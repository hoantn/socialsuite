@extends('layouts.app')
@section('title','Lịch sử giao dịch')
@section('content')
<h4 class="mb-3">Lịch sử giao dịch</h4>
@if($txns->isEmpty())
  <div class="alert alert-info">Chưa có giao dịch.</div>
@else
  <div class="table-responsive bg-white border rounded">
    <table class="table table-hover mb-0 align-middle">
      <thead><tr><th>#</th><th>Số tiền</th><th>Loại</th><th>Trạng thái</th><th>QR/CK</th><th>Thời gian</th></tr></thead>
      <tbody>
        @foreach($txns as $t)
        <tr>
          <td>{{ $t->id }}</td>
          <td class="{{ $t->amount>0?'text-success':'text-danger' }}">{{ number_format($t->amount) }}</td>
          <td>{{ $t->type }}</td>
          <td>{{ $t->status }}</td>
          <td>
            @if($t->type==='deposit' && $t->status==='pending')
              @php
                $bank = env('VIETQR_BANK','vietcombank');
                $acc  = env('VIETQR_ACCOUNT','0123456789');
                $name = urlencode(env('VIETQR_ACCOUNT_NAME','NGUYEN VAN A'));
                $amount = $t->amount;
                $addInfo = urlencode('NAP'.auth()->id().'TXN'.$t->id);
                $qr = "https://img.vietqr.io/image/{$bank}-{$acc}-compact2.png?amount={$amount}&addInfo={$addInfo}&accountName={$name}";
              @endphp
              <img src="{{ $qr }}" alt="QR" style="height:120px">
              <div class="small text-muted">Nội dung: NAP{{ auth()->id() }}TXN{{ $t->id }}</div>
            @endif
          </td>
          <td>{{ $t->created_at }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif
@endsection
