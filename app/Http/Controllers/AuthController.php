<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{
    public function register(Request $r)
    {
        $d = $r->validate([
            'username' => 'required|string|min:3|max:30|alpha_dash|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'name'     => 'nullable|string|max:100',
            'email'    => 'nullable|email',
            'phone'    => 'nullable|string|max:32',
        ]);
        $u = User::create([
            'name'     => $d['name']     ?? null,
            'username' => $d['username'],
            'email'    => $d['email']    ?? null,
            'phone'    => $d['phone']    ?? null,
            'password' => Hash::make($d['password']),
        ]);
        Auth::login($u);
        return redirect()->route('me');
    }
    public function login(Request $r)
    {
        $d = $r->validate([ 'login' => 'required|string', 'password' => 'required|string' ]);
        $login = $d['login']; $field = 'username';
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) { $field = 'email'; }
        elseif (preg_match('/^\+?\d{6,15}$/', $login)) { $field = 'phone'; }
        if (!Auth::attempt([$field => $login, 'password' => $d['password']], true)) {
            return back()->withErrors(['login' => 'Thông tin đăng nhập không đúng.']);
        }
        $r->session()->regenerate();
        return redirect()->route('me');
    }
    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/');
    }
}
