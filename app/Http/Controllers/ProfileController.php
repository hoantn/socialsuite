<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    public function show()
    {
        $u = Auth::user();
        return view('profile.me', compact('u'));
    }
    public function update(Request $r)
    {
        $u = Auth::user();
        $d = $r->validate([
            'name'  => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:32',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $u->name = $d['name'] ?? $u->name;
        $u->email = $d['email'] ?? $u->email;
        $u->phone = $d['phone'] ?? $u->phone;
        if (!empty($d['password'])) { $u->password = Hash::make($d['password']); }
        $u->save();
        return back()->with('ok','Đã lưu thông tin tài khoản.');
    }
}
