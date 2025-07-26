<?php

namespace App\Http\Controllers;

use App\Models\InsightQuest;
use App\Models\InsightQuestSubmission;
use App\Models\Course;
use App\Models\InsightQuestSubmissionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InsightQuestSubmissionController extends Controller
{
    public function upload(Request $request, $questId)
    {
        \Log::info('InsightQuest upload called', ['files' => $request->file('files')]);

        // Force files to always be an array
        $files = $request->file('files');
        if (!is_array($files)) {
            $files = $files ? [$files] : [];
        }
        $request->merge(['files' => $files]);

        $request->validate([
            'files'   => 'required',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,pdf,psd,ai,doc,docx,ppt,pptx|max:10240',
        ]);
        \Log::info('Validation passed');

        $quest = InsightQuest::findOrFail($questId);
        
        // Check if user already submitted
        $existing = InsightQuestSubmission::where('insight_quest_id', $quest->id)
            ->where('user_id', auth()->id())
            ->first();
        if ($existing) {
            return redirect()->route('course.content', ['id' => $quest->course_id])
                ->with('error', 'لقد قمت بتسليم هذه المهمة بالفعل ولا يمكنك التسليم مرة أخرى.');
        }
        
        // Create the submission first
        $submission = InsightQuestSubmission::create([
            'insight_quest_id' => $quest->id,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);
        
        // Save each file in the new table
        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            // نضيف الوقت أو رقم عشوائي لتفادي التكرار
            $uniqueName = time() . '_' . Str::slug($fileName) . '.' . $extension;
            $path = $file->storeAs('insight_quest_uploads', $uniqueName, 'public');
            InsightQuestSubmissionFile::create([
                'insight_quest_submission_id' => $submission->id,
                'file_name' => $originalName,
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
        return redirect()->route('course.content', ['id' => $quest->course_id])
            ->with('success', 'Assignment submitted successfully! Now under review by the instructor, you will be notified of your grade when completed.');
    }

    public function allSubmissions(Request $request)
    {
        $courses = Course::where('user_id', auth()->id())->get();
        $query = InsightQuestSubmission::with(['insightQuest.course', 'user', 'files']);
        if ($request->filled('course_id')) {
            $query->whereHas('insightQuest', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $submissions = $query->latest()->paginate(20);
        return view('instructor.Insight Quest.submissions', compact('submissions', 'courses'));
    }

    public function review(Request $request, $submissionId)
    {
        $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'required|string',
        ]);
        $submission = InsightQuestSubmission::findOrFail($submissionId);
        $submission->grade = $request->grade;
        $submission->feedback = $request->feedback;
        $submission->status = 'reviewed';
        $submission->save();
        return back()->with('success', 'Submission reviewed successfully!');
    }

    public function download($file)
    {
        // ابحث عن الملف في قاعدة البيانات
        $fileRow = InsightQuestSubmissionFile::where('file_path', 'insight_quest_uploads/' . $file)->firstOrFail();

        // تحقق من وجود الملف فعليًا
        if (!Storage::disk('public')->exists($fileRow->file_path)) {
            abort(404);
        }

        // حمل الملف بالاسم الأصلي
        return Storage::disk('public')->download($fileRow->file_path, $fileRow->file_name);
    }

    /**
     * Show the submission status for a user for a specific quest.
     */
    public function showSubmissionStatus(Request $request, $questId)
    {
        $userId = auth()->id();
        $submission = InsightQuestSubmission::where('insight_quest_id', $questId)
            ->where('user_id', $userId)
            ->with('files')
            ->first();
        if ($submission) {
            // Return submission data (status, grade, feedback, files, etc)
            return response()->json([
                'submitted' => true,
                'status' => $submission->status,
                'grade' => $submission->grade,
                'feedback' => $submission->feedback,
                'files' => $submission->files,
            ]);
        } else {
            return response()->json(['submitted' => false]);
        }
    }
} 