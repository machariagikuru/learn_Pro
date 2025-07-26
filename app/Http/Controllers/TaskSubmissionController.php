<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskSubmittedNotification;

class TaskSubmissionController extends Controller
{
    public function submit(Request $request, Task $task)
    {
        try {
            // Check if user has already submitted this task
            $existingSubmission = TaskSubmission::where('user_id', Auth::id())
                ->where('task_id', $task->id)
                ->first();

            if ($existingSubmission) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already submitted this task.',
                    'submission' => [
                        'status' => $existingSubmission->status,
                        'grade' => $existingSubmission->grade,
                        'feedback' => $existingSubmission->feedback
                    ]
                ], 400);
            }

            // Basic validation
            $request->validate([
                'files.*' => 'required|file|max:10240|mimes:jpeg,png,gif,mp4,pdf,psd,ai,doc,docx,ppt,pptx',
                'notes' => 'nullable|string'
            ]);

            // Create submission record
            $submission = TaskSubmission::create([
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'course_id' => $task->chapter->course_id,
                'notes' => $request->notes ?? '',
                'status' => 'pending'
            ]);

            // Handle multiple file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    // Generate unique filename
                    $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    
                    // Store file in storage/app/public/task_submissions
                    $filePath = $file->storeAs('task_submissions', $fileName, 'public');
                    
                    // Create file record
                    \App\Models\SubmissionFile::create([
                        'task_submission_id' => $submission->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize()
                    ]);
                }
            }

            // Send notification to the course instructor
            $course = $task->chapter->course;
            if ($course && $course->instructor) {
                $course->instructor->notify(new TaskSubmittedNotification($submission));
            }

            return response()->json([
                'success' => true,
                'message' => 'Task submitted successfully!',
                'submission_id' => $submission->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Task submission error: ' . $e->getMessage());
            
            // Delete the submission if it was created but files failed
            if (isset($submission)) {
                $submission->delete();
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit task. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function review(Request $request, TaskSubmission $submission)
    {
        // Validate the request
        $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'required|string'
        ]);

        try {
            // Update the submission with grade and feedback
            $submission->update([
                'grade' => $request->grade,
                'feedback' => $request->feedback,
                'status' => 'reviewed'
            ]);

            // Send notification to the student
            $submission->user->notify(new \App\Notifications\TaskSubmissionReviewedNotification(
                $submission,
                $request->grade,
                $request->feedback
            ));

            return redirect()->back()->with('success', 'Task review submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit review. Please try again.');
        }
    }

    public function getSubmissionsByTask(Task $task)
    {
        $submissions = TaskSubmission::where('task_id', $task->id)
            ->with(['user', 'course'])
            ->latest()
            ->paginate(10);

        return view('instructor.tasks.submissions', compact('submissions', 'task'));
    }

    public function getSubmissionsByCourse(Course $course)
    {
        $submissions = TaskSubmission::where('course_id', $course->id)
            ->with(['user', 'task'])
            ->latest()
            ->paginate(10);

        return view('instructor.courses.submissions', compact('submissions', 'course'));
    }

    public function getUserSubmissions()
    {
        $submissions = TaskSubmission::where('user_id', auth()->id())
            ->with(['task.chapter.course', 'files'])
            ->latest()
            ->paginate(10);

        return view('task.user-submissions', compact('submissions'));
    }

    /**
     * Check if user has already submitted this task
     */
    public function checkSubmission(Task $task)
    {
        $submission = TaskSubmission::where('task_id', $task->id)
            ->where('user_id', auth()->id())
            ->first();

        return response()->json([
            'exists' => !is_null($submission),
            'submission' => $submission
        ]);
    }

    public function submitTask(Request $request, Task $task)
    {
        // Validate the request
        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,gif,mp4,pdf,psd,ai,doc,docx,ppt,pptx|max:10240',
            'notes' => 'nullable|string',
        ]);

        // Create task submission
        $submission = \App\Models\TaskSubmission::create([
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Generate unique file name
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                // Store file in public/courses directory
                $file->move(public_path('courses'), $fileName);
                $filePath = 'courses/' . $fileName;
                
                // Create file record in database
                \App\Models\SubmissionFile::create([
                    'task_submission_id' => $submission->id,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Task submitted successfully',
            'submission_id' => $submission->id
        ]);
    }

    public function downloadSubmissionFile($submissionId, $fileId)
    {
        // Find the submission file
        $file = \App\Models\SubmissionFile::findOrFail($fileId);
        $submission = \App\Models\TaskSubmission::findOrFail($submissionId);

        // Check if user is authorized to download this file
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Allow access if:
        // 1. User is the submission owner
        // 2. User is the course instructor
        // 3. User is an admin
        $isOwner = $submission->user_id === $user->id;
        $isInstructor = $submission->task->chapter->course->user_id === $user->id;
        $isAdmin = $user->usertype === 'admin';

        if (!$isOwner && !$isInstructor && !$isAdmin) {
            abort(403, 'You do not have permission to access this file');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found');
        }

        // Return file as download
        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }
} 