<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials, $request->filled('remember'))) {

            $user = Auth::user();
            $allowedEmails = ['gruba@gmail.com', 'imsanjay.tech@gmail.com'];

            if ($user && ($user->role === 'admin' || in_array($user->email, $allowedEmails))) {
                return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully.');
            }

            return redirect('/')->with('success', 'Logged in successfully.');
        }
    
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // GOOGLE REDIRECT
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // GOOGLE CALLBACK
    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(uniqid()),
                ]);
            }

            Auth::login($user);

            $allowedEmails = ['gruba@gmail.com', 'imsanjay.tech@gmail.com'];

            if ($user && ($user->role === 'admin' || in_array($user->email, $allowedEmails))) {
                return redirect()->route('admin.dashboard');
            }

            return redirect('/');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google login failed.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
