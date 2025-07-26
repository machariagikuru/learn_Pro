@extends('admin.layout_admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">All Notifications</h1>
        <!-- Form to mark all notifications as read -->
        <form action="{{ route('admin.notifications.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-double"></i> Mark All as Read
            </button>
        </form>
    </div>
    
    <ul class="list-group">
        @forelse(auth()->user()->notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>

                    
                    @if ($notification->read_at)
                        <span class="badge bg-secondary ms-2">Read</span>
                    @else
                        <span class="badge bg-primary ms-2">Unread</span>
                    @endif
                    @if (isset($notification->data['message']))
                        <p class="mb-0">{{ $notification->data['message'] }}</p>
                    @endif
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <div>
                    @if (!$notification->read_at)
                        <!-- Form to mark as read -->
                        <form action="{{ route('admin.notifications.markRead', $notification->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">Read</button>
                        </form>
                    @else
                        <!-- Form to mark as unread (optional) -->
                        <form action="{{ route('admin.notifications.markUnread', $notification->id) }}" method="POST" class="d-inline">
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
@endsection
