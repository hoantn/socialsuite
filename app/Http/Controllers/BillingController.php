<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Plan;
use Illuminate\Support\Str;
class BillingController extends Controller
{
    public function topupForm(){ return view('billing.topup'); }
    public function createTopup(Request $r){
        $data=$r->validate(['amount'=>'required|integer|min:10000']);
        $txn=Transaction::create(['user_id'=>Auth::id(),'amount'=>$data['amount'],'type'=>'deposit','status'=>'pending','ref'=>Str::upper('U'.Auth::id().'T'.time())]);
        return redirect()->route('billing.history')->with('ok','Đã tạo yêu cầu nạp, quét QR để chuyển khoản.');
    }
    public function history(){ $txns=Transaction::where('user_id',Auth::id())->orderBy('id','desc')->get(); return view('billing.history',compact('txns')); }
    public function plans(){ $plans=Plan::orderBy('price','asc')->get(); $user=Auth::user(); return view('billing.plans',compact('plans','user')); }
    public function upgrade(Request $r){
        $data=$r->validate(['plan_id'=>'required|integer']); $plan=Plan::findOrFail($data['plan_id']); $user=Auth::user();
        $user->plan_id=$plan->id; $user->plan_expires_at=now()->addMonth(); $user->save();
        return back()->with('ok','Đã nâng cấp gói.');
    }
}
