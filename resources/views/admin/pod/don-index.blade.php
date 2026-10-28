@include('admin.pod._dau', ['tieuDe' => 'Đơn POD'])
@php
  $tabs = ['can_lam' => 'Cần làm (' . (($dem['cho_in'] ?? 0) + ($dem['dang_in'] ?? 0)) . ')'];
  foreach (\App\Models\PodDon::TRANG_THAI as $k => $ten) $tabs[$k] = $ten . ' (' . ($dem[$k] ?? 0) . ')';
  $tabs['tat_ca'] = 'Tất cả';
@endphp
<div class="card"><div class="pad acts">
  @foreach($tabs as $k => $ten)<a class="btn-sm {{ $tt === $k ? 'btn-warn' : '' }}" href="{{ route('admin.pod.don.index', ['tt' => $k]) }}">{{ $ten }}</a>@endforeach
</div></div>
<div class="card">
  @if($tt === 'can_lam')<div class="card-h"><div class="card-hint">Hàng đợi in: đơn đã trừ ví, cũ nhất trước. Đơn Chờ nạp tiền chưa in (seller chưa đủ tiền).</div></div>@endif
  @if($items->isEmpty())
    <div class="empty">Không có đơn nào.</div>
  @else
  <table>
    <tr><th>Đơn</th><th>Seller</th><th>Nội dung</th><th class="num">Tiền</th><th>Trạng thái</th><th></th></tr>
    @foreach($items as $d)
    <tr>
      <td><b>{{ $d->ma }}</b><div class="muted">{{ optional($d->created_at)->format('d/m H:i') }}@if($d->ma_don_san) · {{ $d->ma_don_san }}@endif</div></td>
      <td>{{ optional($d->seller)->ten }}<div class="muted">{{ optional($d->seller)->ma }}</div></td>
      <td>
        @foreach($d->dong as $x)
          <div>{{ $x->ten_mau }} · {{ $x->ten_bien_the }}@if($x->chat_lieu) · <b>{{ $x->chat_lieu }}</b>@endif @if($x->mau_sac) · {{ $x->mau_sac }}@endif × <b>{{ $x->so_luong }}</b>@if($x->chu_ca_nhan) <span class="muted">"{{ $x->chu_ca_nhan }}"</span>@endif</div>
        @endforeach
      </td>
      <td class="num">{{ number_format($d->tong_tien, 0, ',', '.') }}đ</td>
      <td><span class="badge b-{{ $d->trang_thai }}">{{ $d->tenTrangThai() }}</span></td>
      <td><a class="btn-sm" href="{{ route('admin.pod.don.show', $d) }}">Xem</a></td>
    </tr>
    @endforeach
  </table>
  @endif
</div>
@include('admin.pod._cuoi')
