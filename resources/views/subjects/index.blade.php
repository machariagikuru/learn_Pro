@extends('layouts.app')

@section('content')
<div class="max-w-5xl py-8 mx-auto">
    <h1 class="mb-6 text-3xl font-bold text-gray-800">All Subjects</h1>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
        @foreach ($subjects as $subject)
            <a href="{{ route('subjects.show', $subject) }}" class="block p-5 transition bg-white rounded-lg shadow hover:shadow-md">
                <h2 class="text-xl font-semibold text-indigo-700">{{ $subject->name }}</h2>
                <p class="mt-2 text-sm text-gray-500">
                    {{ $subject->strands->count() }} strands
                </p>
            </a>
        @endforeach
    </div>
</div>
@endsection
