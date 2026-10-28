@extends('pod.site._khung', ['tab' => 'kho'])
@section('tieu_de', $m->ten)
@section('mo_ta', \Illuminate\Support\Str::limit(trim(($m->mo_ta ?: $m->ten) . ' · Mẫu in 3D Kho chung DALI cho seller POD, có ảnh mockup để đăng bán.'), 155))
@if(!empty($m->anh))@section('og_anh', rtrim(config('pod.url'), '/') . '/anh-mau/' . $m->ma . '/0')@endif
@section('css')
.mau-chi-tiet{display:grid;gap:36px;padding:44px 0 24px}
@media(min-width:880px){.mau-chi-tiet{grid-template-columns:1.05fr .95fr}}
.anh-chinh{aspect-ratio:1;border-radius:20px;overflow:hidden;background:#fff;border:1px solid var(--bd)}
.anh-chinh img{width:100%;height:100%;object-fit:contain}
.anh-phu{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
.anh-phu a{width:72px;height:72px;border-radius:12px;overflow:hidden;border:2px solid var(--bd);display:block}
.anh-phu img{width:100%;height:100%;object-fit:cover}
.pl{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
.pl span{border:1px solid var(--bd);background:#fff;border-radius:10px;padding:7px 12px;font-size:14px;font-weight:600}
.khoa{background:var(--gll);border:1px dashed var(--g);border-radius:16px;padding:20px;margin-top:22px}
@endsection
@section('noi_dung')
<div class="wrap">
  <p style="padding-top:22px;font-size:14px;color:var(--mu)"><a href="/kho-mau" style="text-decoration:none">Kho mẫu</a>@if($m->nhom) › <a href="/kho-mau?nhom={{ urlencode($m->nhom) }}" style="text-decoration:none">{{ $m->nhom }}</a>@endif</p>
  <div class="mau-chi-tiet">
    <div>
      <div class="anh-chinh">@if(!empty($m->anh))<img id="anhChinh" src="/anh-mau/{{ $m->ma }}/0" alt="{{ $m->ten }}">@endif</div>
      @if(count($m->anh ?: []) > 1)
      <div class="anh-phu">
        @foreach(array_values($m->anh) as $i => $x)<a href="/anh-mau/{{ $m->ma }}/{{ $i }}" onclick="document.getElementById('anhChinh').src=this.href;return false"><img src="/anh-mau/{{ $m->ma }}/{{ $i }}" alt="{{ $m->ten }} ảnh {{ $i + 1 }}" loading="lazy"></a>@endforeach
      </div>
      @endif
    </div>
    <div>
      <span class="nhan">Kho chung</span>
      <h1 style="font-size:clamp(28px,3.6vw,40px)">{{ $m->ten }}</h1>
      @if($m->mo_ta)<p style="color:var(--mu);margin-top:12px;white-space:pre-line">{{ $m->mo_ta }}</p>@endif
      @if($m->bienThe->isNotEmpty())
        <div style="margin-top:20px;font-weight:700">Phân loại</div>
        <div class="pl">@foreach($m->bienThe as $b)<span>{{ $b->ten }} · {{ \App\Services\PodSo::chatLieu($b->chat_lieu) }}</span>@endforeach</div>
      @endif
      @if($m->phi_ca_nhan > 0)<p style="margin-top:14px;font-size:15px">Có nhận chữ cá nhân hoá theo từng đơn.</p>@endif
      <div class="khoa">
        <b>Giá và đặt in nằm trong cổng seller</b>
        <p style="color:var(--mu);font-size:15px;margin:6px 0 14px">@php $bg = \App\Services\PodSo::bangGia(); @endphp Giá tính theo gram × đơn giá nhựa (PETG {{ number_format($bg['PETG'], 0, ',', '.') }}đ, PLA {{ number_format($bg['PLA'], 0, ',', '.') }}đ), hiện ngay khi bạn chọn phân loại. Ảnh mockup tải trong cổng để đăng bán.</p>
        <div style="display:flex;gap:10px;flex-wrap:wrap"><a href="/cong" class="nut nut-chinh nut-nho">Đăng nhập để đặt</a><a href="/dang-ky" class="nut nut-vien nut-nho">Chưa có tài khoản? Đăng ký</a></div>
      </div>
    </div>
  </div>
  @if($lienQuan->isNotEmpty())
  <section style="padding-top:24px">
    <h2 style="font-size:24px">Mẫu cùng nhóm</h2>
    <div class="luoi l4" style="margin-top:16px">
      @foreach($lienQuan as $x)<a class="mau-the" href="/kho-mau/{{ $x->ma }}"><div class="anh"><img src="/anh-mau/{{ $x->ma }}/0" alt="{{ $x->ten }}" loading="lazy"></div><div class="chu"><b>{{ $x->ten }}</b><small>{{ $x->nhom }}</small></div></a>@endforeach
    </div>
  </section>
  @endif
</div>
@endsection
