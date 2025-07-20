@extends('instructor.layout_instructor')

@section('title', 'Add Task Points')

@section('body')
<div class="page-content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="page-header mb-4 p-3 bg-white shadow-sm rounded">
                <h1 class="mb-0 text-center" style="letter-spacing: 1.5px;">Add Task Points</h1>
            </div>
            <div class="div_deg">
                {{-- Error/Success Messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form class="add" action="{{ route('store.task.point') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Select Task -->
                    <div class="mb-4">
                        <label for="task_id" class="form-label">Select Task <span class="text-danger">*</span></label>
                        <select name="task_id" id="task_id" class="form-select modern-select" required>
                            <option value="" disabled {{ !old('task_id') ? 'selected' : '' }}>-- Choose Task --</option>
                            @forelse ($tasks as $task)
                                <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                    {{ $task->title }}
                                </option>
                            @empty
                                <option value="" disabled>-- No tasks found --</option>
                            @endforelse
                        </select>
                    </div>

                    <hr class="mb-4">

                    {{-- Container for Dynamic Point Blocks --}}
                    <div id="pointBlocksContainer">
                        {{-- Reconstruct blocks from old input if validation failed --}}
                        @if(old('points_data') && is_array(old('points_data')))
                            @foreach(old('points_data') as $index => $oldBlock)
                                @include('instructor.tasks._point_block', ['index' => $index, 'blockData' => $oldBlock])
                            @endforeach
                        @else
                            {{-- Default: Show one empty block initially --}}
                            @include('instructor.tasks._point_block', ['index' => 0, 'blockData' => null])
                        @endif
                    </div>

                    {{-- Button to add more Point Blocks --}}
                    <div class="mb-3">
                        <button type="button" id="addPointBlock" class="btn btn-secondary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Another Point Block (Assignment)
                        </button>
                    </div>

                    {{-- Submit Button --}}
                    <div class="text-center mt-4 pt-3 border-top">
                        <button class="btn btn-primary px-4 py-2" type="submit">Save All Points</button>
                        <a href="{{ route('view.tasks') }}" class="btn btn-secondary px-4 py-2 ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style type="text/css">
    .div_deg { margin: 30px auto; max-width: 900px; padding: 20px; }
    .add { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); width: 100%; }
    .modern-select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right .75rem center; background-size: 16px 12px; }
    .page-header { background-color: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); color: #2c3e50; }
    label.form-label { font-weight: 500; margin-bottom: .5rem; display: block; }
    .btn i { margin-right: 4px; }
    .text-danger { font-size: 0.9em; }
    .point-block { border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem; background-color: #fdfdfd; position: relative; }
    .point-block-header { margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #eee;}
    .point-block-header h5 { margin-bottom: 0; font-size: 1.1rem; color: #333;}
    .remove-block-btn { position: absolute; top: 10px; right: 10px; z-index: 10; }
    .point-description-item .form-control { border-radius: 0.2rem; }
    .point-description-item .btn { border-radius: 0.2rem; padding: .25rem .5rem; font-size: .875rem;}
    textarea.code-block-input { font-family: monospace; white-space: pre; overflow-x: auto; font-size: 0.9em; line-height: 1.4; background-color: #f0f0f0; border: 1px dashed #ccc; }
    .form-control-sm { padding: .25rem .5rem; font-size: .875rem; border-radius: .2rem; }
    .btn-sm { padding: .25rem .5rem; font-size: .875rem; border-radius: .2rem; }
     .existing-image-preview img { vertical-align: middle; max-height: 100px; max-width: 180px; border-radius: 4px; margin-right: 10px; border: 1px solid #ddd; object-fit: contain; display: inline-block; }
     .form-check-label.small { font-size: 0.85em; }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize index based on initially rendered blocks
        let blockIndex = $('#pointBlocksContainer .point-block').length;

        // Add Point Block
        $('#addPointBlock').on('click', function() {
            let newIndexKey = `new_${blockIndex}`; // Unique key for new blocks
            // Fetch the partial content via AJAX or define inline
            // Using inline for simplicity:
            const blockHtml = `
                <div class="point-block mb-4" data-index="${newIndexKey}">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-block-btn" title="Remove this entire block"> <i class="fas fa-times"></i> </button>
                    <div class="point-block-header"> <h5>Point Block Details</h5> </div>
                    <input type="hidden" name="points_data[${newIndexKey}][id]" value="">

                    <div class="mb-3"> <label for="title_${newIndexKey}" class="form-label">Title <span class="text-danger">*</span></label> <input type="text" id="title_${newIndexKey}" name="points_data[${newIndexKey}][title]" class="form-control form-control-sm" placeholder="Assignment Title " required> </div>
                    <div class="mb-3"> <label for="notes_${newIndexKey}" class="form-label">Notes (Optional)</label> <textarea id="notes_${newIndexKey}" name="points_data[${newIndexKey}][notes]" class="form-control form-control-sm" rows="2" placeholder="Optional notes or instructions"></textarea> </div>
                    <div class="mb-3"> <label for="code_block_${newIndexKey}" class="form-label">Code Block (Optional)</label> <textarea id="code_block_${newIndexKey}" name="points_data[${newIndexKey}][code_block]" class="form-control form-control-sm code-block-input" rows="4" placeholder="Optional code snippet"></textarea> </div>
                    <div class="mb-3"> <label for="image_${newIndexKey}" class="form-label">Image (Optional)</label> <input type="file" class="form-control form-control-sm" id="image_${newIndexKey}" name="points_data[${newIndexKey}][image]" accept="image/*"> <small class="form-text text-muted">Max 2MB. Allowed: jpg, png, gif, svg.</small> </div>
                    <div class="mb-3"> <label class="form-label">Point Descriptions <span class="text-danger">*</span></label> <div class="point-descriptions-container"> <div class="point-description-item input-group mb-2"> <input type="text" name="points_data[${newIndexKey}][points_list][]" class="form-control form-control-sm" placeholder="Enter point description" required> <button type="button" class="btn btn-outline-danger btn-sm remove-description-btn">-</button> </div> </div> <button type="button" class="btn btn-outline-secondary btn-sm add-description-btn mt-2"> <i class="fas fa-plus"></i> Add Description Line </button> </div>
                </div>
            `;
            $('#pointBlocksContainer').append(blockHtml);
            blockIndex++;
        });

        // Remove Point Block
        $('#pointBlocksContainer').on('click', '.remove-block-btn', function() {
            if ($('#pointBlocksContainer .point-block').length > 1) {
                $(this).closest('.point-block').remove();
            } else {
                alert('At least one point block is required.');
            }
        });

        // Add Point Description Line
        $('#pointBlocksContainer').on('click', '.add-description-btn', function() {
            const descriptionContainer = $(this).closest('.point-block').find('.point-descriptions-container');
            const currentBlockIndex = $(this).closest('.point-block').data('index');
            const newDescriptionHtml = `
                <div class="point-description-item input-group mb-2">
                    <input type="text" name="points_data[${currentBlockIndex}][points_list][]" class="form-control form-control-sm" placeholder="Enter point description" required>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-description-btn">-</button>
                </div>
            `;
            descriptionContainer.append(newDescriptionHtml);
        });

        // Remove Point Description Line
        $('#pointBlocksContainer').on('click', '.remove-description-btn', function() {
            const descriptionContainer = $(this).closest('.point-descriptions-container');
            if (descriptionContainer.find('.point-description-item').length > 1) {
                $(this).closest('.point-description-item').remove();
            } else {
                $(this).closest('.point-description-item').find('input[type="text"]').val(''); // Clear last one
            }
        });

        // Pre-select Task if task_id in URL
        const urlParams = new URLSearchParams(window.location.search);
        const taskIdFromUrl = urlParams.get('task_id');
        if (taskIdFromUrl) {
            $('#task_id').val(taskIdFromUrl);
        }
    });
</script>
@endsection