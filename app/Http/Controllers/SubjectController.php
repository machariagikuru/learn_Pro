<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('strands.subStrands')->get();

        return view('subjects.index', compact('subjects'));
    }

    public function show(Subject $subject)
    {
        $subject->load('strands.subStrands');

        return view('subjects.show', compact('subject'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:subjects,slug',
        ]);

        Subject::create($request->only('name', 'slug'));

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:subjects,slug,' . $subject->id,
        ]);

        $subject->update($request->only('name', 'slug'));

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
