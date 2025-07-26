<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Strand;
use App\Models\SubStrand;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function showSubStrandNotes(Subject $subject, Strand $strand, SubStrand $subStrand)
    {
        $notes = $subStrand->notes()->latest()->get();

        return view('notes.index', compact('subject', 'strand', 'subStrand', 'notes'));
    }

    public function show(Subject $subject, Strand $strand, SubStrand $subStrand, Note $note)
    {
        return view('notes.show', compact('subject', 'strand', 'subStrand', 'note'));
    }
}
