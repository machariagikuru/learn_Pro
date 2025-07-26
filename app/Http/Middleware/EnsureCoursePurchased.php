<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCourse;

class EnsureCoursePurchased
{
    /**
     * Handle an incoming request.
     * نتأكد من أن المستخدم قد اشترى الكورس المطلوب.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // افترض أن الكورس ID يُمرر في الـ Route كـ {id}
        $courseId = $request->route('id');

        // التحقق من اشتراك المستخدم في الكورس
        $enrolled = UserCourse::where('user_id', $user->id)
                              ->where('course_id', $courseId)
                              ->exists();

        if (!$enrolled) {
            // إذا لم يشترِ المستخدم الكورس، نعيد توجيهه لصفحة الدفع أو صفحة أخرى
            return redirect()->route('payment', ['id' => $courseId])
                             ->with('error', 'You must purchase the course to access this page.');
        }

        return $next($request);
    }
}
