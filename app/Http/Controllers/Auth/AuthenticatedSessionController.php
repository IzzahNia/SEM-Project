<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validate the role
        $request->validate([
            'role' => 'required|in:admin,user', // Ensure role is either admin or user
        ]);
    
        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
    
            // Check if the role matches
            if ($user->role !== $request->role) {
                Auth::logout(); // Log out if the role does not match
                return back()->withErrors(['role' => 'The selected role does not match your account.']);
            }
    
            // Update last_active_at timestamp
            $user->update(['last_active_at' => now()]);
    
            $request->session()->regenerate();
    
            // Redirect based on role
            return redirect()->intended(route('dashboard', absolute: false));
        }
    
        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
