<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        // Check if the user is an admin
        if ($request->user()->is_admin) {
            // Return the admin profile edit view if the user is an admin
            return view('admin.edit', [
                'user' => $request->user(),
            ]);
        }
        
        // Otherwise, return the regular profile edit view
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile settings.
     */
    public function setting(Request $request)
    {
        $request->validate([
            'email'      => ['required', 'email'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'new_password' => ['nullable', 'min:6'],
        ]);
        
        // Get the currently authenticated user
        $user = \App\Models\User::find(\Illuminate\Support\Facades\Auth::id());
    
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
    
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }
    
        $user->save();
    
        // Redirect back with a success message
        return redirect()->back()->with('status', 'Profile updated successfully!');
    }
    
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logout();
        $user = $request->user();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}