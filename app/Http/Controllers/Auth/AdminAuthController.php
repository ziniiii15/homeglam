<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('admin.auth.login'); // ✅ fixed
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $admins = Admin::all();

        foreach ($admins as $admin) {
            if (Hash::check($request->password, $admin->password)) {
                Auth::guard('admin')->login($admin);
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors([
            'password' => 'Invalid password.',
        ]);
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('admin.auth.register'); // ✅ fixed
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin',
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('admin')->login($admin);
        return redirect()->route('admin.dashboard');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
