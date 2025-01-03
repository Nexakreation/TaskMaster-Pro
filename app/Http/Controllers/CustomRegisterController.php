<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PDO;

class CustomRegisterController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Debug log
            error_log("Starting registration process for email: " . $request->email);

            // Validate input
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            try {
                // Create user using Laravel's User model
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                error_log("User created with ID: " . $user->id);

                // Log the user in
                Auth::login($user);
                error_log("User logged in successfully");

                return redirect()->route('dashboard')->with('success', 'Registration successful');

            } catch (\Exception $e) {
                error_log("Database operation failed: " . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            error_log("Registration failed: " . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
} 