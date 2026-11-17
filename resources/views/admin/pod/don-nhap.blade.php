@include('admin.pod._dau', ['tieuDe' => 'Nhập đơn từ file'])
@php
  $boQua = fn ($d) => $d['ma'] !== '' && isset($daCo[$d['ma']]);
  $soMa = $dong->groupBy(fn ($d) => $d['ma'] !== '' ? $d['ma'] : '#' . $d['so'])->count();
@endphp
<div class="card">
  <div class="card-h"><div class="card-t">{{ $nh['ten_file'] }} — {{ $s->ten }} ({{ $s->ma }})</div>
    <div class="card-hint">{{ $dong->count() }} dòng · {{ $dong->sum('sl') }} chiếc · {{ $soMa }} mã đơn · cột đọc được: {{ collect($nh['cot'])->map(fn ($t, $k) => $t)->implode(', ') }}@if($s->traSau()) · seller trả sau @endif</div></div>
</div>
<form method="POST" action="{{ route('admin.pod.don.nhaptao') }}" id="fNhap">
  @csrf
  <input type="hidden" name="k" value="{{ $k }}">
  <div class="card">
    <div class="card-h"><div class="card-t">1 · Khớp sản phẩm trong file với phân loại của xưởng</div><div class="card-hint">Chọn một lần — lần sau seller này gửi file cùng tên sản phẩm thì tự chọn sẵn. Chỉ có mẫu seller này thấy (Kho chung + mẫu riêng của seller / được cấp quyền).</div></div>
    @if($mau->isEmpty())
      <div class="empty">Seller này chưa thấy mẫu nào. Cấp quyền mẫu cho seller ở trang sửa mẫu trước.</div>
    @else
    <div class="pl-cuon"><table>
      <tr><th>Tên trong file</th><th class="num">Dòng · chiếc</th><th style="min-width:360px">Phân loại của xưởng</th><th style="width:160px">Màu (khi file không ghi)</th><th class="num">Giá 1 chiếc</th></tr>
      @foreach($nhom as $kh => $ds)
        @php
          $b64 = base64_encode($kh);
          $chon = (string) old("khop.$b64", $nho[$kh]->bien_the_id ?? '');
          if ($chon !== '' && !isset($bt[$chon])) $chon = '';
          $slTao = $ds->reject($boQua)->sum('sl');
        @endphp
        <tr class="khop" data-sl="{{ $slTao }}" data-chu="{{ $ds->filter(fn ($d) => $d['chu'] !== '')->count() }}">
          <td><b>{{ $ds[0]['ten'] }}</b>@if($ds[0]['co'] !== '')<div class="muted">{{ $ds[0]['co'] }}</div>@endif @if(isset($nho[$kh]) && $chon !== '')<div class="muted">đã nhớ từ lần trước</div>@endif</td>
          <td class="num">{{ $ds->count() }} · {{ $ds->sum('sl') }}</td>
          <td><select name="khop[{{ $b64 }}]" class="d-bt" required>@include('admin.pod._chon_phan_loai', ['chon' => $chon])</select></td>
          <td><select name="mau[{{ $b64 }}]" class="d-mau" data-old="{{ old("mau.$b64", $nho[$kh]->mau_sac ?? '') }}"><option value="">—</option></select></td>
          <td class="num d-gia muted">—</td>
        </tr>
      @endforeach
    </table></div>
    @endif
  </div>

  <div class="card">
    <div class="card-h"><div class="card-t">2 · Các đơn sẽ tạo</div><div class="card-hint">Mã đơn đã có đơn (chưa huỷ) của seller này thì bỏ qua — nhập lại cùng file không tạo trùng.</div></div>
    <div class="pad acts">
      <label style="display:flex;gap:6px;align-items:center"><input type="radio" name="gop" value="0" @checked(old('gop', '0') === '0') style="width:auto"> Mỗi mã đơn một đơn (Mã đơn sàn = mã trong file)</label>
      <label style="display:flex;gap:6px;align-items:center"><input type="radio" name="gop" value="1" @checked(old('gop') === '1') style="width:auto"> Gộp tất cả thành 1 đơn</label>
    </div>
    <div class="pl-cuon"><table>
      <tr><th>Mã đơn</th><th>Sản phẩm trong file</th><th class="num">SL</th><th>Chữ khắc</th><th>Màu</th><th>Ghi chú</th><th></th></tr>
      @foreach($dong as $d)
      <tr @if($boQua($d)) style="opacity:.5" @endif>
        <td><b>{{ $d['ma'] !== '' ? $d['ma'] : '—' }}</b></td>
        <td>{{ $d['ten'] }}@if($d['co'] !== '') <span class="muted">· {{ $d['co'] }}</span>@endif</td>
        <td class="num">{{ $d['sl'] }}</td>
        <td>{{ $d['chu'] !== '' ? $d['chu'] : '—' }}</td>
        <td>{{ $d['mau'] !== '' ? $d['mau'] : '—' }}</td>
        <td class="muted">{{ $d['ghi_chu'] }}</td>
        <td>@if($boQua($d))<span class="badge b-off">Đã có {{ $daCo[$d['ma']] }} · bỏ qua</span>@endif</td>
      </tr>
      @endforeach
    </table></div>
    <div class="pad muted" id="nTong" data-so-du="{{ $s->soDu() }}" data-tra-sau="{{ $s->traSau() ? 1 : 0 }}"></div>
  </div>

  <div class="card">
    <div class="card-h"><div class="card-t">3 · Giao tới</div><div class="card-hint">Áp cho mọi đơn tạo từ file này. Chưa có địa chỉ thì nhập tạm, sửa sau ở trang đơn (có tuỳ chọn áp cho mọi đơn cùng nơi giao).</div></div>
    <div class="pad grid" style="align-items:start">@include('admin.pod._noi_giao', ['chonSan' => $s->noi_giao_mac_dinh ?? $noiGiao->first()?->id])</div>
  </div>
  <div class="acts"><button class="btn" @disabled(!$s->hien || $mau->isEmpty())>Tạo đơn hộ {{ $s->ten }}</button><a class="btn-sm" href="{{ route('admin.pod.don.tao', ['seller' => $s->id]) }}">Huỷ, về trang tạo đơn</a></div>
</form>
<script>
/* Điền màu theo phân loại chọn, hiện giá 1 chiếc, tính tạm tổng tiền các đơn sẽ tạo (máy chủ tính lại). */
(function () {
  var BT = @json((object) $bt), f = document.getElementById('fNhap');
  if (!f) return;
  var tien = function (n) { return (Number(n) || 0).toLocaleString('vi-VN') + 'đ'; };
  function tinh() {
    var tong = 0, thieu = 0;
    f.querySelectorAll('tr.khop').forEach(function (r) {
      var b = BT[r.querySelector('.d-bt').value];
      if (!b) { thieu++; return; }
      tong += b.gia * (+r.dataset.sl) + (+r.dataset.chu ? b.phi * (+r.dataset.chu) : 0);
    });
    var o = document.getElementById('nTong'), du = +o.dataset.soDu;
    o.textContent = (thieu ? 'Còn ' + thieu + ' tên sản phẩm chưa chọn phân loại. ' : '') + 'Tổng tạm tính ' + tien(tong) + '. ' +
      (o.dataset.traSau === '1' ? 'Seller trả sau: mọi đơn vào hàng đợi in ngay.' : (du >= tong ? 'Ví seller đủ.' : 'Ví seller có ' + tien(Math.max(0, du)) + ': đơn nào không đủ tiền sẽ Chờ nạp tiền.'));
  }
  function doi(r, giuCu) {
    var b = BT[r.querySelector('.d-bt').value], m = r.querySelector('.d-mau'), cu = giuCu ? m.dataset.old : '';
    m.innerHTML = '';
    if (!b || !b.mau.length) { m.add(new Option(b ? 'Không chọn màu' : '—', '')); m.disabled = !b || !b.mau.length; }
    else { m.disabled = false; b.mau.forEach(function (v) { m.add(new Option(v, v)); }); if (cu && b.mau.indexOf(cu) >= 0) m.value = cu; }
    r.querySelector('.d-gia').textContent = b ? tien(b.gia) : '—';
  }
  f.querySelectorAll('tr.khop').forEach(function (r) {
    doi(r, true);
    r.querySelector('.d-bt').addEventListener('change', function () { doi(r, false); tinh(); });
  });
  tinh();
})();
</script>
@include('admin.pod._cuoi')
