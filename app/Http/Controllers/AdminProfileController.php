<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'new_password' => 'nullable|min:6',
        ]);

        $user->email = $validatedData['email'];
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->phone = $validatedData['phone'] ?? $user->phone;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($validatedData['new_password']);
        }

        $user->save();

        return redirect()->route('admin.profile.edit')->with('status', 'Profile updated successfully!');
    }
}
