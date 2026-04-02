<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;


class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Try to find user by google_id
            $user = User::where('google_id', $googleUser->getId())->first();

            // 2. If not found, try by email
            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
            }

            // 3. If still not found, create new user
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(24)),
                ]);
            }

            // 4. If user exists but google_id missing, link it
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user, true);

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            return redirect()
                ->route('login')
                ->with('status', __('Google sign-in failed. Please try again.'));
        }
    }

}
