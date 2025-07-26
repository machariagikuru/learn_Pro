<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserRegisteredNotification;

class GoogleController extends Controller
{
    public function googlepage()
    {
        // Check if the configuration is loaded properly
        return Socialite::driver('google')->redirect();
    }

    public function googlecallback()
    {
        try {
            // Retrieve user information from Google
            $googleUser = Socialite::driver('google')->user();

            // Check if the user already exists in the database by their Google ID or email
            $finduser = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email) // Also check by email
                        ->first();

            if ($finduser) {
                // If the user exists, log them in
                Auth::login($finduser);
                return $this->redirectBasedOnRole($finduser);
            } else {
                // If the user does not exist, create a new user
                $newUser = User::create([
                    'first_name' => $googleUser->name,
                    'last_name'  => $googleUser->name,
                    'email'      => $googleUser->email,
                    'google_id'  => $googleUser->id,
                    'password'   => bcrypt('123456dummy'), // or use a placeholder password
                    // Optionally set default role, e.g., 'user'
                    'usertype'   => 'user'
                ]);

                // Send notification to admin about new user registration
                $admin = User::where('usertype', 'admin')->first();
                if ($admin) {
                    Notification::send($admin, new NewUserRegisteredNotification($newUser));
                }

                // Log the new user in
                Auth::login($newUser);
                return $this->redirectBasedOnRole($newUser);
            }
        } catch (Exception $e) {
            dd($e->getMessage()); // For debugging
        }
    }

    /**
     * Redirect user based on their role (usertype)
     */
    protected function redirectBasedOnRole($user)
    {
        if ($user->usertype == 'admin') {
            return redirect()->intended('admin/dashboard');
        } elseif ($user->usertype == 'instructor') {
            return redirect()->intended('instructor/dashboard');
        } else {
            return redirect()->intended('/dashboard');
        }
    }
}
