<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function login(Request $r){
        $cred = $r->validate(['username'=>'required','password'=>'required']);
        if (Auth::attempt($cred, $r->boolean('remember'))) {
            $r->session()->regenerate(); return redirect()->intended('/dashboard');
        }
        return back()->withErrors(['username'=>'Sai thông tin đăng nhập'])->onlyInput('username');
    }
    public function register(Request $r){
        $data = $r->validate([
            'username'=>'required|min:3|max:50|unique:users,username',
            'password'=>'required|min:6|max:100',
            'email'=>'nullable|email|max:100','phone'=>'nullable|max:30'
        ]);
        $u = new User();
        $u->username=$data['username']; $u->password=Hash::make($data['password']);
        $u->email=$data['email']??null; $u->phone=$data['phone']??null;
        $u->is_admin=false; $u->plan_id=1; $u->save();
        Auth::login($u); return redirect('/dashboard');
    }
    public function logout(Request $r){
        Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken(); return redirect('/');
    }
}
