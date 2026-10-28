@include('admin.pod._dau', ['tieuDe' => 'Sổ ví ' . $seller->ten])
<div class="card">
  <div class="card-h"><div class="card-t">{{ $seller->ten }} ({{ $seller->ma }}) — số dư {{ number_format($soDu, 0, ',', '.') }}đ</div><div class="card-hint">Sổ chỉ thêm dòng, không sửa. Sai thì dùng Điều chỉnh ở trang Seller & ví.</div></div>
  @if($dong->isEmpty())
    <div class="empty">Chưa có giao dịch.</div>
  @else
  <table>
    <tr><th>Lúc</th><th>Loại</th><th class="num">Số tiền</th><th>Ghi chú</th><th>Người tạo</th></tr>
    @foreach($dong as $d)
    <tr>
      <td class="muted">{{ optional($d->created_at)->format('d/m/Y H:i') }}</td>
      <td>{{ \App\Models\PodVi::LOAI[$d->loai] ?? $d->loai }}</td>
      <td class="num" style="color:{{ $d->so_tien < 0 ? '#B91C1C' : '#3E7A0A' }};font-weight:800">{{ $d->so_tien > 0 ? '+' : '' }}{{ number_format($d->so_tien, 0, ',', '.') }}đ</td>
      <td>{{ $d->ghi_chu }}</td>
      <td class="muted">{{ $d->nguoi_tao }}</td>
    </tr>
    @endforeach
  </table>
  <div class="pad">{{ $dong->links('pagination::simple-default') }}</div>
  @endif
</div>
@include('admin.pod._cuoi')
