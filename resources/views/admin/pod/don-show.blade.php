@include('admin.pod._dau', ['tieuDe' => 'Đơn ' . $don->ma])
<div class="card">
  <div class="card-h"><div class="card-t">{{ $don->ma }} · <span class="badge b-{{ $don->trang_thai }}">{{ $don->tenTrangThai() }}</span></div>
    <div class="card-hint">Đặt {{ optional($don->created_at)->format('d/m/Y H:i') }} · {{ $don->da_tru ? 'Đã trừ ví' : 'Chưa trừ ví' }}@if($don->huy_luc) · Huỷ {{ $don->huy_luc->format('d/m/Y H:i') }}@endif @if($don->giao_luc) · Giao {{ $don->giao_luc->format('d/m/Y H:i') }}@endif @if($don->nguoi_in) · In: {{ $don->nguoi_in }}@endif @if($don->nguoi_giao) · Giao bởi: {{ $don->nguoi_giao }}@endif</div></div>
  <div class="pad grid" style="align-items:start">
    <div><div class="muted">Seller</div><b>{{ optional($don->seller)->ten }}</b> ({{ optional($don->seller)->ma }})<div class="muted">{{ optional($don->seller)->sdt }}</div></div>
    <div><div class="muted">Giao tới</div>@if($don->noi_giao)<b>{{ $don->noi_giao['ten'] ?? '' }}</b><div>{{ $don->noi_giao['dia_chi'] ?? '' }}</div><div class="muted">{{ $don->noi_giao['sdt'] ?? '' }} {{ $don->noi_giao['ghi_chu'] ?? '' }}</div>@else<span class="muted">—</span>@endif</div>
    <div><div class="muted">Mã đơn sàn</div><b>{{ $don->ma_don_san ?: '—' }}</b>@if($don->ma_van_don)<div class="muted" style="margin-top:6px">Mã vận đơn</div><b>{{ $don->ma_van_don }}</b>@endif</div>
    <div><div class="muted">Nhãn line bay</div>@if($coNhan)<a class="btn-sm" href="{{ route('admin.pod.don.nhan', $don) }}">Tải nhãn</a>@else<span class="muted">Không có</span>@endif</div>
    @if($don->ghi_chu)<div class="f rong"><div class="muted">Ghi chú của seller</div>{{ $don->ghi_chu }}</div>@endif
  </div>
</div>
<div class="card">
  <table>
    <tr><th>Mẫu · phân loại</th><th>Màu</th><th class="num">SL</th><th>Chữ cá nhân hoá</th><th>File in</th><th class="num">Thành tiền</th></tr>
    @foreach($don->dong as $x)
    <tr>
      <td><b>{{ $x->ten_mau }}</b><div class="muted">{{ $x->ten_bien_the }}@if($x->chat_lieu) · <b>{{ $x->chat_lieu }}</b>@endif · {{ rtrim(rtrim(number_format($x->gram, 1, ',', '.'), '0'), ',') }} g × {{ number_format($x->don_gia_gram, 0, ',', '.') }}đ</div></td>
      <td>{{ $x->mau_sac ?: '—' }}</td>
      <td class="num"><b>{{ $x->so_luong }}</b></td>
      <td>{{ $x->chu_ca_nhan ?: '—' }}@if($x->phi_ca_nhan)<div class="muted">+{{ number_format($x->phi_ca_nhan, 0, ',', '.') }}đ</div>@endif</td>
      <td class="muted" style="word-break:break-all">{{ $fileIn[$x->bien_the_id] ?? '—' }}</td>
      <td class="num">{{ number_format($x->thanh_tien, 0, ',', '.') }}đ</td>
    </tr>
    @endforeach
    <tr><td colspan="5" class="num"><b>Tổng</b></td><td class="num"><b>{{ number_format($don->tong_tien, 0, ',', '.') }}đ</b></td></tr>
  </table>
</div>
@if(in_array($don->trang_thai, ['cho_nap', 'cho_in', 'dang_in'], true))
<div class="card"><div class="pad acts" style="align-items:flex-end">
  @if($don->trang_thai === 'cho_in')
    <form method="POST" action="{{ route('admin.pod.don.status', $don) }}">@csrf<input type="hidden" name="trang_thai" value="dang_in"><button class="btn">Bắt đầu in</button></form>
  @endif
  @if(in_array($don->trang_thai, ['cho_in', 'dang_in'], true))
    <form method="POST" action="{{ route('admin.pod.don.status', $don) }}" class="acts" style="align-items:flex-end">@csrf
      <input type="hidden" name="trang_thai" value="da_giao">
      <label class="f" style="width:200px"><span>Mã vận đơn trong nước</span><input name="ma_van_don" value="{{ $don->ma_van_don }}" maxlength="60"></label>
      <button class="btn">Đã giao</button>
    </form>
  @endif
  <form method="POST" action="{{ route('admin.pod.don.huy', $don) }}" class="acts" style="margin-left:auto">@csrf
    <label style="font-size:12px;display:flex;gap:6px;align-items:center"><input type="checkbox" required> Chắc chắn huỷ{{ $don->da_tru ? ', hoàn tiền vào ví' : '' }}</label>
    <button class="btn-sm btn-del">Huỷ đơn</button>
  </form>
</div></div>
@endif
<a class="btn-sm" href="{{ route('admin.pod.don.index') }}">Về hàng đợi</a>
@include('admin.pod._cuoi')
