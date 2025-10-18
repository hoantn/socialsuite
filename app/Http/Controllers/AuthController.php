<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/** [SOCIALSUITE][GPT][2025-10-18 09:18 +07] Username-based Auth */
class AuthController extends Controller {
    public function register(Request $r) {
        $d = $r->validate([
            'username'=>'required|string|min:3|max:30|alpha_dash|unique:users,username',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6|confirmed',
            'name'=>'nullable|string|max:100',
        ]);
        $u = User::create([
            'name'=>$d['name']??null,
            'username'=>$d['username'],
            'email'=>$d['email'],
            'password'=>Hash::make($d['password']),
        ]);
        Auth::login($u);
        return response()->json(['message'=>'registered','user'=>$u]);
    }
    public function login(Request $r) {
        $d = $r->validate(['login'=>'required|string','password'=>'required|string']);
        $field = filter_var($d['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt([$field=>$d['login'],'password'=>$d['password']], true)) {
            $r->session()->regenerate();
            return response()->json(['message'=>'logged_in','user'=>Auth::user()]);
        }
        return response()->json(['message'=>'invalid_credentials'], 401);
    }
    public function logout(Request $r) {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return response()->json(['message'=>'logged_out']);
    }
}
