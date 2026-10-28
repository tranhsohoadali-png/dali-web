@include('admin.pod._dau', ['tieuDe' => 'Mẫu POD'])
<div class="card">
  <div class="pad" style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;justify-content:space-between">
    <form method="GET" class="acts">
      <a class="btn-sm {{ $kho === '' ? 'btn-warn' : '' }}" href="{{ route('admin.pod.mau.index') }}">Tất cả</a>
      <a class="btn-sm {{ $kho === 'chung' ? 'btn-warn' : '' }}" href="{{ route('admin.pod.mau.index', ['kho' => 'chung']) }}">Kho chung</a>
      <a class="btn-sm {{ $kho === 'rieng' ? 'btn-warn' : '' }}" href="{{ route('admin.pod.mau.index', ['kho' => 'rieng']) }}">Mọi kho riêng</a>
      <select name="kho" onchange="this.form.submit()" style="border:1.5px solid var(--bd);border-radius:7px;padding:5px 8px;font-size:12px;background:var(--gll)">
        <option value="">Kho riêng của…</option>
        @foreach($sellers as $s)<option value="{{ $s->id }}" @selected($kho === (string) $s->id)>{{ $s->ten }} ({{ $s->ma }})</option>@endforeach
      </select>
    </form>
    <form method="POST" action="{{ route('admin.pod.giagram') }}" class="acts">
      @csrf
      <label class="f" style="width:150px"><span>Đơn giá (đ/gram)</span><input name="gia_gram" type="number" value="{{ $giaGram }}" min="100" required></label>
      <button class="btn-sm">Lưu giá</button>
    </form>
    <a class="btn" href="{{ route('admin.pod.mau.create') }}">+ Thêm mẫu</a>
  </div>
</div>
<div class="card">
  @if($items->isEmpty())
    <div class="empty">Chưa có mẫu nào ở đây.</div>
  @else
  <table>
    <tr><th></th><th>Mẫu</th><th>Kho</th><th>Phân loại · giá in 1 cái</th><th>Hiện</th><th></th></tr>
    @foreach($items as $m)
    <tr>
      <td>@if(!empty($m->anh))<img class="thumb" src="{{ route('admin.pod.mau.anh', [$m, 0]) }}" alt="" loading="lazy">@endif</td>
      <td><b>{{ $m->ten }}</b><div class="muted">{{ $m->ma }}@if($m->nhom) · {{ $m->nhom }}@endif</div></td>
      <td>@if($m->chu)<span class="badge b-rieng">Riêng · {{ $m->chu->ten }}</span>@else<span class="badge b-chung">Kho chung</span>@endif</td>
      <td>
        @forelse($m->bienThe as $b)
          <div>{{ $b->ten }} — {{ rtrim(rtrim(number_format($b->gram, 1, ',', '.'), '0'), ',') }} g = <b>{{ number_format(\App\Services\PodSo::giaDong($b->gram, 1, $giaGram, 0, false), 0, ',', '.') }}đ</b></div>
        @empty
          <span class="muted">Chưa có phân loại, seller chưa đặt được</span>
        @endforelse
      </td>
      <td>@if($m->hien)<span class="badge b-on">Hiện</span>@else<span class="badge b-off">Ẩn</span>@endif</td>
      <td><a class="btn-sm" href="{{ route('admin.pod.mau.edit', $m) }}">Sửa</a></td>
    </tr>
    @endforeach
  </table>
  @endif
</div>
@include('admin.pod._cuoi')
