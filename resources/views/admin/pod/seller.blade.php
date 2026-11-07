@include('admin.pod._dau', ['tieuDe' => 'Seller & ví'])
<div class="card">
  <div class="card-h"><div class="card-t">Cấp tài khoản seller</div><div class="card-hint">Xưởng cấp, seller không tự đăng ký. Gửi seller link {{ config('pod.url') }}, số điện thoại và mật khẩu.</div></div>
  <form method="POST" action="{{ route('admin.pod.seller.store') }}" class="pad">
    @csrf
    <div class="grid">
      <label class="f"><span>Tên seller / shop</span><input name="ten" value="{{ old('ten') }}" required maxlength="120"></label>
      <label class="f"><span>Số điện thoại (đăng nhập)</span><input name="sdt" value="{{ old('sdt') }}" required maxlength="20"></label>
      <label class="f"><span>Mật khẩu (≥ 6 ký tự)</span><input name="matkhau" required minlength="6" maxlength="100"></label>
      <label class="f"><span>Ghi chú</span><input name="ghi_chu" value="{{ old('ghi_chu') }}" maxlength="500"></label>
      <div><button class="btn">Tạo seller</button></div>
    </div>
  </form>
</div>

<div class="card">
  @php $tongNo = $soDu->filter(fn ($t) => $t < 0)->sum(); @endphp
  <div class="card-h"><div class="card-t">{{ $items->count() }} seller @if($tongNo < 0)<span class="muted" style="font-weight:600"> · seller đang nợ tổng <b style="color:#B91C1C">{{ number_format(-$tongNo, 0, ',', '.') }}đ</b></span>@endif</div><div class="card-hint">Seller chuyển khoản với nội dung "NAP &lt;mã&gt;". Thấy tiền về thì bấm Nạp — đơn Chờ nạp tiền tự vào hàng đợi in. Seller được "Trả sau" thì đơn vẫn chạy khi ví âm, tới hạn mức.</div></div>
  @if($items->isEmpty())
    <div class="empty">Chưa có seller nào.</div>
  @else
  <table>
    <tr><th>Mã</th><th>Seller</th><th class="num">Số dư</th><th>Chờ nạp</th><th>Điều khoản</th><th>Trạng thái</th><th></th></tr>
    @foreach($items as $s)
    @php $du = (int) ($soDu[$s->id] ?? 0); $cn = $choNap[$s->id] ?? null; @endphp
    <tr>
      <td><b>{{ $s->ma }}</b></td>
      <td><b>{{ $s->ten }}</b><div class="muted">{{ $s->sdt }}@if($s->ghi_chu) · {{ $s->ghi_chu }}@endif</div>
        @if($s->san_ban || $s->link_shop)<div class="muted">Bán trên: {{ $s->san_ban ?: '—' }}@if($s->link_shop) · <a href="{{ $s->link_shop }}" target="_blank" rel="noopener nofollow" style="color:var(--gd)">link shop</a>@endif</div>@endif
        @if($s->loi_nhan)<div class="muted" style="max-width:340px">"{{ $s->loi_nhan }}"</div>@endif
      </td>
      <td class="num"><b style="{{ $du < 0 ? 'color:#B91C1C' : '' }}">{{ number_format($du, 0, ',', '.') }}đ</b>
        @if($s->han_muc > 0 || $du < 0)<div class="muted" style="white-space:nowrap">@if($du < 0)Đang nợ {{ number_format(-$du, 0, ',', '.') }}đ<br>@endif
          @if($s->han_muc > 0)Trả sau {{ number_format($s->han_muc, 0, ',', '.') }}đ · còn {{ number_format(max(0, $du + $s->han_muc), 0, ',', '.') }}đ@endif</div>@endif
      </td>
      <td>@if($cn)<span class="badge b-cho_nap">{{ $cn->n }} đơn · {{ number_format($cn->t, 0, ',', '.') }}đ</span>@else<span class="muted">—</span>@endif</td>
      <td>@if($s->dong_y_luc)<span class="muted">Đồng ý {{ $s->dong_y_luc->format('d/m/Y') }}</span>@else<span class="muted">Chưa đăng nhập</span>@endif</td>
      <td>@if($s->cho_duyet)<span class="badge b-cho_nap">Chờ duyệt · {{ $s->created_at->format('d/m H:i') }}</span>@elseif($s->hien)<span class="badge b-on">Đang mở</span>@else<span class="badge b-off">Đã khoá</span>@endif</td>
      <td>
        @if($s->cho_duyet)
        <div class="acts">
          <form method="POST" action="{{ route('admin.pod.seller.duyet', $s) }}">@csrf<button class="btn-sm">Duyệt, mở tài khoản</button></form>
          <form method="POST" action="{{ route('admin.pod.seller.tuchoi', $s) }}" class="acts">@csrf
            <label style="font-size:11px;display:flex;gap:4px;align-items:center"><input type="checkbox" required> chắc chắn</label>
            <button class="btn-sm btn-del">Từ chối</button>
          </form>
        </div>
        @else
        <div class="acts">
          <details class="edit"><summary class="btn-sm">Nạp tiền</summary>
            <form method="POST" action="{{ route('admin.pod.seller.nap', $s) }}" class="edit-box">
              @csrf
              <label class="f"><span>Số tiền (đ)</span><input name="so_tien" type="text" inputmode="numeric" data-tien data-am autocomplete="off" placeholder="vd 500.000" required></label>
              <label class="f" style="margin-top:8px"><span>Loại</span><select name="loai"><option value="nap">Nạp tiền (đã thấy tiền về)</option><option value="dieu_chinh">Điều chỉnh (+/−, ghi lý do)</option></select></label>
              <label class="f" style="margin-top:8px"><span>Ghi chú</span><input name="ghi_chu" maxlength="300" placeholder="vd: CK MSB 28/10 lúc 9:15"></label>
              <button class="btn" style="margin-top:10px">Ghi vào ví</button>
            </form>
          </details>
          <details class="edit"><summary class="btn-sm">Trả sau</summary>
            <form method="POST" action="{{ route('admin.pod.seller.hanmuc', $s) }}" class="edit-box">
              @csrf
              <label class="f"><span>Hạn mức trả sau (đ, 0 = nạp trước)</span><input name="han_muc" type="text" inputmode="numeric" data-tien autocomplete="off" value="{{ number_format((int) $s->han_muc, 0, ',', '.') }}" required></label>
              <div class="muted" style="margin-top:6px;max-width:260px">Đơn vẫn vào máy in khi ví âm, tới đúng mức này. Seller chuyển khoản "NAP {{ $s->ma }}" để trả nợ, bấm Nạp tiền như thường.</div>
              <button class="btn" style="margin-top:10px">Lưu hạn mức</button>
            </form>
          </details>
          <a class="btn-sm" href="{{ route('admin.pod.seller.vi', $s) }}">Sổ ví</a>
          <details class="edit"><summary class="btn-sm">Sửa</summary>
            <form method="POST" action="{{ route('admin.pod.seller.update', $s) }}" class="edit-box">
              @csrf @method('PUT')
              <label class="f"><span>Tên</span><input name="ten" value="{{ $s->ten }}" required></label>
              <label class="f" style="margin-top:8px"><span>Số điện thoại</span><input name="sdt" value="{{ $s->sdt }}" required></label>
              <label class="f" style="margin-top:8px"><span>Mật khẩu mới (bỏ trống = giữ)</span><input name="matkhau" minlength="6"></label>
              <label class="f" style="margin-top:8px"><span>Ghi chú</span><input name="ghi_chu" value="{{ $s->ghi_chu }}"></label>
              <button class="btn" style="margin-top:10px">Lưu</button>
            </form>
          </details>
          <form method="POST" action="{{ route('admin.pod.seller.khoa', $s) }}">@csrf<button class="btn-sm {{ $s->hien ? 'btn-warn' : '' }}">{{ $s->hien ? 'Khoá' : 'Mở' }}</button></form>
        </div>
        @endif
      </td>
    </tr>
    @endforeach
  </table>
  @endif
</div>
<script>
/* Ô tiền: gõ tới đâu chấm nghìn tới đó (10.000.000), giữ vị trí con trỏ; máy chủ bỏ dấu chấm trước khi kiểm. */
(function () {
  function dinh(inp) {
    var v = inp.value, c = inp.selectionStart == null ? v.length : inp.selectionStart;
    var am = inp.hasAttribute('data-am') && /^\s*[-−]/.test(v);
    var soTruoc = v.slice(0, c).replace(/\D/g, '').length;
    var so = v.replace(/\D/g, '').replace(/^0+(?=\d)/, '');
    var ra = (am ? '-' : '') + so.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    inp.value = ra;
    var p = am ? 1 : 0, dem = 0;
    while (p < ra.length && dem < soTruoc) { if (/\d/.test(ra[p])) dem++; p++; }
    if (document.activeElement === inp) inp.setSelectionRange(p, p);
  }
  document.querySelectorAll('input[data-tien]').forEach(function (inp) {
    inp.addEventListener('input', function () { dinh(inp); });
    dinh(inp);
  });
})();
</script>
@include('admin.pod._cuoi')
