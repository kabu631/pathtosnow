<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()    { return Inertia::render('Auth/Login'); }
    public function showRegister() { return Inertia::render('Auth/Register'); }

    public function login(Request $req)
    {
        $req->validate(['email' => 'required|email', 'password' => 'required']);
        if (!Auth::attempt($req->only('email', 'password'), $req->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }
        $req->session()->regenerate();
        return redirect()->intended(Auth::user()->isAdmin() ? route('admin.dashboard') : route('home'));
    }

    public function register(Request $req)
    {
        $req->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
        Auth::login(User::create([
            'name'     => $req->name,
            'email'    => $req->email,
            'password' => bcrypt($req->password),
        ]));
        $req->session()->regenerate();
        return redirect()->route('home');
    }

    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('home');
    }
}
