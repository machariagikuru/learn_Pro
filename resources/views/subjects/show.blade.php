@extends('layouts.app')

@section('content')
<div class="max-w-5xl py-8 mx-auto">
    <h1 class="mb-6 text-3xl font-bold text-gray-800">{{ $subject->name }}</h1>

    @foreach ($subject->strands as $strand)
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-orange-700">{{ $strand->name }}</h2>
            <ul class="mt-2 ml-4 text-gray-700 list-disc list-inside">
                @foreach ($strand->subStrands as $subStrand)
                    <li>
                        <a href="{{ route('notes.show', [
                            'subject' => $subject->slug,
                            'strand' => $strand->slug,
                            'subStrand' => $subStrand->slug,
                        ]) }}" class="text-blue-600 hover:underline">
                            {{ $subStrand->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
@endsection
