<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showSignupForm()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        try {
            // Stronger backend validation
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
                'username' => [
                    'required',
                    'string',
                    'min:3',
                    'max:20',
                    'unique:users,username',
                    'regex:/^[a-zA-Z0-9_]+$/'
                ],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);

            // Create user safely
            $user = User::create([
                'name' => e($validated['name']), // escape HTML injection
                'username' => e($validated['username']),
                'email' => '', // optional if not using email
                'password' => Hash::make($validated['password']),
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            // Log::info('New user signed up', [
            //     'username' => $validated['username'],
            //     'ip' => $request->ip(),
            // ]);

            return redirect()->route('profile.view');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Will auto redirect back with errors, but log for monitoring
            // Log::warning('Signup validation failed', ['errors' => $e->errors()]);
            throw $e;

        } catch (\Exception $e) {
            // Log::error('Signup failed', ['message' => $e->getMessage()]);
            return back()->withErrors([
                'general' => 'Something went wrong. Please try again later.'
            ]);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            // Stronger validation rules
            $credentials = $request->validate([
                'username' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z0-9_]+$/'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            // Attempt login
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                // Log::info('User logged in', ['username' => $credentials['username'], 'ip' => $request->ip()]);
                return redirect()->route('profile.view');
            }

            // Invalid credentials
            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ])->onlyInput('username');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors (Laravel will handle, but we can log it)
            // Log::warning('Validation failed on login', ['errors' => $e->errors()]);
            throw $e;

        } catch (\Exception $e) {
            // Unexpected error
            // Log::error('Login error', ['message' => $e->getMessage()]);
            return back()->withErrors([
                'general' => 'Something went wrong. Please try again later.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
