@extends('layouts.app')

@section('content')
<div class="max-w-5xl py-8 mx-auto">
    <h1 class="mb-4 text-3xl font-bold text-gray-800">
        {{ $subStrand->name }} Notes
    </h1>

    <p class="mb-6 text-sm text-gray-500">
        Part of <strong>{{ $strand->name }}</strong> in <strong>{{ $subject->name }}</strong>
    </p>

    @forelse ($notes as $note)
        <div class="p-5 mb-6 transition bg-white rounded-lg shadow hover:shadow-md">
            <h2 class="text-xl font-semibold text-indigo-700">{{ $note->title }}</h2>
            <p class="mt-2 text-gray-600">
                {{ Str::limit(strip_tags($note->content), 150) }}
            </p>
            <a href="{{ route('notes.view', $note) }}" class="inline-block mt-3 text-blue-600 hover:underline">
                Read Full Note →
            </a>
        </div>
    @empty
        <p class="text-gray-500">No notes found for this sub-strand yet.</p>
    @endforelse
</div>
@endsection
