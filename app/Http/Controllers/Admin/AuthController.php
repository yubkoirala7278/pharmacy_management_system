<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'Welcome back!');
            }

            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->onlyInput('email');
        } catch (Exception $e) {
            return back()->withErrors([
                'error' => 'Something went wrong while trying to log you in. Please try again later.',
            ])->onlyInput('email');
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        try {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'You have been logged out successfully.');
        } catch (Exception $e) {

            return redirect()->route('login')->withErrors([
                'error' => 'An error occurred while logging out. Please try again.',
            ]);
        }
    }
}
