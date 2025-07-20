<?php

namespace App\Http\Controllers;

use App\Models\InsightQuest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InsightQuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quests = InsightQuest::where('user_id', Auth::id())
            ->with('course')
            ->latest()
            ->paginate(10);
            
        return view('instructor.Insight Quest.view', compact('quests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::where('user_id', Auth::id())->get();
        return view('instructor.Insight Quest.add', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'description' => $request->description,
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/quest_images', $imageName);
            $data['image'] = 'quest_images/' . $imageName;
        }

        InsightQuest::create($data);

        return redirect()->route('insight_quest.view')
            ->with('success', 'Insight Quest created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $quest = InsightQuest::where('user_id', Auth::id())
            ->findOrFail($id);
        $courses = Course::where('user_id', Auth::id())->get();
            
        return view('instructor.Insight Quest.update', compact('quest', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $quest = InsightQuest::where('user_id', Auth::id())
            ->findOrFail($id);

        $data = [
            'description' => $request->description,
            'course_id' => $request->course_id,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($quest->image) {
                Storage::delete('public/' . $quest->image);
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/quest_images', $imageName);
            $data['image'] = 'quest_images/' . $imageName;
        }

        $quest->update($data);

        return redirect()->route('insight_quest.view')
            ->with('success', 'Insight Quest updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $quest = InsightQuest::where('user_id', Auth::id())
            ->findOrFail($id);
            
        // Delete image if exists
        if ($quest->image) {
            Storage::delete('public/' . $quest->image);
        }
        
        $quest->delete();

        return redirect()->route('insight_quest.view')
            ->with('success', 'Insight Quest deleted successfully.');
    }

    /**
     * Search for quests based on title or description.
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $quests = InsightQuest::where('user_id', Auth::id())
            ->where(function($query) use ($search) {
                $query->where('description', 'like', "%{$search}%");
            })
            ->with('course')
            ->latest()
            ->paginate(10);
        return view('instructor.Insight Quest.view', compact('quests'));
    }
} 