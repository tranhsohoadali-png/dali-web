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
.thu-ten{background:#fff;border:1px solid var(--bd);border-radius:16px;padding:18px;margin-top:20px}
.thu-ten label{display:flex;flex-direction:column;gap:6px;font-weight:700;font-size:14px}
.thu-ten input{font:inherit;font-size:18px;border:1.5px solid var(--bd);border-radius:12px;padding:12px 14px;background:var(--gll);outline:none}
.thu-ten input:focus{border-color:var(--g);background:#fff}
.chip-mau{display:inline-flex;align-items:center;gap:7px;border:1.5px solid var(--bd);background:#fff;border-radius:20px;padding:6px 12px 6px 7px;font:inherit;font-size:14px;font-weight:600;cursor:pointer}
.chip-mau i{width:18px;height:18px;border-radius:50%;border:1px solid rgba(0,0,0,.15);display:block}
.chip-mau.on{border-color:var(--gd);box-shadow:0 0 0 2px var(--gl)}
@endsection
@section('noi_dung')
<div class="wrap">
  <p style="padding-top:22px;font-size:14px;color:var(--mu)"><a href="/kho-mau" style="text-decoration:none">Kho mẫu</a>@if($m->nhom) › <a href="/kho-mau?nhom={{ urlencode($m->nhom) }}" style="text-decoration:none">{{ $m->nhom }}</a>@endif</p>
  <div class="mau-chi-tiet">
    @php
      $xt = $m->tenToiDa() ? $m->xem_truoc : null;
      $mauSac = $m->bienThe->flatMap(fn ($b) => $b->dsMau())->unique()->values();
    @endphp
    <div>
      @if($xt)
      <div class="anh-chinh" style="aspect-ratio:auto"><canvas id="cvXt" width="900" height="{{ (int) round(900 * $xt['h'] / $xt['w']) }}" style="width:100%;height:auto;display:block" aria-label="Ảnh xem trước {{ $m->ten }} theo tên"></canvas></div>
      <button type="button" class="nut nut-vien nut-nho" id="taiXt" style="margin-top:12px">Tải ảnh xem trước</button>
      @else
      <div class="anh-chinh">@if(!empty($m->anh))<img id="anhChinh" src="/anh-mau/{{ $m->ma }}/0" alt="{{ $m->ten }}">@endif</div>
      @endif
      @if(!$xt && count($m->anh ?: []) > 1)
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
      @if($xt)
        <div class="thu-ten">
          <label><span>Thử tên của bạn</span><input id="tenXt" maxlength="{{ $m->tenToiDa() }}" placeholder="Vd: Emma" autocomplete="off"></label>
          @if($mauSac->isNotEmpty())
          <div style="margin-top:14px;font-weight:700;font-size:14px">Màu nhựa</div>
          <div class="pl" id="mauXt">@foreach($mauSac as $i => $c)<button type="button" class="chip-mau {{ $i ? '' : 'on' }}" data-mau="{{ $c }}"><i></i>{{ $c }}</button>@endforeach</div>
          @endif
          <p style="color:var(--mu);font-size:14px;margin-top:10px">Ảnh vẽ từ file in thật bằng font của mẫu, gần giống bản in. Tên tối đa {{ $m->tenToiDa() }} ký tự.</p>
        </div>
      @elseif($m->phi_ca_nhan > 0)<p style="margin-top:14px;font-size:15px">Có nhận chữ cá nhân hoá theo từng đơn.</p>@endif
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
@if($xt)
@include('pod._ve_ten')
<script>
(function () {
  var d = @json($xt), cv = document.getElementById('cvXt'), ip = document.getElementById('tenXt');
  var chips = document.querySelectorAll('#mauXt .chip-mau'), mau = chips.length ? chips[0].dataset.mau : '';
  chips.forEach(function (c) {
    c.querySelector('i').style.background = PodVeTen.mauHex(c.dataset.mau);
    c.onclick = function () { mau = c.dataset.mau; chips.forEach(function (x) { x.classList.toggle('on', x === c); }); ve(); };
  });
  function ve() { PodVeTen.choFont().then(function () { PodVeTen.ve(cv, d, ip.value.trim() || 'Tên của bạn', mau); }); }
  ip.oninput = ve;
  document.getElementById('taiXt').onclick = function () {
    PodVeTen.tai(cv, ({!! json_encode($m->ten, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!} + '-' + (ip.value.trim() || 'mau') + (mau ? '-' + mau : '')).replace(/[\\/:*?"<>|]+/g, '') + '.png');
  };
  ve();
})();
</script>
@endif
@endsection
