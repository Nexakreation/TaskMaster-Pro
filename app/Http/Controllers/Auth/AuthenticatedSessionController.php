<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        try {
            $host = 'localhost';
            $dbname = 'todo_app';
            $username = 'root';
            $password = '';

            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Get user from database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$request->email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if user exists
            if (!$user) {
                return back()->withErrors([
                    'email' => 'Email not found.',
                ])->onlyInput('email');
            }

            // Check password
            if (!Hash::check($request->password, $user['password'])) {
                return back()->withErrors([
                    'password' => 'Incorrect password.',
                ])->onlyInput('email');
            }

            // If we get here, both email and password are correct
            $request->session()->regenerate();
            Auth::loginUsingId($user['id']);

            return redirect()->intended(route('home'));

        } catch (\PDOException $e) {
            return back()->withErrors([
                'email' => 'Database connection failed.',
            ])->onlyInput('email');
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
