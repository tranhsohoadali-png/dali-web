@include('admin.pod._dau', ['tieuDe' => 'Thợ in'])
<div class="card">
  <div class="card-h"><div class="card-t">Cấp tài khoản thợ in</div><div class="card-hint">Thợ in đăng nhập ở {{ config('pod.url') }}/tho-in: chỉ thấy hàng đợi in, file in, nhãn, nơi giao. Không thấy tiền, ví seller, kho mẫu, không huỷ được đơn, không vào được admin.</div></div>
  <form method="POST" action="{{ route('admin.pod.thoin.store') }}" class="pad">
    @csrf
    <div class="grid">
      <label class="f"><span>Tên thợ</span><input name="ten" value="{{ old('ten') }}" required maxlength="120"></label>
      <label class="f"><span>Số điện thoại (đăng nhập)</span><input name="sdt" value="{{ old('sdt') }}" required maxlength="20"></label>
      <label class="f"><span>Mật khẩu (≥ 6 ký tự)</span><input name="matkhau" required minlength="6" maxlength="100"></label>
      <div><button class="btn">Tạo thợ in</button></div>
    </div>
  </form>
</div>
<div class="card">
  @if($items->isEmpty())
    <div class="empty">Chưa có thợ in nào.</div>
  @else
  <table>
    <tr><th>Thợ</th><th>Đăng nhập gần nhất</th><th>Trạng thái</th><th></th></tr>
    @foreach($items as $t)
    <tr>
      <td><b>{{ $t->ten }}</b><div class="muted">{{ $t->sdt }}</div></td>
      <td class="muted">{{ $t->dang_nhap_luc ? $t->dang_nhap_luc->format('d/m/Y H:i') : 'Chưa' }}</td>
      <td>@if($t->hien)<span class="badge b-on">Đang mở</span>@else<span class="badge b-off">Đã khoá</span>@endif</td>
      <td>
        <div class="acts">
          <details class="edit"><summary class="btn-sm">Sửa</summary>
            <form method="POST" action="{{ route('admin.pod.thoin.update', $t) }}" class="edit-box">
              @csrf @method('PUT')
              <label class="f"><span>Tên</span><input name="ten" value="{{ $t->ten }}" required></label>
              <label class="f" style="margin-top:8px"><span>Mật khẩu mới (bỏ trống = giữ)</span><input name="matkhau" minlength="6"></label>
              <button class="btn" style="margin-top:10px">Lưu</button>
            </form>
          </details>
          <form method="POST" action="{{ route('admin.pod.thoin.khoa', $t) }}">@csrf<button class="btn-sm {{ $t->hien ? 'btn-warn' : '' }}">{{ $t->hien ? 'Khoá' : 'Mở' }}</button></form>
        </div>
      </td>
    </tr>
    @endforeach
  </table>
  @endif
</div>
@include('admin.pod._cuoi')
