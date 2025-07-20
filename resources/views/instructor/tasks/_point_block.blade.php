{{-- resources/views/instructor/tasks/_point_block.blade.php --}}
@php
    // --- PHP block remains the same ---
    $currentIndex = $index ?? 'NEW_INDEX_PLACEHOLDER';
    $blockData = $blockData ?? [];
    $title = $blockData['title'] ?? '';
    $notes = $blockData['notes'] ?? '';
    $code_block = $blockData['code_block'] ?? '';
    $blockId = $blockData['id'] ?? null;
    $existing_image_filename = $blockData['image_path'] ?? ($blockId ? \App\Models\TaskPoint::find($blockId)?->image_path : null);
    $existing_image_url = $existing_image_filename ? asset('courses/' . $existing_image_filename) : null;
    $points_list = $blockData['points_list'] ?? [''];
    if (empty($points_list) || !is_array($points_list)) $points_list = [''];
@endphp

<div class="point-block" data-index="{{ $currentIndex }}">
    <div class="point-block-header">
        <h5>Point Block Details</h5>
        <button type="button" class="remove-block-btn" title="Remove this entire block">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <input type="hidden" name="points_data[{{ $currentIndex }}][id]" value="{{ $blockId ?? '' }}">

    <div>
        <label for="title_{{ $currentIndex }}">Title <span class="text-danger">*</span></label>
        <input type="text" id="title_{{ $currentIndex }}" name="points_data[{{ $currentIndex }}][title]" value="{{ $title }}" placeholder="Enter point title" required>
    </div>

    <div>
        <label for="notes_{{ $currentIndex }}">Notes (Optional)</label>
        <textarea id="notes_{{ $currentIndex }}" name="points_data[{{ $currentIndex }}][notes]" placeholder="Enter optional notes">{{ $notes }}</textarea>
    </div>

    <div>
        <label for="code_block_{{ $currentIndex }}">Code Block (Optional)</label>
        <textarea id="code_block_{{ $currentIndex }}" name="points_data[{{ $currentIndex }}][code_block]" class="code-block-input" placeholder="Enter code block">{{ $code_block }}</textarea>
    </div>

    <div>
        <label for="image_{{ $currentIndex }}">Image (Optional)</label>
        <input type="file" id="image_{{ $currentIndex }}" name="points_data[{{ $currentIndex }}][image]" accept="image/*">
        @if ($existing_image_url)
            <div class="existing-image-preview">
                <img src="{{ $existing_image_url }}" alt="Current Image">
                <div class="remove-image-option">
                    <input type="checkbox" id="remove_image_{{ $currentIndex }}" name="points_data[{{ $currentIndex }}][remove_image]" value="1">
                    <label for="remove_image_{{ $currentIndex }}">Remove Current Image</label>
                </div>
            </div>
        @endif
        <small>Max 2MB. Allowed: jpg, png, gif, svg.</small>
    </div>

    <div>
        <label>Point Descriptions <span class="text-danger">*</span></label>
        <div class="point-descriptions-container">
            @foreach ($points_list as $description)
                <div class="point-description-item">
                    <input type="text" name="points_data[{{ $currentIndex }}][points_list][]" value="{{ $description }}" placeholder="Enter point description" required>
                    <button type="button" class="remove-description-btn">-</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="add-description-btn">
            <i class="fas fa-plus"></i> Add Description Line
        </button>
    </div>
</div>

<style type="text/css">
    .point-block {
        background: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .point-block:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .point-block-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .point-block-header h5 {
        margin: 0;
        color: #2196F3;
        font-size: 20px;
        font-weight: 600;
    }

    .remove-block-btn {
        background: #ff5252;
        border: none;
        color: white;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .remove-block-btn:hover {
        background: #ff1744;
        transform: rotate(90deg);
    }

    .point-block > div {
        margin-bottom: 20px;
    }

    input[type='text'],
    textarea {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 25px;
        font-size: 16px;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    input[type='text']:focus,
    textarea:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.2);
        outline: none;
    }

    input[type='text']:hover,
    textarea:hover {
        border-color: #2196F3;
    }

    textarea {
        resize: vertical;
        min-height: 120px;
        border-radius: 15px;
    }

    .code-block-input {
        font-family: 'Fira Code', monospace;
        background-color: #f8f9fa;
        padding: 15px 20px;
        line-height: 1.6;
    }

    input[type='file'] {
        width: 100%;
        padding: 12px 20px;
        border: 2px dashed #2196F3;
        border-radius: 25px;
        font-size: 16px;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    input[type='file']:hover {
        background-color: #e3f2fd;
    }

    .existing-image-preview {
        margin: 15px 0;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 15px;
        border: 1px solid #e0e0e0;
    }

    .existing-image-preview img {
        max-width: 200px;
        max-height: 150px;
        border-radius: 8px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .remove-image-option {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .point-description-item {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
    }

    .remove-description-btn {
        background: #ff5252;
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 18px;
        padding: 0;
        line-height: 1;
    }

    .remove-description-btn:hover {
        background: #ff1744;
        transform: rotate(90deg);
    }

    .add-description-btn {
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 25px;
        padding: 10px 20px;
        cursor: pointer;
        margin-top: 15px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .add-description-btn:hover {
        background: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .add-description-btn i {
        font-size: 16px;
    }

    small {
        color: #757575;
        font-size: 14px;
        display: block;
        margin-top: 8px;
    }

    .text-danger {
        color: #ff1744;
    }
</style>