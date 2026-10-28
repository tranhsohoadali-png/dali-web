@include('admin.pod._dau', ['tieuDe' => 'Nhật ký mẫu riêng'])
<div class="card">
  <div class="card-h"><div class="card-t">Ai làm gì với mẫu POD</div><div class="card-hint">Ghi khi admin mở, sửa, đổi chủ hoặc chuyển mẫu riêng sang Kho chung.</div></div>
  @if($dong->isEmpty())
    <div class="empty">Chưa có dòng nào.</div>
  @else
  <table>
    <tr><th>Lúc</th><th>Mẫu</th><th>Hành động</th><th>Chi tiết</th><th>IP</th></tr>
    @foreach($dong as $d)
    @php $m = $mau[$d->mau_id] ?? null; @endphp
    <tr>
      <td class="muted">{{ optional($d->created_at)->format('d/m/Y H:i') }}</td>
      <td>@if($m)<a href="{{ route('admin.pod.mau.edit', $m->id) }}" style="color:var(--gd);font-weight:700">{{ $m->ten }}</a><div class="muted">{{ $m->ma }}</div>@else<span class="muted">—</span>@endif</td>
      <td><b>{{ ['xem' => 'Mở xem', 'tao' => 'Tạo', 'sua' => 'Sửa', 'doi_chu' => 'Đổi chủ', 'chuyen_kho_chung' => 'Chuyển sang Kho chung'][$d->hanh_dong] ?? $d->hanh_dong }}</b></td>
      <td>{{ $d->chi_tiet }}</td>
      <td class="muted">{{ $d->ip }}</td>
    </tr>
    @endforeach
  </table>
  <div class="pad">{{ $dong->links('pagination::simple-default') }}</div>
  @endif
</div>
@include('admin.pod._cuoi')
