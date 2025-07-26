@extends('instructor.layout_instructor')

@section('body')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">All Notifications</h1>
        <!-- زر لتعليم جميع الإشعارات كمقروء -->
        <form action="{{ route('instructor.notifications.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-double"></i> Mark All as Read
            </button>
        </form>
        
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse(auth()->user()->notifications as $notification)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $notification->data['title'] }}</h5>
                            @if(isset($notification->data['message']))
                                <p class="mb-1">{{ $notification->data['message'] }}</p>
                            @endif
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="text-end">
                            @if (!$notification->read_at)
                                <form action="{{ route('instructor.notifications.markRead', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark as Read</button>
                                </form>
                            @else
                                <span class="badge bg-secondary mb-1">Read</span>
                                <!-- زر اختياري لجعلها غير مقروءة -->
                                <form action="{{ route('instructor.notifications.markUnread', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">Mark as Unread</button>
                                </form>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">No notifications found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
