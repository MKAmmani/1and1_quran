<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Inertia\Inertia;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(Request $request)
    {
        // Get the role from the request and store it in the session.
        $role = $request->input('role');
        if ($role === 'teacher' || $role === 'student') {
            session(['google_signup_role' => $role]);
        }

        return Inertia::location(Socialite::driver('google')->redirect()->getTargetUrl());
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Use wasRecentlyCreated to check if the user is new
            $user = User::firstOrCreate(
                ['email' => $googleUser->email],
                [
                    'first_name' => $googleUser->user['given_name'],
                    'last_name' => $googleUser->user['family_name'],
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'google_token' => $googleUser->token,
                ]
            );

            // Update google_id and other details if the user existed but didn't have a google_id
             if (!$user->wasRecentlyCreated && is_null($user->google_id)) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'google_token' => $googleUser->token,
                ]);
            }

            // If the user was just created, assign the role from the session.
            if ($user->wasRecentlyCreated) {
                // Default to student if the session variable is not set.
                $role = session('google_signup_role', 'student');
                $user->role = ($role === 'teacher') ? UserRole::Teacher : UserRole::Student;
                $user->save();
            }

            // Clean up the session variable
            session()->forget('google_signup_role');

            Auth::login($user, true); // Log in and "remember" the user.

            return redirect()->intended($this->redirectTo());

        } catch (\Exception $e) {
            // Log the error or show a friendly message.
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong with Google Login. Please try again.');
        }
    }

    /**
     * Redirect based on user role.
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        if ($user->role === UserRole::Teacher) {
            return route('teacher.index');
        } elseif ($user->role === UserRole::Student) {
            return route('student.dashboard');
        }
        // As a fallback, redirect to a generic dashboard or login.
        return route('dashboard'); // Assuming a generic dashboard route exists.
    }
}
