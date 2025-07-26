<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\InstructorRequest;
use App\Models\Course;



use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', 2)->get();
        return view('admin.index', compact('users'));

        return view('admin.index');
    }
    public function users()
    {
       
        $pendingRequests = \App\Models\InstructorRequest::with('user')
                               ->where('status', 'pending')
                               ->get();
        $users = \App\Models\User::where('id', '!=', 2)->get();
    
        return view('admin.users', compact('users', 'pendingRequests'));
    }

    public function updateUserType(Request $request)
    {
    $user = User::find($request->user_id);
    if ($user) {
        $user->usertype = $request->usertype;
        $user->save();
        return response()->json(['success' => 'User type updated successfully!']);
    } else {
        return response()->json(['error' => 'User not found!'], 404);
    }
     return view('admin.index', compact('users'));
    }
public function pendingRequests()
{
    $pendingRequests = \App\Models\InstructorRequest::with('user')
        ->where('status', 'pending')
        ->get();

    return view('admin.pending_requests', compact('pendingRequests'));
}

public function processInstructorRequest(Request $request)
{
    $request->validate([
        'request_id' => 'required|exists:instructor_requests,id',
        'action'     => 'required|in:approve,decline',
    ]);

    $instructorRequest = \App\Models\InstructorRequest::find($request->request_id);

    if ($request->action == 'approve') {
        $instructorRequest->update(['status' => 'approved']);
        $user = $instructorRequest->user;
        if ($user) {
            $user->usertype = 'instructor';
            $user->save();
            return response()->json(['message' => 'Request approved and user updated successfully!']);
        }
        return response()->json(['error' => 'User not found!'], 404);
    } else {
        $instructorRequest->update(['status' => 'declined']);
        return response()->json(['message' => 'Request declined successfully.']);
    }
}

public function deleteUser(Request $request)
{
    $user = User::find($request->user_id);
    if ($user) {
        $user->delete();
        return response()->json(['success' => 'User deleted successfully']);
    } else {
        return response()->json(['error' => 'User not found'], 404);
    }
}

public function pendingCourses()
{
    $pendingCourses = Course::where('status', 'pending')->get();
    return view('admin.pending_courses', compact('pendingCourses'));
}


public function showCourse($id)
{
    $course = Course::findOrFail($id);
    return view('admin.incourse', compact('course'));
}

public function approveCourse($id)
{
    $course = Course::findOrFail($id);
    $course->status = 'approved';
    $course->save();

    // إرسال الإشعار للمُدرّس عند الموافقة
    $course->user->notify(new \App\Notifications\CourseStatusUpdated($course));

    return back()->with('success', 'Course approved successfully.');
}

public function rejectCourse($id)
{
    $course = Course::findOrFail($id);
    $course->status = 'rejected';
    $course->save();

    // إرسال الإشعار للمُدرّس عند الرفض
    $course->user->notify(new \App\Notifications\CourseStatusUpdated($course));

    return back()->with('success', 'Course rejected successfully.');
}

public function notifications()
{
    return view('admin.notifications');
}

public function markNotificationAsRead($id)
{
    $notification = auth()->user()->notifications()->find($id);
    if ($notification) {
        $notification->markAsRead();
    }
    return redirect()->back()->with('success', 'Notification marked as read.');
}

public function markNotificationAsUnread($id)
{
    $notification = auth()->user()->notifications()->find($id);
    if ($notification) {
        $notification->update(['read_at' => null]);
    }
    return redirect()->back()->with('success', 'Notification marked as unread.');
}

public function markAllRead()
{
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back()->with('success', 'All notifications marked as read.');
}




}






