@extends('shared/layout')
@section('content')
<div class=\"wrap grid\" style=\"grid-template-columns:2fr 1fr\">
  <div>
    @if(session('ok'))<div class=\"alert alert-ok\">{{ session('ok') }}</div>@endif
    @if(session('error'))<div class=\"alert alert-err\">{{ session('error') }}</div>@endif
    @foreach($posts as $post)
      <div class=\"card\">
        <div style=\"color:#64748b\">#{{ $post->id }} • {{ $post->type }} • {{ $post->status }}</div>
        <div style=\"margin-top:6px;white-space:pre-wrap\">{{ $post->message }}</div>
        @if($post->link)<div><a href=\"{{ $post->link }}\">{{ $post->link }}</a></div>@endif
        @if($post->image_url)<img src=\"{{ $post->image_url }}\" style=\"max-height:200px;border-radius:12px;margin-top:6px\">@endif
        <div class=\"row\" style=\"margin-top:8px\">
          @if($post->status!=='published')
          <form method=\"POST\" action=\"{{ route('pages.posts.publish', [$page,$post]) }}\">@csrf
            <button class=\"btn\" style=\"background:#334155\">Đăng ngay</button>
          </form>
          @endif
          @if($post->fb_post_id)<a class=\"btn\" href=\"https://facebook.com/{{ $post->fb_post_id }}\" target=\"_blank\">Xem trên Facebook</a>@endif
        </div>
      </div>
    @endforeach
    <div style=\"margin-top:10px\">{{ $posts->links() }}</div>
  </div>
  <div>
    <div class=\"card\">
      <div style=\"font-weight:700\">Tạo bài viết</div>
      <form method=\"POST\" action=\"{{ route('pages.posts.store',$page) }}\" style=\"margin-top:10px\">@csrf
        <label>Loại</label>
        <select name=\"type\" class=\"inp\">
          <option value=\"text\">Văn bản</option>
          <option value=\"photo\">Ảnh (URL)</option>
          <option value=\"link\">Link</option>
        </select>
        <label style=\"margin-top:8px\">Nội dung</label>
        <textarea class=\"inp\" rows=\"4\" name=\"message\"></textarea>
        <label style=\"margin-top:8px\">Link (tuỳ chọn)</label>
        <input class=\"inp\" name=\"link\" type=\"url\" />
        <label style=\"margin-top:8px\">Ảnh - URL (tuỳ chọn)</label>
        <input class=\"inp\" name=\"image_url\" type=\"url\" />
        <div class=\"row\" style=\"margin-top:10px\">
          <button name=\"action\" value=\"save\" class=\"btn\" style=\"background:#64748b\">Lưu nháp</button>
          <button name=\"action\" value=\"publish_now\" class=\"btn\">Đăng ngay</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
