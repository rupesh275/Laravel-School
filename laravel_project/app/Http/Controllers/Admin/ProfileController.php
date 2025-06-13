<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // For more complex password rules if needed

class ProfileController extends Controller
{
    public function __construct()
    {
        // Middleware applied via routes
    }

    /**
     * Show the form for editing the admin's profile (including password change).
     */
    public function edit()
    {
        $user = Auth::user();
        // return view('admin.profile.edit', compact('user'));
        return response("Admin Profile Edit Form (View: admin.profile.edit) - User: " . $user->email);
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request)
    {
        // In a real application, use a Form Request: php artisan make:request UpdateAdminPasswordRequest
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
            // 'password_confirmation' field will be automatically checked by 'confirmed'
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // return redirect()->route('admin.profile.edit')->with('status', 'password-updated');
        return response("Admin password updated successfully. Redirect to admin.profile.edit with status.");
    }

    // Other profile update methods (e.g., for name, email) could go here.
}
