<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Stripe;
use App\Models\UserCourse;
use App\Models\User;
use App\Models\Courses;
use Session;
use App\Notifications\CoursePurchasedNotification;

class EnrollmentController extends Controller
{
    public function payment($id)
    {
        $course = Course::findOrFail($id);
        return view('enroll.payment', compact('course'));
    }

    public function stripe($id)
    {
        $course = Course::findOrFail($id);
        return view('enroll.stripe', compact('course'));
    }
    public function stripePost(Request $request, $id)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

      
        $course = Course::findOrFail($id);

       
        $user = Auth::user();

     
        if (UserCourse::where('user_id', $user->id)->where('course_id', $course->id)->exists()) {
            Session::flash('error', 'You have already purchased this course!');
            return redirect()->back();
        }

        try {
           
            Stripe\Charge::create([
                "amount" => $course->price * 100, 
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Payment for course: " . $course->title
            ]);

           
            UserCourse::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

           
            $user->notify(new CoursePurchasedNotification($course, $user));

           
            if ($course->user) {
                $course->user->notify(new \App\Notifications\CoursePurchasedInstructorNotification($course, $user));
            }

            Session::flash('success', 'Payment successful! You are now enrolled in the course.');
        } catch (\Exception $e) {
            Session::flash('error', 'Payment failed: ' . $e->getMessage());
        }

        return redirect()->route('course.content', ['id' => $id]);
    }
}
