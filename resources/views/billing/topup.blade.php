@extends('layouts.app')
@section('title','Nạp tiền')
@section('content')
<h4 class="mb-3">Nạp tiền</h4>
<form class="row g-3" method="post" action="{{ route('billing.topup.create') }}">@csrf
  <div class="col-md-4"><label class="form-label">Số tiền (VND)</label>
    <input type="number" name="amount" class="form-control" min="10000" step="1000" value="{{ old('amount',100000) }}"></div>
  <div class="col-12"><button class="btn btn-primary">Tạo yêu cầu nạp & QR</button>
    <a href="{{ route('billing.history') }}" class="btn btn-outline-primary">Lịch sử nạp</a></div>
</form>
<div class="mt-4 p-3 bg-white border rounded">
  <h6>Hướng dẫn</h6>
  <ol class="mb-0"><li>Nhập số tiền và bấm "Tạo yêu cầu nạp & QR".</li>
    <li>Quét QR hiển thị trong lịch sử để chuyển khoản. Nội dung CK tự động gồm mã giao dịch để admin đối soát.</li>
    <li>Admin duyệt giao dịch → tiền vào ví → bạn có thể nâng cấp gói.</li></ol>
</div>
@endsection
