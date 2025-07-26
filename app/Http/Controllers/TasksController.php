<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Task;
use App\Models\TaskPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // Using File facade for deletion/checks
use Illuminate\Support\Str;

class TasksController extends Controller
{
    // Destination path within the public directory for task point images
    const IMAGE_DESTINATION_PATH = 'courses';

    /**
     * Show the form for creating a new Task (details only).
     */
    public function create()
    {
        $courses = Course::where('user_id', Auth::id())->orderBy('title')->get();
        $chapters = collect(); // Chapters loaded via AJAX
        return view('instructor.tasks.add_task', compact('courses', 'chapters'));
    }

    /**
     * Store a newly created Task (details only) in storage.
     */
    public function store(Request $request)
    {
        $validatedTaskData = $request->validate([
            'course_id'   => ['required', Rule::exists('courses', 'id')->where('user_id', Auth::id())],
            'chapter_id'  => ['required', Rule::exists('chapters', 'id')->where('course_id', $request->course_id)],
            'task_title'  => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'videos_required_watched'=> 'required|string|max:255',
        ]);

        try {
            $task = Task::create([
                'course_id' => $validatedTaskData['course_id'],
                'chapter_id' => $validatedTaskData['chapter_id'],
                'title' => $validatedTaskData['task_title'],
                'description' => $validatedTaskData['task_description'],
                'videos_required_watched' => $validatedTaskData['videos_required_watched'],
            ]);

            // Get the course and its enrolled students
            $course = Course::findOrFail($validatedTaskData['course_id']);
            
            // Send notification to all enrolled students
            foreach ($course->users as $student) {
                $student->notify(new \App\Notifications\NewCourseContentNotification(
                    $task,
                    'task',
                    $course
                ));
            }

            return redirect()->route('view.tasks')->with('success', 'Task details added! Add points next.');
        } catch (\Exception $e) {
            Log::error("Error storing task details: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Failed to add task details.');
        }
    }

    /**
     * Display a listing of the Tasks.
     */
    public function index(Request $request)
    {
        $query = Task::whereHas('course', fn($q) => $q->where('user_id', Auth::id()));
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        $tasks = $query->with(['chapter', 'course'])
                       ->orderBy('course_id')->orderBy('chapter_id')->latest('tasks.created_at')
                       ->paginate(10)->withQueryString();
        $courses = Course::where('user_id', Auth::id())->orderBy('title')->get();
        return view('instructor.tasks.view_tasks', compact('tasks', 'courses'));
    }

    /**
     * AJAX endpoint to get chapters.
     */
    public function getChapters($courseId)
    {
        $course = Course::where('id', $courseId)->where('user_id', Auth::id())->firstOrFail();
        $chapters = Chapter::where('course_id', $courseId)->select('id', 'title')->orderBy('order')->get();
        return response()->json($chapters);
    }

    /**
     * Show the form for editing a Task and its points.
     */
    public function edit(Task $task)
    {
        if ($task->course->user_id !== Auth::id()) { abort(403); }
        $courses = Course::where('user_id', Auth::id())->orderBy('title')->get();
        $chapters = Chapter::where('course_id', $task->course_id)->orderBy('order')->get();
        $task->loadMissing('taskPoints');
        return view('instructor.tasks.update_task', compact('task', 'courses', 'chapters'));
    }

    /**
     * Update the specified Task and sync its points.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->course->user_id !== Auth::id()) { abort(403); }

        // Validate Task Details
        $validatedTaskData = $request->validate([
            'course_id' => ['required', Rule::exists('courses', 'id')->where('user_id', Auth::id())],
            'chapter_id' => ['required', Rule::exists('chapters', 'id')->where('course_id', $request->course_id)],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'videos_required_watched' => 'required|string|max:255',
        ]);

        // Validate Task Points Data
        $validatedPointsData = $request->validate([
            'points_data'           => 'nullable|array',
            'points_data.*.id'      => 'nullable|integer|exists:task_points,id,task_id,'.$task->id,
            'points_data.*.title'   => 'required_with:points_data|string|max:255',
            'points_data.*.notes'   => 'nullable|string|max:65535',
            'points_data.*.code_block' => 'nullable|string|max:65535',
            'points_data.*.image'   => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'points_data.*.remove_image' => 'nullable|boolean',
            'points_data.*.points_list' => 'required_with:points_data|array|min:1',
            'points_data.*.points_list.*' => 'required|string|max:65535',
        ],[/* Custom messages */]);

        DB::beginTransaction();
        $filesToDeleteAfterCommit = [];
        $newlyMovedFiles = [];
        $destinationPath = public_path(self::IMAGE_DESTINATION_PATH);
        if (!File::isDirectory($destinationPath)) File::makeDirectory($destinationPath, 0775, true, true);

        try {
            // 1. Update Task Details
            $task->update($validatedTaskData);

            // 2. Prepare data for point sync
            $existingPoints = $task->taskPoints()->get()->keyBy('id');
            $submittedPoints = collect($validatedPointsData['points_data'] ?? []);
            $processedIds = []; // Keep track of submitted IDs that exist

            // 3. Update or Create points based on submission
            foreach ($submittedPoints as $index => $pointBlock) {
                $descriptions = isset($pointBlock['points_list']) && is_array($pointBlock['points_list'])
                                ? array_values(array_filter(array_map('trim', $pointBlock['points_list']), fn($d) => $d !== ''))
                                : [];
                $title = isset($pointBlock['title']) && is_string($pointBlock['title']) ? trim($pointBlock['title']) : '';

                // Skip invalid blocks
                if ($title === '' || empty($descriptions)) {
                    continue;
                }

                $pointId = $pointBlock['id'] ?? null;
                $currentPoint = $pointId ? $existingPoints->get($pointId) : null;

                // Skip processing if ID submitted is invalid for this task
                 if ($pointId && !$currentPoint) {
                     Log::warning("Attempted update with invalid TaskPoint ID [{$pointId}] for Task [{$task->id}]. Skipping block.");
                     continue;
                 }

                $notes = isset($pointBlock['notes']) && is_string($pointBlock['notes']) ? trim($pointBlock['notes']) : null;
                $code_block = isset($pointBlock['code_block']) && is_string($pointBlock['code_block']) ? trim($pointBlock['code_block']) : null;
                $removeImage = filter_var($pointBlock['remove_image'] ?? false, FILTER_VALIDATE_BOOLEAN);
                $imageName = $currentPoint ? $currentPoint->image_path : null;

                // Handle Image
                $imageKey = "points_data.{$index}.image";
                if ($request->hasFile($imageKey) && $request->file($imageKey)->isValid()) {
                    if ($imageName) $filesToDeleteAfterCommit[] = $imageName;
                    $imageFile = $request->file($imageKey);
                    $newImageName = time().'_'.Str::slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$imageFile->getClientOriginalExtension();
                    $imageFile->move($destinationPath, $newImageName);
                    $newlyMovedFiles[] = $newImageName;
                    $imageName = $newImageName;
                } elseif ($removeImage && $imageName) {
                    $filesToDeleteAfterCommit[] = $imageName;
                    $imageName = null;
                }

                $dataToSave = [
                    'title' => $title,
                    'notes' => $notes ?: null,
                    'code_block' => $code_block ?: null,
                    'image_path' => $imageName,
                    'points' => $descriptions,
                ];

                if ($currentPoint) {
                    $currentPoint->update($dataToSave);
                    $processedIds[] = $currentPoint->id; // Mark as processed
                } else {
                    // Create new point associated with the task
                    $task->taskPoints()->create($dataToSave);
                }
            }

            // 4. Delete points that were not in the submission
            $idsToDelete = $existingPoints->pluck('id')->diff($processedIds)->all();
            if (!empty($idsToDelete)) {
                $pointsToDelete = TaskPoint::whereIn('id', $idsToDelete)->select(['id', 'image_path'])->get();
                foreach ($pointsToDelete as $p) {
                    if ($p->image_path) $filesToDeleteAfterCommit[] = $p->image_path;
                }
                TaskPoint::destroy($idsToDelete); // Model event handles file deletion from disk
            }

            DB::commit();

            // 5. Delete marked image files after successful DB commit
            foreach (array_unique($filesToDeleteAfterCommit) as $filename) {
                $filePath = public_path(self::IMAGE_DESTINATION_PATH . '/' . $filename);
                if (File::exists($filePath)) File::delete($filePath);
            }

            return redirect()->route('view.tasks')->with('success', 'Task updated successfully!');

        } catch (\Exception $e) {
             DB::rollBack();
             // Cleanup newly moved files on error
             foreach ($newlyMovedFiles as $filename) {
                 $filePath = public_path(self::IMAGE_DESTINATION_PATH . '/' . $filename);
                 if (File::exists($filePath)) File::delete($filePath);
             }
             Log::error("Error updating task {$task->id}: " . $e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
             return redirect()->back()->withInput()->with('error', 'Failed to update task points.');
        }
    }

    /** Remove task */
    public function destroy(Task $task)
    {
        if ($task->course->user_id !== Auth::id()) { abort(403); }
        DB::beginTransaction();
        try {
            // TaskPoint deleting event handles image cleanup when points are deleted
            $task->delete(); // This should cascade delete points if DB constraint exists OR trigger model event if not
            DB::commit();
            return redirect()->route('view.tasks')->with('success', 'Task deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting task {$task->id}: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('view.tasks')->with('error', 'Failed to delete task.');
        }
    }

    /** Show form to add points */
    public function createPoint()
    {
        $tasks = Task::whereHas('course', fn($q) => $q->where('user_id', Auth::id()))
                     ->orderBy('title')->select('id', 'title')->get();
        return view('instructor.tasks.add_task_point', compact('tasks'));
    }

    /** Store new points */
    public function storePoint(Request $request)
    {
         $validatedData = $request->validate([
            'task_id' => ['required', Rule::exists('tasks', 'id')->where(fn($q) => $q->whereIn('course_id', fn($sub) => $sub->select('id')->from('courses')->where('user_id', Auth::id())))],
            'points_data'           => 'required|array|min:1',
            'points_data.*.title'      => 'required|string|max:255',
            'points_data.*.notes'      => 'nullable|string|max:65535',
            'points_data.*.code_block' => 'nullable|string|max:65535',
            'points_data.*.image'      => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'points_data.*.points_list' => 'required|array|min:1',
            'points_data.*.points_list.*' => 'required|string|max:65535',
        ],[ /* Custom messages */ ]);

        DB::beginTransaction();
        $pointsAddedCount = 0;
        $movedImageFiles = [];
        try {
            $taskId = $validatedData['task_id'];
            $destinationPath = public_path(self::IMAGE_DESTINATION_PATH);
             if (!File::isDirectory($destinationPath)) File::makeDirectory($destinationPath, 0775, true, true);

            foreach ($validatedData['points_data'] as $index => $pointBlock) {
                $descriptions = isset($pointBlock['points_list']) && is_array($pointBlock['points_list']) ? array_filter(array_map('trim', $pointBlock['points_list']), fn($d)=>$d!=='') : [];
                $title = isset($pointBlock['title']) && is_string($pointBlock['title']) ? trim($pointBlock['title']) : '';

                if ($title !== '' && !empty($descriptions)) {
                    $imageName = null;
                    $imageKey = "points_data.{$index}.image";
                    if ($request->hasFile($imageKey) && $request->file($imageKey)->isValid()) {
                        $imageFile = $request->file($imageKey);
                        $imageName = time().'_'.Str::slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$imageFile->getClientOriginalExtension();
                        $imageFile->move($destinationPath, $imageName);
                        $movedImageFiles[] = $imageName;
                    }
                    TaskPoint::create([
                        'task_id'     => $taskId,
                        'title'       => $title,
                        'notes'       => isset($pointBlock['notes']) && is_string($pointBlock['notes']) ? trim($pointBlock['notes']) ?: null : null,
                        'code_block'  => isset($pointBlock['code_block']) && is_string($pointBlock['code_block']) ? trim($pointBlock['code_block']) ?: null : null,
                        'image_path'  => $imageName,
                        'points'      => array_values($descriptions), // Ensure sequential keys for JSON
                    ]);
                    $pointsAddedCount++;
                }
            }
            if ($pointsAddedCount > 0) {
                 DB::commit();
                 return redirect()->route('view.tasks')->with('success', $pointsAddedCount . ' task point block(s) added!');
            } else {
                 DB::rollBack();
                 return redirect()->back()->withInput()->with('error', 'No valid task point blocks provided.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            foreach ($movedImageFiles as $filename) { // Cleanup moved files
                 $filePath = public_path(self::IMAGE_DESTINATION_PATH . '/' . $filename);
                 if (File::exists($filePath)) File::delete($filePath);
            }
            Log::error("Error storing task points: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Failed to add task points: '.$e->getMessage());
        }
    }

     /** Display test view */
     public function test($taskId = 1)
     {
         $task = Task::with('taskPoints')->find($taskId);
         $errorMessage = null;
         if (!$task) { $errorMessage = "Task with ID {$taskId} not found."; }
         return view('Home.test', compact('task', 'errorMessage'));
     }
}