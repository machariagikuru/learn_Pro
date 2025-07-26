<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstructorProfileController extends Controller

{
    /**
     * Display the instructor's profile edit form.
     */
    public function edit(Request $request)
    {
        // Return the instructor edit view from resources/views/instructor/edit.blade.php
        return view('instructor.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the instructor's profile settings.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'new_password'=> 'nullable|min:6',
        ]);

        $user->email = $validatedData['email'];
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->phone = $validatedData['phone'] ?? $user->phone;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($validatedData['new_password']);
        }

        $user->save();

        return redirect()->route('instructor.profile.edit')->with('status', 'Profile updated successfully!');
    }
}
