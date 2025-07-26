@extends('layouts.app')

@section('content')
<div class="max-w-4xl px-4 py-10 mx-auto">
    <h1 class="mb-4 text-4xl font-bold text-indigo-800">
        {{ $note->title }}
    </h1>

    <p class="mb-6 text-sm text-gray-500">
        Grade {{ $note->grade }} · 
        {{ $note->subStrand->name }} – 
        {{ $note->subStrand->strand->name }} – 
        {{ $note->subStrand->strand->subject->name }}
    </p>

    <div class="prose max-w-none">
        {!! $note->content !!}
    </div>
</div>
@endsection
