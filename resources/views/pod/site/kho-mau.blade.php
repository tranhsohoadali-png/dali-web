@extends('pod.site._khung', ['tab' => 'kho'])
@section('tieu_de', $nhom !== '' ? 'Kho mẫu ' . $nhom : 'Kho mẫu in 3D cho seller POD')
@section('mo_ta', 'Kho mẫu chung của xưởng DALI: mẫu in 3D làm sẵn cho seller POD, có ảnh mockup để đăng bán. Đăng nhập để xem giá và đặt in.')
@section('noi_dung')
<div class="wrap" style="padding:56px 20px 8px">
  <span class="nhan">Kho chung</span>
  <h1 style="font-size:clamp(30px,4.4vw,44px)">Kho mẫu in 3D</h1>
  <p class="phu" style="margin-top:12px">Mẫu xưởng làm sẵn, mọi seller dùng được. Giá và đặt in nằm trong cổng seller sau khi đăng nhập. Mẫu độc quyền của từng seller không hiện ở đây.</p>
  @if($cacNhom->isNotEmpty())
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:22px">
    <a class="chip {{ $nhom === '' ? 'on' : '' }}" href="/kho-mau">Tất cả</a>
    @foreach($cacNhom as $n)<a class="chip {{ $nhom === $n ? 'on' : '' }}" href="/kho-mau?nhom={{ urlencode($n) }}">{{ $n }}</a>@endforeach
  </div>
  @endif
</div>
<section style="padding-top:28px">
  <div class="wrap">
    @if($ds->isEmpty())
      <div class="the" style="text-align:center;color:var(--mu)">Kho mẫu đang được cập nhật. Muốn xưởng làm mẫu riêng cho shop bạn, <a href="/dang-ky">đăng ký seller</a> hoặc nhắn Zalo 0856 911 698.</div>
    @else
      <div class="luoi l4">
        @foreach($ds as $m)
          <a class="mau-the" href="/kho-mau/{{ $m->ma }}">
            <div class="anh">@if(!empty($m->anh))<img src="/anh-mau/{{ $m->ma }}/0" alt="{{ $m->ten }}" loading="lazy">@else Chưa có ảnh @endif</div>
            <div class="chu"><b>{{ $m->ten }}</b><small>{{ $m->nhom }}@if($m->bienThe->count() > 1){{ $m->nhom ? ' · ' : '' }}{{ $m->bienThe->count() }} phân loại @endif</small></div>
          </a>
        @endforeach
      </div>
    @endif
    <div class="the" style="margin-top:32px;display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap">
      <div><h3 style="margin:0">Muốn mẫu không ai khác có?</h3><p style="color:var(--mu);font-size:15px">Xưởng thiết kế mẫu riêng, chỉ shop bạn thấy và đặt được.</p></div>
      <a href="/bang-gia#thiet-ke" class="nut nut-vien nut-nho">Xem phí thiết kế</a>
    </div>
  </div>
</section>
@endsection
