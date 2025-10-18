@extends('layouts.app')
@section('content')
<section class="relative overflow-hidden">
  <div class="mx-auto max-w-7xl px-4 pt-16 pb-20 grid md:grid-cols-2 gap-10 items-center">
    <div>
      <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">
        Tăng tốc kinh doanh Facebook với<br><span class="text-brand">SocialSuite</span>
      </h1>
      <p class="mt-4 text-slate-600 text-lg">
        Tự động đăng bài, lên lịch, quản lý nhiều Fanpage, theo dõi hiệu quả – an toàn, đúng chuẩn chính sách Meta.
      </p>
      <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('fb.login') }}" class="px-5 py-3 rounded-xl bg-brand text-white hover:opacity-90">Kết nối Facebook ngay</a>
        <a href="#pricing" class="px-5 py-3 rounded-xl border hover:border-brand hover:text-brand">Xem bảng giá</a>
      </div>
      <div class="mt-6 flex items-center gap-6 text-sm text-slate-600">
        <div>✔ Chuẩn OAuth Meta</div>
        <div>✔ Lịch đăng thông minh</div>
        <div>✔ Hỗ trợ nhiều trang</div>
      </div>
    </div>
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-8">
      <div class="aspect-[16/10] rounded-2xl bg-white shadow-lg flex items-center justify-center text-slate-500">
        <div class="text-center">
          <div class="text-xl font-semibold">Bảng điều khiển SocialSuite</div>
          <div class="mt-2 text-sm">Kết nối Facebook và bắt đầu quản lý</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="mt-10">
  <div class="mx-auto max-w-7xl px-4">
    <div class="text-center text-sm text-slate-500">Được tin dùng bởi các chủ shop online & marketer</div>
  </div>
</section>

<section id="features" class="mt-20">
  <div class="mx-auto max-w-7xl px-4">
    <h2 class="text-2xl md:text-3xl font-bold text-center">Tính năng nổi bật</h2>
    <div class="mt-10 grid md:grid-cols-3 gap-6">
      <div class="p-6 rounded-2xl border hover:shadow-sm">
        <div class="font-semibold">Đăng & Lên lịch đa trang</div>
        <p class="mt-2 text-sm text-slate-600">Một lần soạn – đăng cho nhiều Fanpage, lên lịch theo khung giờ vàng.</p>
      </div>
      <div class="p-6 rounded-2xl border hover:shadow-sm">
        <div class="font-semibold">Quản lý token an toàn</div>
        <p class="mt-2 text-sm text-slate-600">Lưu trữ user/page access token đúng chuẩn, theo dõi hết hạn.</p>
      </div>
      <div class="p-6 rounded-2xl border hover:shadow-sm">
        <div class="font-semibold">Báo cáo hiệu quả</div>
        <p class="mt-2 text-sm text-slate-600">Tổng hợp tương tác, phạm vi tiếp cận, hiệu suất bài viết.</p>
      </div>
    </div>
  </div>
</section>

<section class="mt-20">
  <div class="mx-auto max-w-7xl px-4 grid md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
      <h2 class="text-2xl md:text-3xl font-bold">Cách hoạt động</h2>
      <p class="mt-2 text-slate-600">3 bước đơn giản để bắt đầu.</p>
    </div>
    <div class="md:col-span-2 grid md:grid-cols-3 gap-6">
      <div class="p-6 rounded-2xl border">
        <div class="text-brand font-semibold">Bước 1</div>
        <div class="font-medium">Kết nối Facebook</div>
        <p class="text-sm text-slate-600 mt-1">Đăng nhập & cấp quyền chuẩn OAuth.</p>
      </div>
      <div class="p-6 rounded-2xl border">
        <div class="text-brand font-semibold">Bước 2</div>
        <div class="font-medium">Chọn Fanpage</div>
        <p class="text-sm text-slate-600 mt-1">SocialSuite tự nhận diện & lưu Page tokens.</p>
      </div>
      <div class="p-6 rounded-2xl border">
        <div class="text-brand font-semibold">Bước 3</div>
        <div class="font-medium">Đăng & Lên lịch</div>
        <p class="text-sm text-slate-600 mt-1">Soạn nội dung, đặt giờ, theo dõi hiệu quả.</p>
      </div>
    </div>
  </div>
</section>

<section id="pricing" class="mt-20">
  <div class="mx-auto max-w-7xl px-4">
    <h2 class="text-2xl md:text-3xl font-bold text-center">Bảng giá linh hoạt</h2>
    <div class="mt-10 grid md:grid-cols-3 gap-6">
      <div class="p-6 rounded-2xl border">
        <div class="text-sm text-slate-500">Starter</div>
        <div class="text-3xl font-bold mt-1">0đ</div>
        <ul class="mt-4 text-sm space-y-2">
          <li>• 1 Fanpage</li>
          <li>• Lên lịch cơ bản</li>
          <li>• Hỗ trợ qua email</li>
        </ul>
        <a href="{{ route('fb.login') }}" class="mt-6 inline-block px-4 py-2 rounded-xl bg-brand text-white">Dùng thử</a>
      </div>
      <div class="p-6 rounded-2xl border ring-2 ring-brand">
        <div class="text-sm text-slate-500">Growth</div>
        <div class="text-3xl font-bold mt-1">199k<span class="text-base font-medium text-slate-500">/tháng</span></div>
        <ul class="mt-4 text-sm space-y-2">
          <li>• 10 Fanpage</li>
          <li>• Lên lịch nâng cao</li>
          <li>• Báo cáo cơ bản</li>
        </ul>
        <a href="{{ route('fb.login') }}" class="mt-6 inline-block px-4 py-2 rounded-xl bg-brand text-white">Bắt đầu</a>
      </div>
      <div class="p-6 rounded-2xl border">
        <div class="text-sm text-slate-500">Pro</div>
        <div class="text-3xl font-bold mt-1">Liên hệ</div>
        <ul class="mt-4 text-sm space-y-2">
          <li>• Không giới hạn Fanpage</li>
          <li>• Báo cáo & API tuỳ biến</li>
          <li>• Hỗ trợ ưu tiên</li>
        </ul>
        <a href="#contact" class="mt-6 inline-block px-4 py-2 rounded-xl border hover:border-brand hover:text-brand">Nhận tư vấn</a>
      </div>
    </div>
  </div>
</section>

<section id="faqs" class="mt-20">
  <div class="mx-auto max-w-7xl px-4">
    <h2 class="text-2xl md:text-3xl font-bold text-center">Câu hỏi thường gặp</h2>
    <div class="mt-8 grid md:grid-cols-2 gap-6">
      <div class="p-6 rounded-2xl border">
        <div class="font-medium">SocialSuite có tuân thủ chính sách Meta không?</div>
        <p class="text-sm text-slate-600 mt-1">Có. Hệ thống dùng OAuth chuẩn, token lưu an toàn và chỉ gọi các API được cấp phép.</p>
      </div>
      <div class="p-6 rounded-2xl border">
        <div class="font-medium">Tôi có thể quản lý bao nhiêu Fanpage?</div>
        <p class="text-sm text-slate-600 mt-1">Tuỳ gói. Bạn có thể nâng cấp để không giới hạn.</p>
      </div>
    </div>
  </div>
</section>

<section class="mt-20">
  <div class="mx-auto max-w-7xl px-4 text-center">
    <div class="p-10 rounded-3xl bg-gradient-to-r from-blue-50 to-indigo-50">
      <h3 class="text-2xl md:text-3xl font-bold">Sẵn sàng tăng trưởng với SocialSuite?</h3>
      <p class="mt-2 text-slate-600">Kết nối Facebook và bắt đầu tự động hoá ngay hôm nay.</p>
      <a href="{{ route('fb.login') }}" class="mt-6 inline-block px-6 py-3 rounded-xl bg-brand text-white">Kết nối ngay</a>
    </div>
  </div>
</section>
@endsection
