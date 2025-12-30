<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login/register form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $credentials = $request->only('email', 'password');

            if (! Auth::attempt($credentials)) {
                return back()
                    ->withErrors(['email' => 'Invalid credentials'])
                    ->withInput();
            }

            $request->session()->regenerate();

            return redirect()->intended('dashboard')
                ->with('success', 'Login successful!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Login failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'              => 'required|string|max:255',
                'email'             => 'required|string|email|max:255|unique:users',
                'password'          => 'required|string|min:8|confirmed',
                'role'              => 'required|in:admin,user',
                'email_verified_at' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'role'              => $request->role,
                'email_verified_at' => $request->email_verified_at ? Carbon::parse($request->email_verified_at) : now(),
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Registration successful!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])
                ->withInput();
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
                ->with('success', 'Logged out successfully!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Logout failed: ' . $e->getMessage()]);
        }
    }
}
