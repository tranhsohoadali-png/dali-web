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
.gia-co{margin-top:20px;background:#fff;border:1px solid var(--bd);border-radius:14px;overflow:hidden}
.gia-co th{background:var(--gll)}
.gia-co td.so{color:var(--gd);font-size:16px}
.thu-ten label{display:flex;flex-direction:column;gap:6px;font-weight:700;font-size:14px}
.thu-ten input{font:inherit;font-size:18px;border:1.5px solid var(--bd);border-radius:12px;padding:12px 14px;background:var(--gll);outline:none}
.thu-ten input:focus{border-color:var(--g);background:#fff}
.chip-mau{display:inline-flex;align-items:center;gap:7px;border:1.5px solid var(--bd);background:#fff;border-radius:20px;padding:6px 12px 6px 7px;font:inherit;font-size:14px;font-weight:600;cursor:pointer}
.chip-mau i{width:18px;height:18px;border-radius:50%;border:1px solid rgba(0,0,0,.15);display:block}
.chip-mau.on{border-color:var(--gd);box-shadow:0 0 0 2px var(--gl)}
.chip-kieu{border:1.5px solid var(--bd);background:#fff;border-radius:12px;padding:8px 14px;font:inherit;font-size:14px;font-weight:600;cursor:pointer}
.chip-kieu.on{border-color:var(--gd);background:var(--gll);box-shadow:0 0 0 2px var(--gl)}
.chip-kieu .ten-font{font-size:19px;font-weight:400;line-height:1.2;display:block}
.chip-kieu small{display:block;color:var(--mu);font-size:11px;font-weight:600}
.nhom-tieu{margin-top:14px;font-weight:700;font-size:14px}
.kt-hop{background:#fff;border:1px solid var(--bd);border-radius:16px;padding:16px;margin-top:16px}
.kt-hop canvas{width:100%;height:auto;display:block;margin:12px 0;border-radius:10px;border:1px solid var(--bd)}
.gia-co tr.chon td{background:var(--gll)}
@endsection
@section('noi_dung')
<div class="wrap">
  <p style="padding-top:22px;font-size:14px;color:var(--mu)"><a href="/kho-mau" style="text-decoration:none">Kho mẫu</a>@if($m->nhom) › <a href="/kho-mau?nhom={{ urlencode($m->nhom) }}" style="text-decoration:none">{{ $m->nhom }}</a>@endif</p>
  <div class="mau-chi-tiet">
    @php
      $xt = $m->tenToiDa() ? $m->xem_truoc : null;
      $mauSac = $m->bienThe->flatMap(fn ($b) => $b->dsMau())->unique()->values();
      $theoKieu = $m->bienThe->groupBy(fn ($b) => (string) $b->kieu);   // '' = mẫu không chia kiểu
      $kieu0 = (string) $theoKieu->keys()->first();
      $khung0 = ($xt && $kieu0 !== '' && isset($xt['kieu'][$kieu0])) ? $xt['kieu'][$kieu0] : $xt;
      $fonts = $m->dsFont();
      $coKt = $m->coKichThuoc();
      // mỗi (kiểu, cỡ) một số đo — PETG/PLA cùng hình nên lấy một
      $dsCo = $m->bienThe->filter(fn ($b) => !empty($b->kich_thuoc))->unique(fn ($b) => $b->kieu . '|' . $b->ten)
          ->map(fn ($b) => ['kieu' => $b->kieu, 'ten' => $b->ten, 'kt' => $b->kich_thuoc])->values();
      $hinhKt = array_filter(['truoc' => $m->anhHinhChieu('truoc') ? "/anh-kt/{$m->ma}/truoc" : null,
                              'ben' => $m->anhHinhChieu('ben') ? "/anh-kt/{$m->ma}/ben" : null]);
    @endphp
    <div>
      @if($xt)
      <div class="anh-chinh" style="aspect-ratio:auto"><canvas id="cvXt" width="900" height="{{ !empty($khung0['w']) ? (int) round(900 * $khung0['h'] / $khung0['w']) : 520 }}" style="width:100%;height:auto;display:block" aria-label="Ảnh xem trước {{ $m->ten }} theo tên"></canvas></div>
      <button type="button" class="nut nut-vien nut-nho" id="taiXt" style="margin-top:12px">Tải ảnh xem trước</button>
      @else
      <div class="anh-chinh">@if(!empty($m->anh))<img id="anhChinh" src="/anh-mau/{{ $m->ma }}/0" alt="{{ $m->ten }}">@endif</div>
      @endif
      @if(!$xt && count($m->anh ?: []) > 1)
      <div class="anh-phu">
        @foreach(array_values($m->anh) as $i => $x)<a href="/anh-mau/{{ $m->ma }}/{{ $i }}" onclick="document.getElementById('anhChinh').src=this.href;return false"><img src="/anh-mau/{{ $m->ma }}/{{ $i }}" alt="{{ $m->ten }} ảnh {{ $i + 1 }}" loading="lazy"></a>@endforeach
      </div>
      @endif
      @if($coKt)
      <div class="kt-hop">
        <div class="nhom-tieu" style="margin-top:0">Kích thước từng cỡ</div>
        <div class="pl" id="coKt"></div>
        <canvas id="cvKt" width="900" height="560" aria-label="Ảnh kích thước {{ $m->ten }}"></canvas>
        <button type="button" class="nut nut-vien nut-nho" id="taiKt">Tải ảnh kích thước</button>
        <p style="color:var(--mu);font-size:13px;margin-top:8px">Hình nhìn trước và nhìn bên cùng tỉ lệ, số đo theo file in.@if($dsCo->contains(fn ($x) => isset($x['kt']['ti_le']))) Số có dấu ~ đo theo tên đang gõ, lệch bản in khoảng ±0.1 inch.@endif Dùng làm ảnh kích thước khi đăng bán.</p>
      </div>
      @endif
    </div>
    <div>
      <span class="nhan">Kho chung</span>
      <h1 style="font-size:clamp(28px,3.6vw,40px)">{{ $m->ten }}</h1>
      @if($m->mo_ta)<p style="color:var(--mu);margin-top:12px;white-space:pre-line">{{ $m->mo_ta }}</p>@endif
      @php
        $bg = \App\Services\PodSo::bangGia();
        $cls = $m->bienThe->map(fn ($b) => \App\Services\PodSo::chatLieu($b->chat_lieu))->unique()->values();
      @endphp
      @if($m->bienThe->isNotEmpty())
        @foreach($theoKieu as $k => $dsKieu)
        <table class="bang gia-co" data-kieu="{{ $k }}" @if($k !== $kieu0) hidden @endif>
          <thead><tr><th>{{ $k !== '' ? $m->tenKieu($k) . ' · kích thước' : 'Kích thước' }}</th>@foreach($cls as $cl)<th class="so">{{ $cl }}</th>@endforeach</tr></thead>
          <tbody>
            @foreach($dsKieu->groupBy('ten') as $tenCo => $ds)
            <tr data-co="{{ $tenCo }}"><td><b>{{ $tenCo }}</b></td>
              @foreach($cls as $cl)
                @php $b = $ds->first(fn ($x) => \App\Services\PodSo::chatLieu($x->chat_lieu) === $cl); @endphp
                <td class="so">{{ $b ? number_format(\App\Services\PodSo::giaBienThe($m, $b, $bg), 0, ',', '.') . 'đ' : '—' }}</td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
        @endforeach
        <p style="color:var(--mu);font-size:13px;margin-top:6px">Giá in 1 chiếc, chưa gồm phí vận chuyển.@if($xt) {{ $xt['ghi_co'] ?? 'Kích thước đo theo bề ngang.' }}@endif</p>
      @endif
      @if($xt)
        <div class="thu-ten">
          @if($theoKieu->count() > 1)
          <div class="nhom-tieu" style="margin-top:0">Kiểu</div>
          <div class="pl" id="kieuXt">@foreach($theoKieu->keys() as $i => $k)<button type="button" class="chip-kieu {{ $i ? '' : 'on' }}" data-kieu="{{ $k }}">{{ $m->tenKieu($k) }}</button>@endforeach</div>
          @endif
          <label style="margin-top:14px"><span>Thử tên của bạn</span><input id="tenXt" maxlength="{{ $m->tenToiDa($kieu0 !== '' ? $kieu0 : null) }}" placeholder="Vd: Emma" autocomplete="off"></label>
          @if($mauSac->isNotEmpty())
          <div class="nhom-tieu">Màu nhựa</div>
          <div class="pl" id="mauXt">@foreach($mauSac as $i => $c)<button type="button" class="chip-mau {{ $i ? '' : 'on' }}" data-mau="{{ $c }}"><i></i>{{ $c }}</button>@endforeach</div>
          @endif
          @if(count($fonts) > 1)
          <div class="nhom-tieu">Font chữ</div>
          <div class="pl" id="fontXt">@foreach($fonts as $i => $f)<button type="button" class="chip-kieu {{ $i ? '' : 'on' }}" data-font="{{ $f }}"><span class="ten-font">Emma</span><small>{{ $f }}</small></button>@endforeach</div>
          @endif
          <p style="color:var(--mu);font-size:14px;margin-top:10px">Ảnh vẽ từ file in thật, gần giống bản in. Tên tối đa <span id="toiDaXt">{{ $m->tenToiDa($kieu0 !== '' ? $kieu0 : null) }}</span> ký tự.</p>
        </div>
      @elseif($m->phi_ca_nhan > 0)<p style="margin-top:14px;font-size:15px">Có nhận chữ cá nhân hoá theo từng đơn.</p>@endif
      <div class="khoa">
        <b>Đặt in trong cổng seller</b>
        <p style="color:var(--mu);font-size:15px;margin:6px 0 14px">Chọn cỡ, nhựa, màu và tên ngay trong cổng; đơn trừ thẳng vào ví nạp trước. Ảnh mockup tải trong cổng để đăng bán.</p>
        <div style="display:flex;gap:10px;flex-wrap:wrap"><a href="/cong" class="nut nut-chinh nut-nho">Đăng nhập để đặt</a><a href="/dang-ky" class="nut nut-vien nut-nho">Chưa có tài khoản? Đăng ký</a></div>
      </div>
    </div>
  </div>
  @if($lienQuan->isNotEmpty())
  <section style="padding-top:24px">
    <h2 style="font-size:24px">Mẫu cùng nhóm</h2>
    <div class="luoi l4" style="margin-top:16px">
      @foreach($lienQuan as $x)<a class="mau-the" href="/kho-mau/{{ $x->ma }}"><div class="anh"><img src="/anh-mau/{{ $x->ma }}/0" alt="{{ $x->ten }}" loading="lazy"></div><div class="chu"><b>{{ $x->ten }}</b><small>{{ $x->nhom }}</small>@php $gt = \App\Services\PodSo::giaTu($x); @endphp @if($gt)<span class="gia-tu">từ {{ number_format($gt, 0, ',', '.') }}đ</span>@endif</div></a>@endforeach
    </div>
  </section>
  @endif
</div>
@if($xt || $coKt)
@include('pod._ve_ten')
<script>
(function () {
  var d = @json($xt), cv = document.getElementById('cvXt'), ip = document.getElementById('tenXt');
  var kieu = @json($kieu0 !== '' ? $kieu0 : null), fonts = @json($fonts), font = fonts[0] || '', mau = '';
  var dsCo = @json($dsCo), hinh = @json((object) $hinhKt), co = null, cvKt = document.getElementById('cvKt');
  var TEN = {!! json_encode($m->ten, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
  function esc(s) { return String(s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
  function coCuaKieu() { return dsCo.filter(function (x) { return (x.kieu || null) === (kieu || null); }); }
  function coNay() { return coCuaKieu().find(function (x) { return x.ten === co; }) || null; }
  function veCo() { // chip cỡ của kiểu đang chọn + tô dòng giá tương ứng
    var hop = document.getElementById('coKt'), ds = coCuaKieu();
    if (!ds.some(function (x) { return x.ten === co; })) co = ds.length ? ds[0].ten : null;
    if (hop) {
      hop.innerHTML = ds.map(function (x) { return '<button type="button" class="chip-kieu' + (x.ten === co ? ' on' : '') + '" data-co="' + esc(x.ten) + '">' + esc(x.ten) + '</button>'; }).join('');
      hop.querySelectorAll('button').forEach(function (b) { b.onclick = function () { co = b.dataset.co; veCo(); ve(); }; });
    }
    document.querySelectorAll('table.gia-co tr[data-co]').forEach(function (tr) { tr.classList.toggle('chon', !!cvKt && tr.dataset.co === co); });
  }
  function veKt() {
    if (!cvKt) return;
    var x = coNay();
    PodVeTen.veKt(cvKt, { kt: x && x.kt, hinh: hinh, xt: d, kieu: kieu, chu: ip ? ip.value.trim() : '', mau: mau, font: font });
  }
  if (document.getElementById('taiKt')) document.getElementById('taiKt').onclick = function () {
    PodVeTen.tai(cvKt, [TEN, 'kich thuoc', (d && kieu) ? PodVeTen.khung(d, kieu).ten : '', co].filter(Boolean).join('-').replace(/[\\/:*?"<>|]+/g, '') + '.png');
  };
  function chon(sel, ham) {
    var ds = document.querySelectorAll(sel);
    ds.forEach(function (c) { c.onclick = function () { ds.forEach(function (x) { x.classList.toggle('on', x === c); }); ham(c); ve(); }; });
    return ds;
  }
  var chips = chon('#mauXt .chip-mau', function (c) { mau = c.dataset.mau; });
  if (chips.length) mau = chips[0].dataset.mau;
  chips.forEach(function (c) { c.querySelector('i').style.background = PodVeTen.nenChip(c.dataset.mau); });
  chon('#kieuXt .chip-kieu', function (c) {
    kieu = c.dataset.kieu;
    var k = PodVeTen.khung(d, kieu), td = k.toi_da || d.toi_da || 12;
    ip.maxLength = td; document.getElementById('toiDaXt').textContent = td;
    if (ip.value.length > td) ip.value = ip.value.slice(0, td);
    document.querySelectorAll('table.gia-co[data-kieu]').forEach(function (t) { t.hidden = t.dataset.kieu !== kieu; });
    veCo();
  });
  chon('#fontXt .chip-kieu', function (c) { font = c.dataset.font; })
    .forEach(function (c) { c.querySelector('.ten-font').style.font = PodVeTen.css(c.dataset.font, 19); });
  function ve() {
    veKt();
    if (!d || !cv) return;
    var k = PodVeTen.khung(d, kieu), ten = ip.value.trim(), x = coNay();
    PodVeTen.datCao(cv, k);
    document.querySelectorAll('#fontXt .ten-font').forEach(function (s) { s.textContent = ten || 'Emma'; });
    PodVeTen.choFont(font).then(function () { PodVeTen.ve(cv, k, ten || 'Tên của bạn', mau, font, { ti: x && x.kt && x.kt.ti_le }); });
  }
  if (ip) ip.oninput = ve;
  if (document.getElementById('taiXt')) document.getElementById('taiXt').onclick = function () {
    PodVeTen.tai(cv, [TEN, PodVeTen.khung(d, kieu).ten, ip.value.trim() || 'mau', mau].filter(Boolean).join('-').replace(/[\\/:*?"<>|]+/g, '') + '.png');
  };
  veCo();
  ve();
})();
</script>
@endif
@endsection
