<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\User;
use App\Services\MailService;

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
        $user = User::create([
            'name'     => $req->name,
            'email'    => $req->email,
            'password' => bcrypt($req->password),
        ]);
        Auth::login($user);
        $req->session()->regenerate();
        app(MailService::class)->sendWelcome($user);
        return redirect()->route('home');
    }

    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function showForgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendResetLink(Request $req)
    {
        $req->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($req->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'We\'ve emailed your password reset link.');
        }
        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $req)
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $req->route('token'),
            'email' => $req->query('email', ''),
        ]);
    }

    public function resetPassword(Request $req)
    {
        $req->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $req->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password reset successfully. Please sign in.');
        }
        return back()->withErrors(['email' => __($status)]);
    }
}
