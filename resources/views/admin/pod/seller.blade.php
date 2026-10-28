@include('admin.pod._dau', ['tieuDe' => 'Seller & ví'])
<div class="card">
  <div class="card-h"><div class="card-t">Cấp tài khoản seller</div><div class="card-hint">Xưởng cấp, seller không tự đăng ký. Gửi seller link {{ url('/pod') }}, số điện thoại và mật khẩu.</div></div>
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
  <div class="card-h"><div class="card-t">{{ $items->count() }} seller</div><div class="card-hint">Seller chuyển khoản với nội dung "NAP &lt;mã&gt;". Thấy tiền về thì bấm Nạp — đơn Chờ nạp tiền tự vào hàng đợi in.</div></div>
  @if($items->isEmpty())
    <div class="empty">Chưa có seller nào.</div>
  @else
  <table>
    <tr><th>Mã</th><th>Seller</th><th class="num">Số dư</th><th>Chờ nạp</th><th>Điều khoản</th><th>Trạng thái</th><th></th></tr>
    @foreach($items as $s)
    @php $du = (int) ($soDu[$s->id] ?? 0); $cn = $choNap[$s->id] ?? null; @endphp
    <tr>
      <td><b>{{ $s->ma }}</b></td>
      <td><b>{{ $s->ten }}</b><div class="muted">{{ $s->sdt }}@if($s->ghi_chu) · {{ $s->ghi_chu }}@endif</div></td>
      <td class="num"><b>{{ number_format($du, 0, ',', '.') }}đ</b></td>
      <td>@if($cn)<span class="badge b-cho_nap">{{ $cn->n }} đơn · {{ number_format($cn->t, 0, ',', '.') }}đ</span>@else<span class="muted">—</span>@endif</td>
      <td>@if($s->dong_y_luc)<span class="muted">Đồng ý {{ $s->dong_y_luc->format('d/m/Y') }}</span>@else<span class="muted">Chưa đăng nhập</span>@endif</td>
      <td>@if($s->hien)<span class="badge b-on">Đang mở</span>@else<span class="badge b-off">Đã khoá</span>@endif</td>
      <td>
        <div class="acts">
          <details class="edit"><summary class="btn-sm">Nạp tiền</summary>
            <form method="POST" action="{{ route('admin.pod.seller.nap', $s) }}" class="edit-box">
              @csrf
              <label class="f"><span>Số tiền (đ)</span><input name="so_tien" type="number" step="1000" required></label>
              <label class="f" style="margin-top:8px"><span>Loại</span><select name="loai"><option value="nap">Nạp tiền (đã thấy tiền về)</option><option value="dieu_chinh">Điều chỉnh (+/−, ghi lý do)</option></select></label>
              <label class="f" style="margin-top:8px"><span>Ghi chú</span><input name="ghi_chu" maxlength="300" placeholder="vd: CK MSB 28/10 lúc 9:15"></label>
              <button class="btn" style="margin-top:10px">Ghi vào ví</button>
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
      </td>
    </tr>
    @endforeach
  </table>
  @endif
</div>
@include('admin.pod._cuoi')
