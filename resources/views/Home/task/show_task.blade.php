@extends('layouts.head') {{-- Assuming layouts.head includes necessary header/meta/css --}}
@section('content')
    <title>Task View: {{ $task->title ?? 'Task Details' }}</title>

    {{-- Include Font Awesome if not already in layouts.head --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Include SweetAlert2 if not already in layouts.head --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Modern Design System */
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --dark-text: #1e293b;
            --gray-text: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--light-bg);
            padding: 0;
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--dark-text);
        }

        .container {
            /* Use standard bootstrap container or custom width */
             max-width: 1140px; /* Example max-width */
             margin-left: auto;
             margin-right: auto;
             padding-left: 15px;
             padding-right: 15px;
        }

        .task-container {
            background-color: #ffffff;
            padding: 2rem 3rem;
            margin: 2rem auto 0; /* Add top margin */
            border-radius: 0 0 1rem 1rem; /* Keep bottom radius */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
            border: 1px solid var(--border-color);
        }

        .task-container:hover {
            transform: translateY(-2px);
        }

        .task-point-block {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 2rem;
            margin-bottom: 2rem;
            background-color: #fff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .task-point-block:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .task-point-block h5 {
            color: var(--primary-color);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            font-size: 1.25rem;
            position: relative;
            cursor: pointer; /* Indicate it's clickable */
        }
         .task-point-block h5 .toggle-icon {
            float: right;
            transition: transform 0.3s ease;
         }
         .task-point-block h5.collapsed .toggle-icon {
            transform: rotate(-90deg);
         }


        .task-point-block h5::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }
        .task-point-block h5:hover::after {
            width: 80px;
        }


        .task-point-content {
            transition: max-height 0.5s ease-out, opacity 0.3s ease-in;
            overflow: hidden;
            max-height: 1000px; /* Set a large max-height for open state */
            opacity: 1;
        }

        .task-point-content.collapsed {
            max-height: 0;
            opacity: 0;
            padding-top: 0;
            padding-bottom: 0;
             margin-top: 0;
             margin-bottom: 0;
             border: none;
        }


        .notes {
            color: var(--dark-text);
            margin: 1.5rem 0;
            padding: 1.25rem 1.5rem;
            border-left: 4px solid var(--info-color);
            background-color: #f0f9ff;
            border-radius: 0.5rem;
            font-size: 1rem;
            line-height: 1.6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .point-list {
            list-style-type: none;
            padding-left: 0;
            margin-top: 1.5rem;
        }

        .point-list li {
            margin-bottom: 1rem;
            line-height: 1.7;
            padding-left: 2rem;
            position: relative;
            font-size: 1rem;
            color: var(--dark-text);
        }

        .point-list li::before {
            content: "\f00c"; /* Font Awesome check icon */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 4px;
            color: var(--success-color);
            font-size: 1.1rem;
        }

        .task-point-block img {
            display: block;
            max-width: 85%;
            height: auto;
            margin: 1.5rem auto;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .task-point-block img:hover {
            transform: scale(1.02);
        }

        hr.task-divider {
            margin: 2.5rem 0;
            border: none;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--border-color), transparent);
        }

        .text-muted {
            color: var(--gray-text) !important;
        }

        .lead {
            font-size: 1.15rem;
            line-height: 1.7;
            color: var(--gray-text);
        }

        h1 {
            color: var(--dark-text);
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 2.25rem;
        }

        h3 {
            color: var(--dark-text);
            font-weight: 600;
            margin-bottom: 2rem;
            font-size: 1.75rem;
        }

        /* Enhanced Code Block Styles */
        .code-block-wrapper {
            position: relative;
            margin: 1.5rem 0;
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid #2d3748;
            background-color: #1a202c;
        }

        .code-language-label {
            position: absolute;
            top: 0;
            left: 0;
            background-color: var(--primary-color);
            color: white;
            padding: 0.3rem 1rem;
            font-size: 0.85em;
            font-weight: 600;
            border-bottom-right-radius: 0.5rem;
            z-index: 2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .copy-code-button {
            position: absolute;
            top: 0.5rem;
            right: 0.75rem;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.85em;
            cursor: pointer;
            transition: all 0.2s ease;
            opacity: 0.8;
            z-index: 2;
            padding: 0.4rem 0.8rem;
            backdrop-filter: blur(4px);
        }

        .copy-code-button:hover {
            opacity: 1;
            background-color: rgba(255, 255, 255, 0.2);
        }
         .copy-code-button.copied {
            background-color: rgba(16, 185, 129, 0.3); /* Success color hint */
         }

        pre.code-block {
            background-color: #1a202c;
            color: #e2e8f0;
            padding: 2rem;
            padding-top: 3rem; /* Space for label/button */
            margin: 0;
            border-radius: 0;
            overflow-x: auto;
            font-family: 'Fira Code', 'Consolas', monospace;
            font-size: 0.95em;
            line-height: 1.6;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) #2d3748;
        }

        pre.code-block::-webkit-scrollbar {
            height: 8px;
        }

        pre.code-block::-webkit-scrollbar-track {
            background: #2d3748;
            border-radius: 4px;
        }

        pre.code-block::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 4px;
        }

        /* Button Styles */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none; /* Remove underline from links styled as buttons */
            display: inline-block; /* Ensure proper alignment */
            text-align: center;
            vertical-align: middle;
            user-select: none;
             border: 1px solid transparent;
        }
         .btn:disabled {
             cursor: not-allowed;
             opacity: 0.65;
         }

        .btn-lg {
             padding: 1rem 2rem;
             font-size: 1.25rem;
             border-radius: 0.6rem;
        }


        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }

        .btn-success:hover:not(:disabled) {
            background-color: #0d9f6e;
            border-color: #0d9f6e;
            transform: translateY(-1px);
            color: #fff; /* Ensure text stays white */
        }

        .btn-primary {
             background-color: var(--primary-color);
             border-color: var(--primary-color);
             color: #fff;
        }
        .btn-primary:hover:not(:disabled) {
             background-color: #4338ca; /* Darker primary */
             border-color: #4338ca;
             color: #fff;
        }


        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--gray-text);
            background-color: #fff;
        }

        .btn-outline-secondary:hover {
            background-color: var(--light-bg);
            border-color: var(--gray-text);
            color: var(--dark-text);
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background-color: transparent;
        }
         .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
         }


        /* Alert Styles */
        .alert {
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid transparent; /* Base border */
        }

        .alert-danger {
            background-color: #fef2f2;
            color: var(--danger-color);
            border-color: #fecaca; /* Lighter border */
        }

        .alert-success {
             background-color: #f0fdf4;
             color: var(--success-color);
             border-color: #a7f3d0;
        }
        .alert-warning {
            background-color: #fffbeb;
            color: #b45309; /* Darker warning text */
            border-color: #fde68a;
        }

        .alert-info {
             background-color: #eff6ff;
             color: var(--info-color);
             border-color: #bfdbfe;
        }


        .alert-secondary {
            background-color: #f8fafc;
            color: var(--gray-text);
            border-color: var(--border-color);
        }

        .alert-heading {
             font-weight: 600;
             color: inherit; /* Inherit color from parent alert */
        }


        /* Button Group Styles */
        .button-group {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem 2rem;
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 0.75rem; /* Rounded corners */
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .control-button {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-text);
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .control-button:hover {
            color: var(--primary-color);
            border-color: var(--primary-color);
            background-color: var(--light-bg);
        }

        .control-button i {
            margin-right: 0.5rem;
        }

        /* Modal Styles */
        .modal-dialog {
            max-width: 600px;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .modal-header {
             border-bottom: 1px solid var(--border-color);
             padding: 1rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--dark-text);
        }

        .modal-body {
             padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }


        /* Form Styles within Modal */
         .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--dark-text);
         }

        .form-select, .form-control {
            border-radius: 6px;
            padding: 0.6rem 0.75rem;
            font-size: 0.95rem;
            border: 1px solid var(--border-color);
            width: 100%;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-select:focus, .form-control:focus {
            border-color: var(--primary-color);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }

        .form-control[disabled], .form-select[disabled] {
            background-color: #e9ecef;
            opacity: 1;
        }

        textarea.form-control {
            min-height: 80px; /* Better default size */
        }


        /* Custom File Upload Button */
        .btn-upload {
            border-radius: 6px;
            padding: 8px 16px;
            width: 100%; /* Make it full width */
            text-align: center;
            margin-bottom: 0.5rem; /* Space before file list/text */
        }
        .btn-upload i {
            margin-right: 0.5rem;
        }
        #fileUpload {
            display: none; /* Hide the actual input */
        }
        #fileList {
            margin-top: 0.75rem;
            font-size: 0.9em;
            color: var(--gray-text);
        }
        #fileList div {
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        .supported-formats {
            font-size: 0.85em; /* Slightly smaller */
            color: var(--gray-text);
            text-align: center;
            margin-top: 0.5rem;
        }

        /* Submission Status Styles */
         .submission-status .alert {
             margin-bottom: 1.5rem; /* More space */
         }
         .submission-status .grade-info {
             font-size: 1.1em;
             margin-bottom: 0.75rem;
         }
         .submission-status .feedback-section {
             margin-top: 1rem;
             padding-top: 1rem;
             border-top: 1px solid var(--border-color);
         }
        .submission-status .feedback-section p {
            background-color: #f8fafc;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

         .submitted-files h6 {
             margin-bottom: 0.75rem;
             font-weight: 600;
             color: var(--dark-text);
         }
         .list-group-item {
             padding: 0.75rem 1.25rem;
             border-color: var(--border-color);
         }
         .list-group-item:hover {
             background-color: #f8f9fa;
             color: var(--primary-color);
         }
         .list-group-item i {
             color: var(--info-color); /* Icon color */
         }
         .list-group-item .badge {
             font-size: 0.8em;
         }


        /* Task Navigation */
        .task-navigation {
            background-color: #ffffff;
            padding: 1rem 1.5rem; /* Consistent padding */
            border-radius: 0.75rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        .task-navigation .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .task-navigation .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .task-navigation .btn-outline-primary {
            border-width: 1px; /* Standard border width */
        }

        .task-navigation .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .task-navigation .btn-secondary {
            background-color: #f8f9fa;
            color: var(--dark-text);
            border: 1px solid var(--border-color);
        }

        .task-navigation .btn-secondary:hover {
            background-color: #e9ecef;
            color: var(--dark-text);
        }

        /* Spinner for Submit Button */
         #submitTask .fa-spinner {
             margin-right: 0.5rem;
         }

    </style>

    <body>
        <div class="container mt-4"> {{-- Add top margin to container --}}

            @if ($task) {{-- Check if task exists FIRST --}}

                <div class="button-group">
                    <button class="control-button" id="openAllBtn">
                        <i class="fas fa-chevron-down"></i> Open All Points
                    </button>
                    <button class="control-button" id="collapseAllBtn">
                        <i class="fas fa-chevron-up"></i> Collapse All Points
                    </button>
                </div>

                <div class="task-container">

                    {{-- Back to Course Link (if applicable) --}}
                    @if ($task->chapter && $task->chapter->course)
                        <div class="mb-4">
                            <a href="{{ route('course.content', ['id' => $task->chapter->course->id]) }}"
                                class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Course Content
                            </a>
                        </div>
                    @endif

                    <h1>{{ $task->title }}</h1>
                    @if (!empty($task->description))
                        <p class="lead mb-4">{{ $task->description }}</p> {{-- Add bottom margin --}}
                    @endif
                    @if (!empty($task->videos_required_watched))
                        <p class="text-muted mb-4">
                            <i class="fas fa-video me-2 text-primary"></i>
                            <strong>Recommended Videos:</strong> {{ $task->videos_required_watched }}
                        </p>
                    @endif

                    <hr class="task-divider">

                    @if ($task->taskPoints && $task->taskPoints->count() > 0)
                        <h3>Assignments</h3>
                        @forelse ($task->taskPoints->sortBy('created_at') as $index => $pointBlock)
                            <div class="task-point-block">
                                {{-- Title with Toggle Icon --}}
                                <h5 class="task-point-title">
                                    {{ $index + 1 }}. {{ $pointBlock->title }}
                                    <span class="toggle-icon fas fa-chevron-down"></span>
                                </h5>

                                {{-- Collapsible Content Wrapper --}}
                                <div class="task-point-content">
                                    @if (!empty($pointBlock->notes))
                                        <div class="notes">
                                            <i class="fas fa-info-circle me-2"></i>
                                            {!! nl2br(e($pointBlock->notes)) !!} {{-- nl2br AFTER e() for safety --}}
                                        </div>
                                    @endif

                                    {{-- Filter out empty points more robustly --}}
                                    @php
                                        $validPoints = is_array($pointBlock->points) ? array_filter($pointBlock->points, fn($p) => trim($p) !== '') : [];
                                    @endphp
                                    @if (!empty($validPoints))
                                        <h6 class="mt-4 mb-3" style="font-weight:600; color: var(--primary-color);">
                                            <i class="fas fa-check-double me-2"></i>Points
                                        </h6>
                                        <ul class="point-list">
                                            @foreach ($validPoints as $pointDescription)
                                                <li>{{ e($pointDescription) }}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if (!empty($pointBlock->code_block))
                                        <div class="code-block-wrapper">
                                            {{-- Dynamic language label could be added if stored --}}
                                            <span class="code-language-label">CODE</span>
                                            <button class="copy-code-button" type="button" title="Copy Code">
                                                <i class="far fa-copy"></i> <span class="copy-text">Copy</span>
                                            </button>
                                            {{-- Ensure proper escaping for code within pre/code --}}
                                            <pre class="code-block language-html"><code>{{ $pointBlock->code_block }}</code></pre>
                                        </div>
                                    @endif

                                    @if ($pointBlock->image_url)
                                        <div class="text-center mt-4"> {{-- Add margin --}}
                                            {{-- Consider using asset() if image_url is relative to public path --}}
                                            <img src="{{ filter_var($pointBlock->image_url, FILTER_VALIDATE_URL) ? $pointBlock->image_url : asset($pointBlock->image_url) }}"
                                                 alt="Image for {{ e($pointBlock->title) }}">
                                        </div>
                                    @endif
                                </div> {{-- End task-point-content --}}
                            </div> {{-- End task-point-block --}}
                        @empty
                            <div class="alert alert-secondary">No assignment points found for this task.</div>
                        @endforelse
                    @else
                        <div class="alert alert-secondary text-center">
                            No assignments or points have been added for this task yet.
                        </div>
                    @endif

                    <hr class="task-divider">

                    <div class="text-center mt-4 mb-4"> {{-- Add bottom margin --}}
                        {{-- Check if already submitted before showing the button --}}
                        @php
                            $existingSubmission = \App\Models\TaskSubmission::where('user_id', Auth::id())
                                ->where('task_id', $task->id)
                                ->first();
                        @endphp
                        @if(!$existingSubmission)
                             <button class="btn btn-success btn-lg px-5" data-bs-toggle="modal" data-bs-target="#taskDetailModal">
                                <i class="fas fa-paper-plane me-2"></i> Submit Task
                            </button>
                        @else
                            {{-- Optionally show a button to view submission or just indicate it's submitted --}}
                             <button class="btn btn-info btn-lg px-5" data-bs-toggle="modal" data-bs-target="#taskDetailModal">
                                <i class="fas fa-eye me-2"></i> View Submission Status
                            </button>
                         @endif

                    </div>
                </div> {{-- End task-container --}}


                {{-- Task Navigation (Previous/Next) --}}
                <div class="task-navigation container mt-4 mb-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="col-auto">
                            @if($previousTask)
                                <a href="{{ route('task.show', $previousTask->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Previous Task
                                </a>
                            @else
                                <span class="btn btn-outline-primary disabled" aria-disabled="true"><i class="fas fa-arrow-left me-2"></i> Previous Task</span>
                            @endif
                        </div>

                        @if($task->chapter && $task->chapter->course)
                            <div class="col-auto">
                                <a href="{{ route('course.content', ['id' => $task->chapter->course->id]) }}" class="btn btn-secondary">
                                    <i class="fas fa-list me-2"></i>
                                    Back to Course
                                </a>
                            </div>
                        @endif

                        <div class="col-auto">
                            @if($nextTask)
                                <a href="{{ route('task.show', $nextTask->id) }}" class="btn btn-primary">
                                    Next Task
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            @else
                                 <span class="btn btn-primary disabled" aria-disabled="true">Next Task <i class="fas fa-arrow-right ms-2"></i></span>
                            @endif
                        </div>
                    </div>
                </div>

            @else {{-- Task Not Found State --}}
                <div class="task-container"> {{-- Use consistent container --}}
                    <div class="alert alert-danger text-center mt-5"> {{-- Add margin top --}}
                        <h4 class="alert-heading mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>Task Not Found
                        </h4>
                        <p class="mb-3">{{ $errorMessage ?? 'The requested task could not be loaded or does not exist.' }}</p>
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> Go Home
                        </a>
                    </div>
                </div>
            @endif {{-- End check for $task --}}

        </div> {{-- End .container --}}


        {{-- Task Submission Modal --}}
        {{-- Only include modal if task exists --}}
        @if ($task)
            <div id="taskDetailModal" class="modal fade" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"> {{-- Consider larger modal if needed --}}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="taskDetailModalLabel">Task Submission: {{ $task->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Reuse the existing submission check --}}
                            @if($existingSubmission)
                                <div class="submission-status mb-4">
                                     <div class="alert {{ $existingSubmission->status == 'reviewed' ? ($existingSubmission->grade >= 50 ? 'alert-success' : 'alert-danger') : 'alert-warning' }}" role="alert">
                                        <h6 class="alert-heading mb-2">
                                            @if($existingSubmission->status == 'reviewed')
                                                <i class="fas fa-check-circle me-2"></i> Task Reviewed
                                            @else
                                                <i class="fas fa-clock me-2"></i> Pending Review
                                            @endif
                                        </h6>

                                        @if($existingSubmission->status == 'reviewed')
                                            <div class="grade-info mb-2">
                                                <strong>Grade:</strong>
                                                <span class="badge bg-{{ $existingSubmission->grade >= 70 ? 'success' : ($existingSubmission->grade >= 50 ? 'warning' : 'danger') }}">
                                                    {{ $existingSubmission->grade ?? 'N/A' }}%
                                                </span>
                                            </div>
                                            @if($existingSubmission->feedback)
                                                <div class="feedback-section">
                                                    <strong>Instructor Feedback:</strong>
                                                    <p class="mb-0 mt-1">{{ $existingSubmission->feedback }}</p>
                                                </div>
                                            @else
                                                <p class="text-muted small">No feedback provided.</p>
                                            @endif
                                        @else
                                            <p class="mb-0">Your submission is awaiting review by the instructor.</p>
                                        @endif
                                    </div>

                                    {{-- Show submitted files (using asset helper) --}}
                                    {{-- Eager load files in controller if performance is an issue --}}
                                    @if($existingSubmission->files()->count() > 0)
                                        <div class="submitted-files mt-3">
                                            <h6>Submitted Files:</h6>
                                            <div class="list-group">
                                                @foreach($existingSubmission->files as $file)
                                                    <a href="{{ route('task.submission.file.download', ['submission' => $existingSubmission->id, 'file' => $file->id]) }}"
                                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-download me-2"></i>
                                                            {{-- Display original file name --}}
                                                            {{ $file->file_name ?? basename($file->file_path) }}
                                                        </div>
                                                        <span class="badge bg-primary rounded-pill">
                                                            {{-- Format file size --}}
                                                             @if($file->file_size > 0)
                                                               {{ number_format($file->file_size / 1024, 1) }} KB
                                                             @elseif($file->file_size === 0)
                                                                0 KB
                                                             @else
                                                                N/A
                                                             @endif
                                                        </span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                         <p class="text-muted">No files were submitted.</p>
                                    @endif
                                </div>
                            @else
                                {{-- Submission Form --}}
                                {{-- Use POST method, action will be handled by JS --}}
                                <form id="taskSubmissionForm" method="POST" action="{{ route('task.submit', ['task' => $task->id]) }}" enctype="multipart/form-data">
                                    @csrf
                                    {{-- Course Display (if needed, though context is clear) --}}
                                     @if ($task->chapter && $task->chapter->course)
                                        <div class="mb-3">
                                            <label for="courseDisplay" class="form-label">Course</label>
                                            <input type="text" id="courseDisplay" class="form-control" value="{{ $task->chapter->course->title }}" disabled readonly>
                                        </div>
                                     @endif

                                    {{-- Notes Section --}}
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes (Optional)</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="4"
                                                placeholder="Add any relevant notes for your instructor..."></textarea>
                                    </div>

                                    <hr>

                                    {{-- Upload Section --}}
                                    <div class="upload-section mb-3">
                                        <label for="fileUploadButton" class="form-label">Upload Your Files</label>
                                        {{-- This button triggers the hidden file input --}}
                                        <button type="button" id="fileUploadButton" class="btn btn-outline-primary btn-upload w-100">
                                            <i class="fas fa-cloud-upload-alt"></i> Choose Files...
                                        </button>
                                        {{-- Hidden actual file input --}}
                                        <input type="file" id="fileUpload" name="files[]" class="d-none" multiple
                                               accept=".jpeg,.jpg,.png,.gif,.mp4,.pdf,.psd,.ai,.doc,.docx,.ppt,.pptx,.zip,.rar,.txt,.html,.css,.js,.php,.sql"> {{-- Add accept attribute --}}

                                        {{-- Area to display selected file names --}}
                                        <div id="fileList" class="mt-2 small text-muted">No files selected.</div>

                                        <div class="supported-formats mt-2 small text-muted">
                                            Max file size: 20MB. Common formats accepted.
                                        </div>
                                        {{-- Display validation errors for files --}}
                                        @error('files.*')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                         @error('files') {{-- General file error --}}
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success w-100" id="submitTask">
                                            <i class="fas fa-paper-plane me-2"></i> Submit Task
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>{{-- End modal-body --}}
                         <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif {{-- End check for $task for modal --}}


        {{-- Include Bootstrap JS if not already in layouts.head --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // --- Code Copy Functionality ---
                const copyButtons = document.querySelectorAll('.copy-code-button');
                copyButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const wrapper = this.closest('.code-block-wrapper');
                        const codeElement = wrapper ? wrapper.querySelector('pre.code-block code') : null;
                        const buttonText = this.querySelector('.copy-text');

                        if (codeElement && buttonText) {
                            const codeToCopy = codeElement.textContent || codeElement.innerText;

                            navigator.clipboard.writeText(codeToCopy).then(() => {
                                const originalText = 'Copy';
                                const successText = 'Copied!';
                                buttonText.textContent = successText;
                                this.classList.add('copied');
                                this.querySelector('i').classList.remove('fa-copy');
                                this.querySelector('i').classList.add('fa-check');


                                setTimeout(() => {
                                    buttonText.textContent = originalText;
                                    this.classList.remove('copied');
                                    this.querySelector('i').classList.remove('fa-check');
                                    this.querySelector('i').classList.add('fa-copy');
                                }, 2500);
                            }).catch(err => {
                                console.error('Failed to copy code: ', err);
                                // Use SweetAlert for error feedback
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Copy Failed',
                                    text: 'Could not copy code to clipboard. Please try manually.',
                                });
                            });
                        }
                    });
                });

                // --- Task Point Collapse/Expand Functionality ---
                const taskPointBlocks = document.querySelectorAll('.task-point-block');
                const openAllBtn = document.getElementById('openAllBtn');
                const collapseAllBtn = document.getElementById('collapseAllBtn');

                const togglePoint = (pointBlock, forceOpen = null) => {
                    const title = pointBlock.querySelector('.task-point-title');
                    const content = pointBlock.querySelector('.task-point-content');
                    const icon = title.querySelector('.toggle-icon');

                    if (content && title && icon) {
                        const isOpen = !content.classList.contains('collapsed');
                        const shouldOpen = forceOpen === null ? !isOpen : forceOpen;

                        if (shouldOpen) {
                            content.classList.remove('collapsed');
                            title.classList.remove('collapsed'); // For icon rotation etc.
                            icon.classList.remove('fa-chevron-right'); // If you use right arrow for collapsed
                            icon.classList.add('fa-chevron-down');
                             content.style.maxHeight = content.scrollHeight + "px"; // Expand fully
                        } else {
                            content.style.maxHeight = content.scrollHeight + "px"; // Get current height
                            requestAnimationFrame(() => { // Allow setting height before collapsing
                                content.classList.add('collapsed');
                                title.classList.add('collapsed');
                                icon.classList.remove('fa-chevron-down');
                                icon.classList.add('fa-chevron-right'); // Optional: change icon
                                content.style.maxHeight = '0px'; // Animate collapse
                            });
                        }
                    }
                };

                taskPointBlocks.forEach(pointBlock => {
                    const title = pointBlock.querySelector('.task-point-title');
                    if (title) {
                        title.addEventListener('click', () => togglePoint(pointBlock));
                    }
                    // Initially collapse all by default (optional)
                    // togglePoint(pointBlock, false);
                });

                if (openAllBtn) {
                    openAllBtn.addEventListener('click', () => {
                        taskPointBlocks.forEach(point => togglePoint(point, true));
                    });
                }

                if (collapseAllBtn) {
                    collapseAllBtn.addEventListener('click', () => {
                        taskPointBlocks.forEach(point => togglePoint(point, false));
                    });
                }

                // --- File Input Handling ---
                const fileInput = document.getElementById('fileUpload');
                const fileUploadButton = document.getElementById('fileUploadButton');
                const fileListDisplay = document.getElementById('fileList');

                if (fileUploadButton && fileInput) {
                    fileUploadButton.addEventListener('click', () => {
                        fileInput.click(); // Trigger the hidden file input
                    });
                }

                if (fileInput && fileListDisplay) {
                    fileInput.addEventListener('change', function() {
                        fileListDisplay.innerHTML = ''; // Clear previous list
                        if (this.files.length > 0) {
                             fileListDisplay.classList.remove('text-muted'); // Make text normal
                            Array.from(this.files).forEach(file => {
                                const fileElement = document.createElement('div');
                                fileElement.textContent = file.name + ` (${(file.size / 1024).toFixed(1)} KB)`;
                                fileListDisplay.appendChild(fileElement);
                            });
                            // Update the trigger button text
                            fileUploadButton.innerHTML = `<i class="fas fa-check me-2"></i> ${this.files.length} file(s) selected`;
                            fileUploadButton.classList.remove('btn-outline-primary');
                            fileUploadButton.classList.add('btn-outline-success'); // Use success outline
                        } else {
                            fileListDisplay.innerHTML = 'No files selected.';
                            fileListDisplay.classList.add('text-muted');
                            // Reset trigger button
                            fileUploadButton.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Choose Files...';
                             fileUploadButton.classList.remove('btn-outline-success');
                            fileUploadButton.classList.add('btn-outline-primary');
                        }
                    });
                }


                // --- Form Submission via Fetch API ---
                const form = document.getElementById('taskSubmissionForm');
                const submitButton = document.getElementById('submitTask');
                const modalElement = document.getElementById('taskDetailModal');
                const modalInstance = modalElement ? new bootstrap.Modal(modalElement) : null; // Get modal instance


                if (form && submitButton) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault(); // Prevent default HTML form submission
                        console.log('Form submission initiated...');

                        const formData = new FormData(this);
                        const originalButtonText = submitButton.innerHTML;

                        // Show loading state on button
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';

                        fetch(this.action, { // Use form's action URL
                            method: 'POST',
                            body: formData,
                            headers: {
                                // CSRF token is usually sent via the hidden input field named _token
                                // 'X-CSRF-TOKEN': '{{ csrf_token() }}', // Not needed if using @csrf in form
                                'Accept': 'application/json' // Expect JSON response
                            },
                            //credentials: 'same-origin' // Usually default and sufficient
                        })
                        .then(response => {
                            // Check if response is ok (status in the range 200-299)
                             if (!response.ok) {
                                // If not OK, try to parse JSON error body, otherwise throw generic error
                                return response.json().then(errData => {
                                     throw { status: response.status, data: errData }; // Throw object with status and data
                                });
                             }
                             return response.json(); // Parse JSON body for successful responses
                        })
                        .then(data => {
                            console.log('Submission response:', data);
                            if (data.success) {
                                if (modalInstance) modalInstance.hide(); // Hide modal on success
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload(); // Reload page to show submission status
                                });
                            } else {
                                // Handle specific known 'already submitted' case from backend
                                if (data.submission) {
                                     if (modalInstance) modalInstance.hide();
                                     Swal.fire({
                                        title: 'Already Submitted',
                                        text: data.message || 'You have previously submitted this task.',
                                        icon: 'info',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                     // General failure message from backend
                                     throw { data: data }; // Re-throw to be caught by catch block
                                }
                            }
                        })
                        .catch(error => {
                             console.error('Submission Fetch Error:', error);
                             let title = 'Submission Error!';
                             let message = 'An unexpected error occurred. Please try again.';
                             let errorList = '';

                             // Check if it's our custom error object with status and data
                            if (error && error.data) {
                                message = error.data.message || message; // Use backend message if available
                                // Handle validation errors (status 422)
                                if (error.status === 422 && error.data.errors) {
                                    title = 'Validation Failed';
                                    errorList = '<ul style="text-align: left; list-style-position: inside;">';
                                    for (const field in error.data.errors) {
                                        error.data.errors[field].forEach(errMsg => {
                                            errorList += `<li>${errMsg}</li>`;
                                        });
                                    }
                                    errorList += '</ul>';
                                     message = 'Please correct the following issues:'; // Override general message
                                }
                            } else if (error instanceof Error) {
                                // Network error or other JS error
                                message = error.message || message;
                            }


                             Swal.fire({
                                title: title,
                                html: message + errorList, // Combine message and potential error list
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        })
                        .finally(() => {
                            // Always reset button state regardless of success/failure
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonText;
                        });
                    });
                }

            }); // End DOMContentLoaded
        </script>
    </body>
@endsection