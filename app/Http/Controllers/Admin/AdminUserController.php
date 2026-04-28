<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index(Request $req)
    {
        $query = User::select('id', 'name', 'email', 'role', 'phone', 'nationality', 'created_at')->latest();

        if ($req->filled('q')) {
            $q = $req->q;
            $query->where(fn($w) => $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
        }
        if ($req->filled('role')) {
            $query->where('role', $req->role);
        }

        return Inertia::render('Admin/Users/Index', [
            'users'   => $query->paginate(20)->withQueryString(),
            'filters' => $req->only('q', 'role'),
            'authId'  => Auth::id(),
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', ['user' => $user]);
    }

    public function update(Request $req, User $user)
    {
        $data = $req->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:user,admin',
            'phone'    => 'nullable|string|max:30',
            'nationality' => 'nullable|string|max:100',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'role'        => $data['role'],
            'phone'       => $data['phone'] ?? null,
            'nationality' => $data['nationality'] ?? null,
            ...($req->filled('password') ? ['password' => Hash::make($data['password'])] : []),
        ]);

        return redirect('/admin/users')->with('success', "User {$user->name} updated.");
    }

    public function destroy(Request $req, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }
        $name = $user->name;
        $user->delete();
        return redirect('/admin/users')->with('success', "User {$name} deleted.");
    }
}
