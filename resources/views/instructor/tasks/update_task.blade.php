@extends('instructor.layout_instructor')

@section('title', 'Update Task & Points')

@section('body')
<div class="container my-4"> {{-- Added overall spacing --}}

    <!-- Page Header -->
    <div class="page-header text-center p-4 mb-4 bg-white shadow-sm rounded border border-light">
        <h1 class="mb-0" style="letter-spacing: 1px; font-weight: 600; color: #343a40;">
            Update Task & Points
        </h1>
    </div>

    {{-- Error/Success Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
             <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
             <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Update Task Form -->
    <div class="card shadow-sm mt-4 form-main-card">
        <div class="card-body p-lg-4 p-md-3 p-2">
            <form action="{{ route('update.task', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Task Details Section --}}
                <fieldset class="mb-4">
                    <legend class="form-legend">Task Details</legend>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="course_id" class="form-label">Course:</label>
                            <select name="course_id" id="course_id" class="form-select form-select-sm modern-select" required>
                                <option value="" disabled>-- Select Course --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $task->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="chapter_id" class="form-label">Chapter:</label>
                            {{-- Chapters are pre-populated if editing, AJAX handles changes --}}
                            <select name="chapter_id" id="chapter_id" class="form-select form-select-sm modern-select" required {{ $chapters->isEmpty() ? 'disabled' : '' }}>
                                <option value="" disabled {{ !$task->chapter_id && !old('chapter_id') ? 'selected' : '' }}>
                                     {{ $task->course_id || old('course_id') ? '-- Select Chapter --' : '-- Choose Course First --' }}
                                 </option>
                                @foreach($chapters as $chapter) {{-- Passed from edit method --}}
                                    <option value="{{ $chapter->id }}" {{ old('chapter_id', $task->chapter_id) == $chapter->id ? 'selected' : '' }}>
                                        {{ $chapter->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">Task Title:</label>
                            <input type="text" name="title" id="title" class="form-control form-control-sm" value="{{ old('title', $task->title) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Task Description:</label>
                            <textarea name="description" id="description" class="form-control form-control-sm" rows="1">{{ old('description', $task->description) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="videos_required_watched" class="form-label">Videos Required:</label>
                            <input type="text" name="videos_required_watched" id="videos_required_watched" class="form-control form-control-sm" value="{{ old('videos_required_watched', $task->videos_required_watched) }}" required>
                        </div>
                    </div>
                </fieldset>

                {{-- Task Points Section - Wrapped in Fieldset --}}
                <fieldset class="mb-4 points-section-fieldset">
                     <legend class="form-legend">Task Points</legend>
                     <div class="points-section-content">
                        <div id="pointBlocksContainerUpdate">
                            {{-- Determine data source: old input or existing model data --}}
                            @php
                                $pointsData = old('points_data');
                                if ($pointsData === null && $task->taskPoints) {
                                    $pointsData = $task->taskPoints->sortBy('created_at')->map(fn($p) => [ // Sort points
                                        'id' => $p->id, 'title' => $p->title, 'notes' => $p->notes,
                                        'code_block' => $p->code_block, 'image_path' => $p->image_path,
                                        'points_list' => $p->points ?? []
                                    ])->toArray();
                                } elseif (!is_array($pointsData)) {
                                    $pointsData = [];
                                }
                                $pointsData = array_values($pointsData); // Ensure sequential keys for JS
                            @endphp

                            {{-- Loop and include partial --}}
                            @forelse($pointsData as $index => $blockData)
                                {{-- Each block is wrapped in a styled div here for the 'box' effect --}}
                                <div class="card mb-4 point-block-card">
                                    <div class="card-body">
                                        @include('instructor.tasks._point_block', [
                                            'index' => $blockData['id'] ?? "new_$index", // Use ID or new index key
                                            'blockData' => $blockData
                                        ])
                                    </div>
                                </div>
                            @empty
                                {{-- Display one empty block if there's no data --}}
                                <div class="card mb-4 point-block-card">
                                    <div class="card-body">
                                        @include('instructor.tasks._point_block', [
                                            'index' => 'new_0',
                                            'blockData' => null
                                        ])
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        {{-- Button to add more blocks --}}
                        <div class="mt-3 text-center">
                            <button type="button" id="addPointBlockUpdate" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-plus me-1"></i> Add Another Point Block
                            </button>
                        </div>
                    </div> {{-- End Content wrapper --}}
                </fieldset>


                <!-- Submit Button -->
                <div class="text-center mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary px-5 py-2">
                        <i class="fas fa-save me-1"></i> Update Task
                    </button>
                    <a href="{{ route('view.tasks') }}" class="btn btn-light px-4 py-2 ms-2 border">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- Bootstrap JS Bundle for dismissible alerts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        // --- Chapter Loading AJAX ---
        function fetchChapters(courseId, targetChapterId = null) {
            var chapterSelect = $('#chapter_id');
            var currentVal = chapterSelect.val();
            chapterSelect.prop('disabled', true).empty().append('<option value="">-- Loading... --</option>');
            if (courseId) {
                $.ajax({
                    url: '/get-chapters/' + courseId, // Ensure this route exists
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        chapterSelect.prop('disabled', false).empty().append('<option value="" disabled selected>-- Choose Chapter --</option>');
                        if (data && data.length > 0) {
                            $.each(data, function(key, chapter) {
                                // Determine selection based on target OR old value
                                var isSelected = (targetChapterId && chapter.id == targetChapterId) ||
                                                 ('{{ old("chapter_id") }}' == chapter.id && !targetChapterId); // Prioritize target, then old
                                chapterSelect.append(`<option value="${chapter.id}" ${isSelected ? 'selected' : ''}>${chapter.title}</option>`);
                            });
                             // Ensure targetChapterId (usually the initial value) is selected if no old value was chosen
                             if (targetChapterId && !chapterSelect.val() && '{{ !old("chapter_id") }}') {
                                 chapterSelect.val(targetChapterId);
                             }
                        } else {
                            chapterSelect.append('<option value="" disabled>-- No Chapters Found --</option>');
                        }
                        // Trigger change only if value differs from initial load value (less likely needed on update)
                        // if (chapterSelect.val() !== currentVal) { chapterSelect.trigger('change'); }
                    },
                    error: function() {
                        chapterSelect.prop('disabled', false).empty().append('<option value="" disabled>-- Error Loading --</option>');
                        console.error("Failed to load chapters for course ID: " + courseId);
                    }
                });
            } else {
                chapterSelect.prop('disabled', false).empty().append('<option value="" disabled selected>-- Choose Course First --</option>');
            }
        }

        // Bind chapter loading to course change
        $('#course_id').on('change', function() {
            fetchChapters($(this).val()); // On change, don't pass targetChapterId
        });

        // Initial chapter load on page load for editing
        var initialCourseId = $('#course_id').val();
        var initialChapterId = '{{ old("chapter_id", $task->chapter_id) }}'; // Use old value or model value
        if (initialCourseId) {
            fetchChapters(initialCourseId, initialChapterId); // Load initial chapters/selection
        } else {
            $('#chapter_id').prop('disabled', true); // Disable if no course selected
        }


        // --- Dynamic Task Points (for Update Form) ---
        // Calculate next index for NEW blocks, starting high to avoid collision with existing IDs
        let blockCounter = {{ $task->taskPoints->max('id') ?? 0 }} + {{ count($pointsData ?? []) }} + 1000; // Start high

        // Add New Point Block
        $('#addPointBlockUpdate').on('click', function() {
            let newIndexKey = `new_${blockCounter}`;
            // HTML Template for a new block (matches the partial structure)
            const blockHtml = `
                <div class="card mb-4 point-block-card">
                    <div class="card-body">
                        <div class="point-block" data-index="${newIndexKey}">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-block-btn" title="Remove block"><i class="fas fa-times"></i></button>
                            <div class="point-block-content">
                                <div class="point-block-header"><h5>New Point Block Details</h5></div>
                                <input type="hidden" name="points_data[${newIndexKey}][id]" value="">
                                <div class="mb-3"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" name="points_data[${newIndexKey}][title]" class="form-control form-control-sm" required></div>
                                <div class="mb-3"><label class="form-label">Notes</label><textarea name="points_data[${newIndexKey}][notes]" class="form-control form-control-sm" rows="2"></textarea></div>
                                <div class="mb-3"><label class="form-label">Code Block</label><textarea name="points_data[${newIndexKey}][code_block]" class="form-control form-control-sm code-block-input" rows="4"></textarea></div>
                                <div class="mb-3"><label class="form-label">Image</label><input type="file" name="points_data[${newIndexKey}][image]" class="form-control form-control-sm" accept="image/*"><small class="form-text text-muted">Max 2MB</small></div>
                                <div class="mb-3"><label class="form-label">Descriptions <span class="text-danger">*</span></label><div class="point-descriptions-container"><div class="point-description-item input-group mb-2"><input type="text" name="points_data[${newIndexKey}][points_list][]" class="form-control form-control-sm" required><button type="button" class="btn btn-outline-danger btn-sm remove-description-btn">-</button></div></div><button type="button" class="btn btn-outline-secondary btn-sm add-description-btn mt-2"><i class="fas fa-plus"></i> Line</button></div>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('#pointBlocksContainerUpdate').append(blockHtml);
            blockCounter++; // Increment counter for the next new block
        });

        // Remove Point Block (targets the outer card)
        $('#pointBlocksContainerUpdate').on('click', '.remove-block-btn', function() {
            $(this).closest('.point-block-card').remove(); // Remove the entire card
             // Optional: Add back an empty block if none are left
            // if($('#pointBlocksContainerUpdate .point-block-card').length === 0) {
            //      $('#addPointBlockUpdate').trigger('click');
            // }
        });

        // Add Description Line (targets within the block card)
        $('#pointBlocksContainerUpdate').on('click', '.add-description-btn', function() {
            const container = $(this).closest('.point-block').find('.point-descriptions-container');
            const index = $(this).closest('.point-block').data('index'); // Get the block's index (ID or new_X)
            const html = `<div class="point-description-item input-group mb-2"><input type="text" name="points_data[${index}][points_list][]" class="form-control form-control-sm" placeholder="Enter point description" required><button type="button" class="btn btn-outline-danger btn-sm remove-description-btn">-</button></div>`;
            container.append(html);
        });

        // Remove Description Line (targets within the block card)
        $('#pointBlocksContainerUpdate').on('click', '.remove-description-btn', function() {
            const container = $(this).closest('.point-descriptions-container');
            if (container.find('.point-description-item').length > 1) {
                 $(this).closest('.point-description-item').remove();
            } else {
                 // Clear the value of the last input instead of removing it
                 $(this).closest('.point-description-item').find('input[type="text"]').val('');
                 alert('At least one description line is required per block.'); // Optional feedback
            }
        });

        // Image remove checkbox visual feedback
        $('#pointBlocksContainerUpdate').on('change', 'input[type="checkbox"][name$="[remove_image]"]', function() {
            // Toggle opacity and add/remove a class for more styling options
            const imgPreview = $(this).closest('.existing-image-preview').find('img');
            if ($(this).is(':checked')) {
                imgPreview.css('opacity', '0.4').addClass('marked-for-removal');
            } else {
                 imgPreview.css('opacity', '1').removeClass('marked-for-removal');
            }
         });

    });
</script>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* General Styles */
    body { background-color: #f8f9fa; }
    .container { max-width: 960px; }
    .form-main-card { border: none; }
    .modern-select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right .75rem center; background-size: 16px 12px; }
    .page-header { border: 1px solid #e9ecef; }
    label.form-label { font-weight: 500; margin-bottom: .5rem; display: block; color: #495057; font-size: 0.9rem; }
    .btn i { margin-right: 0.4em; }
    .text-danger { font-size: 0.9em; }
    select:disabled { background-color: #e9ecef; opacity: 0.7; }
    .form-control-sm, .form-select-sm { padding: .3rem .6rem; font-size: .875rem; border-radius: .2rem; }
    .btn-sm { padding: .3rem .6rem; font-size: .875rem; border-radius: .2rem; }
    textarea.form-control-sm { min-height: calc(1.5em + .6rem + 2px); }

    /* Fieldset Styling */
    fieldset {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1.5rem 1.75rem;
        background-color: #fff;
         margin-bottom: 2rem !important;
    }
     fieldset legend.form-legend {
        float: none; width: auto; padding: 0 0.75rem;
        font-size: 1.1rem; font-weight: 600; color: #495057; margin-bottom: 1rem;
     }

    /* Specific Points Fieldset */
    .points-section-fieldset {
         background-color: #f8f9fa; border-color: #e9ecef;
    }
     .points-section-content { /* Padding inside fieldset */ }

    /* Point Block Card Styling (Outer card for each point block) */
    .point-block-card {
        border: 1px solid #c0c9d2; /* Clearer border */
        box-shadow: 0 2px 5px rgba(0,0,0,0.07); /* Slightly stronger shadow */
        margin-bottom: 1.75rem !important; /* Ensure spacing */
    }
    .point-block-card .card-body {
        padding: 0; /* Remove card-body padding, handled by inner div */
    }


    /* Inner Point Block Structure (from partial) */
    .point-block {
        /* This div inside the card-body holds the content */
         padding: 1.5rem;
         position: relative;
         /* Removed background, border, shadow - handled by outer card */
    }
    .point-block-header { margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e9ecef; }
    .point-block-header h5 { margin-bottom: 0; font-size: 1.1rem; color: #343a40; font-weight: 600; }
    .remove-block-btn { position: absolute; top: 0.75rem; right: 0.75rem; z-index: 10; } /* Position relative to inner block */
    .point-description-item .form-control { /* ... */ }
    .point-description-item .btn { /* ... */ }
    textarea.code-block-input { font-family: monospace; white-space: pre; overflow-x: auto; font-size: 0.9em; line-height: 1.4; background-color: #e9ecef; border: 1px dashed #adb5bd; }
    .existing-image-preview img { vertical-align: middle; max-height: 150px; max-width: 250px; border-radius: 5px; margin-right: 15px; border: 1px solid #dee2e6; object-fit: contain; background-color: #f8f9fa; display: inline-block; }
    .existing-image-preview img.marked-for-removal { opacity: 0.4; border-style: dashed; border-color: #dc3545; } /* Visual cue for removal */
    .form-check-label.small { font-size: 0.85em; cursor: pointer; }
    .form-check-inline { margin-right: 0; vertical-align: middle; }
    textarea#description { line-height: 1.5; } /* Ensure task description textarea is normal height */
</style>
@endsection