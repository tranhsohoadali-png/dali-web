@include('admin.pod._dau', ['tieuDe' => 'Tạo đơn hộ seller'])
@php $tien = fn ($x) => number_format($x, 0, ',', '.') . 'đ'; @endphp
<div class="card">
  <div class="card-h"><div class="card-t">1 · Chọn seller</div><div class="card-hint">Xưởng tạo đơn thay seller (seller gửi đơn qua Zalo…). Chỉ chọn được mẫu seller đó thấy; chữ, màu, giá kiểm như seller tự đặt; tiền trừ ví seller, thiếu thì đơn Chờ nạp tiền. Cổng seller ghi "Xưởng tạo hộ".</div></div>
  <form method="GET" action="{{ route('admin.pod.don.tao') }}" class="pad acts">
    <select name="seller" onchange="this.form.submit()" style="min-width:280px;border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;background:var(--gll)">
      <option value="">— Chọn seller —</option>
      @foreach($sellers as $x)<option value="{{ $x->id }}" @selected($s && $s->id === $x->id)>{{ $x->ten }} ({{ $x->ma }}){{ $x->hien ? '' : ' · đã khoá' }}</option>@endforeach
    </select>
    @if($s)
      @php $du = $s->soDu(); @endphp
      <span class="muted">Ví <b style="{{ $du < 0 ? 'color:#B91C1C' : '' }}">{{ $tien($du) }}</b>@if($du < 0) (đang nợ)@endif</span>
      @if($s->traSau())<span class="badge b-chung">Trả sau · không giới hạn</span>@endif
      @if(!$s->hien)<span class="badge b-off">Seller đang bị khoá</span>@endif
    @endif
  </form>
</div>

@if($s)
<form method="POST" action="{{ route('admin.pod.don.taoluu') }}" enctype="multipart/form-data" id="fTao" data-so-du="{{ $du }}" data-tra-sau="{{ $s->traSau() ? 1 : 0 }}">
  @csrf
  <input type="hidden" name="seller_id" value="{{ $s->id }}">
  <div class="card">
    <div class="card-h"><div class="card-t">2 · Dòng đơn</div><div class="card-hint">{{ $mau->count() }} mẫu seller này thấy (Kho chung + mẫu riêng của seller). Dòng bỏ trống phân loại thì bỏ qua.</div></div>
    @if($mau->isEmpty())
      <div class="empty">Seller này chưa thấy mẫu nào.</div>
    @else
    <div class="pl-cuon"><table>
      <tr><th style="min-width:320px">Mẫu · phân loại</th><th style="width:80px">SL</th><th style="width:150px">Màu</th><th style="min-width:200px">Chữ khắc</th><th style="width:150px">Font</th><th class="num" style="width:110px">Thành tiền</th></tr>
      @for($i = 0; $i < 8; $i++)
      <tr class="dong-tao" data-i="{{ $i }}">
        <td><select name="dong[{{ $i }}][bien_the_id]" class="d-bt">
          <option value="">—</option>
          @foreach($mau as $m)
          <optgroup label="{{ $m->ten }}{{ $m->laRieng() ? ' (mẫu riêng)' : '' }}">
            @foreach($m->bienThe as $b)
              @continue($b->laCu())
              <option value="{{ $b->id }}" @selected((string) old("dong.$i.bien_the_id") === (string) $b->id)>{{ $m->ten }} · {{ trim(($m->tenKieu($b->kieu) ? $m->tenKieu($b->kieu) . ' · ' : '') . $b->ten) }} · {{ \App\Services\PodSo::chatLieu($b->chat_lieu) }} — {{ $tien($bt[$b->id]['gia'] ?? 0) }}</option>
            @endforeach
          </optgroup>
          @endforeach
        </select></td>
        <td><input name="dong[{{ $i }}][so_luong]" class="d-sl" type="number" min="1" max="{{ \App\Services\PodSo::SL_TOI_DA }}" value="{{ old("dong.$i.so_luong", 1) }}"></td>
        <td><select name="dong[{{ $i }}][mau_sac]" class="d-mau" data-old="{{ old("dong.$i.mau_sac") }}"><option value="">—</option></select></td>
        <td><input name="dong[{{ $i }}][chu]" class="d-chu" maxlength="300" value="{{ old("dong.$i.chu") }}" placeholder="—"></td>
        <td><select name="dong[{{ $i }}][font]" class="d-font" data-old="{{ old("dong.$i.font") }}"><option value="">—</option></select></td>
        <td class="num d-tien muted">—</td>
      </tr>
      @endfor
      <tr><td colspan="5" class="num"><b>Tổng tạm tính</b></td><td class="num"><b id="tTong">0đ</b></td></tr>
    </table></div>
    <div class="pad muted" id="tVi"></div>
    @endif
  </div>

  <div class="card">
    <div class="card-h"><div class="card-t">3 · Giao tới + thông tin đơn</div></div>
    <div class="pad grid" style="align-items:start">
      <label class="f rong"><span>Nơi giao</span>
        <select name="noi_giao_id" id="ngChon">
          @foreach($noiGiao as $ng)<option value="{{ $ng->id }}" @selected((string) old('noi_giao_id', $s->noi_giao_mac_dinh ?? $noiGiao->first()?->id) === (string) $ng->id)>{{ $ng->ten }} — {{ $ng->dia_chi }}{{ (int) $s->noi_giao_mac_dinh === $ng->id ? ' (mặc định)' : '' }}</option>@endforeach
          <option value="moi" @selected(old('noi_giao_id') === 'moi' || $noiGiao->isEmpty())>+ Nhập nơi giao khác (chỉ cho đơn này)</option>
        </select>
      </label>
      <div class="f rong" id="ngMoi">
        <div class="grid">
          <label class="f"><span>Tên nơi giao / người nhận</span><input name="ng_ten" value="{{ old('ng_ten') }}" maxlength="120"></label>
          <label class="f"><span>Số điện thoại</span><input name="ng_sdt" value="{{ old('ng_sdt') }}" maxlength="30"></label>
          <label class="f rong"><span>Địa chỉ</span><input name="ng_dia_chi" value="{{ old('ng_dia_chi') }}" maxlength="500"></label>
          <label class="f rong"><span>Ghi chú giao</span><input name="ng_ghi_chu" value="{{ old('ng_ghi_chu') }}" maxlength="300"></label>
        </div>
      </div>
      <label class="f"><span>Mã đơn Etsy / Amazon (đối chiếu)</span><input name="ma_don_san" value="{{ old('ma_don_san') }}" maxlength="60"></label>
      <label class="f"><span>Nhãn line bay (nếu có)</span><input type="file" name="nhan" accept="image/*,application/pdf"></label>
      <label class="f rong"><span>Ghi chú (seller thấy)</span><textarea name="ghi_chu" rows="2" maxlength="1000">{{ old('ghi_chu') }}</textarea></label>
    </div>
  </div>
  <div class="acts"><button class="btn" @disabled($mau->isEmpty() || !$s->hien)>Tạo đơn hộ {{ $s->ten }}</button><a class="btn-sm" href="{{ route('admin.pod.don.index') }}">Về danh sách đơn</a></div>
</form>
<script>
/* Điền màu / font / gợi ý chữ theo phân loại chọn, tính tạm thành tiền (máy chủ tính lại khi tạo đơn). */
(function () {
  var BT = @json((object) $bt), f = document.getElementById('fTao');
  if (!f) return;
  var tien = function (n) { return (Number(n) || 0).toLocaleString('vi-VN') + 'đ'; };
  function dien(sel, ds, cu, trong) {
    sel.innerHTML = '';
    if (!ds.length) { sel.add(new Option(trong, '')); sel.disabled = true; return; }
    sel.disabled = false;
    ds.forEach(function (v) { sel.add(new Option(v, v)); });
    if (cu && ds.indexOf(cu) >= 0) sel.value = cu;
  }
  function tinh() {
    var tong = 0;
    f.querySelectorAll('.dong-tao').forEach(function (r) {
      var b = BT[r.querySelector('.d-bt').value], o = r.querySelector('.d-tien');
      if (!b) { o.textContent = '—'; return; }
      var sl = Math.max(1, parseInt(r.querySelector('.d-sl').value, 10) || 1);
      var t = b.gia * sl + (r.querySelector('.d-chu').value.trim() ? b.phi : 0);
      o.textContent = tien(t); tong += t;
    });
    document.getElementById('tTong').textContent = tien(tong);
    var du = +f.dataset.soDu, vi = document.getElementById('tVi');
    if (vi) vi.textContent = !tong ? '' : f.dataset.traSau === '1'
      ? 'Seller trả sau: đơn vào hàng đợi in ngay' + (du < tong ? ', ví âm thành nợ ' + tien(tong - du) + '.' : '.')
      : (du >= tong ? 'Ví seller đủ: đơn vào hàng đợi in ngay.' : 'Ví seller có ' + tien(Math.max(0, du)) + ': đơn sẽ Chờ nạp tiền, tự vào sản xuất khi seller nạp đủ.');
  }
  function doi(r, giuCu) {
    var b = BT[r.querySelector('.d-bt').value], m = r.querySelector('.d-mau'), fo = r.querySelector('.d-font'), c = r.querySelector('.d-chu');
    dien(m, b ? b.mau : [], giuCu ? m.dataset.old : '', b ? 'Không chọn màu' : '—');
    dien(fo, b ? b.font : [], giuCu ? fo.dataset.old : '', b ? 'Mặc định' : '—');
    c.placeholder = b ? b.goi_y : '—';
  }
  f.querySelectorAll('.dong-tao').forEach(function (r) {
    doi(r, true);
    r.querySelector('.d-bt').addEventListener('change', function () { doi(r, false); tinh(); });
    r.querySelector('.d-sl').addEventListener('input', tinh);
    r.querySelector('.d-chu').addEventListener('input', tinh);
  });
  var ng = document.getElementById('ngChon'), moi = document.getElementById('ngMoi');
  function ngHien() { moi.style.display = ng.value === 'moi' ? '' : 'none'; }
  ng.addEventListener('change', ngHien); ngHien();
  tinh();
})();
</script>
@endif
@include('admin.pod._cuoi')
