@include('admin.pod._dau', ['tieuDe' => $seller ? 'Đơn của ' . $seller->ten : 'Đơn POD'])
@php
  $tabs = ['can_lam' => 'Cần làm (' . (($dem['cho_in'] ?? 0) + ($dem['dang_in'] ?? 0)) . ')'];
  foreach (\App\Models\PodDon::TRANG_THAI as $k => $ten) $tabs[$k] = $ten . ' (' . ($dem[$k] ?? 0) . ')';
  $tabs['tat_ca'] = 'Tất cả (' . $dem->sum() . ')';
  $giu = array_filter(['seller' => $seller?->id, 'thang' => $thang, 'q' => $tim]);   // giữ bộ lọc khi đổi tab
  $tien = fn ($x) => number_format($x, 0, ',', '.') . 'đ';
@endphp
<div class="card"><div class="pad">
  <form method="GET" action="{{ route('admin.pod.don.index') }}" class="acts" style="margin-bottom:10px">
    <input type="hidden" name="tt" value="{{ $tt }}">
    <select name="seller" onchange="this.form.submit()" style="min-width:220px;border:1.5px solid var(--bd);border-radius:9px;padding:7px 10px;font-size:13px;background:var(--gll)">
      <option value="">Mọi seller</option>
      @foreach($sellers as $x)<option value="{{ $x->id }}" @selected($seller && $seller->id === $x->id)>{{ $x->ten }} ({{ $x->ma }})</option>@endforeach
    </select>
    <select name="thang" onchange="this.form.submit()" style="border:1.5px solid var(--bd);border-radius:9px;padding:7px 10px;font-size:13px;background:var(--gll)">
      <option value="">Mọi tháng</option>
      @foreach($cacThang as $t)<option value="{{ $t }}" @selected($thang === $t)>Tháng {{ substr($t, 5, 2) }}/{{ substr($t, 0, 4) }}</option>@endforeach
    </select>
    <input name="q" value="{{ $tim }}" maxlength="60" placeholder="Tìm mã đơn, mã sàn, tên mẫu, chữ khắc" style="min-width:240px;border:1.5px solid var(--bd);border-radius:9px;padding:7px 10px;font-size:13px;background:var(--gll)">
    <button class="btn-sm">Lọc</button>
    @if($giu)<a class="btn-sm" href="{{ route('admin.pod.don.index') }}">Bỏ lọc</a>@endif
    <a class="btn" href="{{ route('admin.pod.don.tao', $seller ? ['seller' => $seller->id] : []) }}" style="margin-left:auto">+ Tạo đơn hộ{{ $seller ? ' ' . $seller->ten : ' seller' }}</a>
  </form>
  <div class="acts">
    @foreach($tabs as $k => $ten)<a class="btn-sm {{ $tt === $k ? 'btn-warn' : '' }}" href="{{ route('admin.pod.don.index', ['tt' => $k] + $giu) }}">{{ $ten }}</a>@endforeach
  </div>
</div></div>

@if($seller && $tong)
<div class="card">
  <div class="card-h"><div class="card-t">Lịch sử đơn · {{ $seller->ten }} ({{ $seller->ma }}){{ $thang ? ' · tháng ' . substr($thang, 5, 2) . '/' . substr($thang, 0, 4) : '' }}</div>
    <div class="card-hint">Không tính đơn đã huỷ.@if($tong['dau']) Đơn đầu {{ \Illuminate\Support\Carbon::parse($tong['dau'])->format('d/m/Y') }}, gần nhất {{ \Illuminate\Support\Carbon::parse($tong['cuoi'])->format('d/m/Y') }}.@endif
      <a href="{{ route('admin.pod.seller.vi', $seller) }}" style="color:var(--gd)">Sổ ví</a></div></div>
  <div class="pad grid" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr))">
    <div><div class="muted">Số đơn</div><b style="font-size:20px">{{ $tong['don'] }}</b><div class="muted">đã giao {{ $tong['da_giao'] }}</div></div>
    <div><div class="muted">Số chiếc</div><b style="font-size:20px">{{ number_format($tong['chiec'], 0, ',', '.') }}</b></div>
    <div><div class="muted">Tổng tiền đơn</div><b style="font-size:20px">{{ $tien($tong['tien']) }}</b></div>
    <div><div class="muted">Ví hiện tại</div><b style="font-size:20px;{{ $tong['so_du'] < 0 ? 'color:#B91C1C' : '' }}">{{ $tien($tong['so_du']) }}</b>
      <div class="muted">{{ $tong['so_du'] < 0 ? 'đang nợ' : '' }}{{ $seller->traSau() ? ($tong['so_du'] < 0 ? ' · ' : '') . 'trả sau' : '' }}</div></div>
  </div>
  @if($tong['mau']->isNotEmpty())
  <div class="pad" style="padding-top:0">
    <table class="pl-bang"><tr><th>Mẫu đặt nhiều</th><th class="num">Chiếc</th><th class="num">Tiền</th></tr>
      @foreach($tong['mau'] as $r)<tr><td>{{ $r->ten_mau }}</td><td class="num">{{ number_format($r->sl, 0, ',', '.') }}</td><td class="num">{{ $tien($r->tien) }}</td></tr>@endforeach
    </table>
  </div>
  @endif
</div>
@endif

<div class="card">
  @if($tt === 'can_lam')<div class="card-h"><div class="card-hint">Hàng đợi in: đơn đã trừ ví, cũ nhất trước. Đơn Chờ nạp tiền chưa in (seller chưa đủ tiền).</div></div>@endif
  @if($items->isEmpty())
    <div class="empty">Không có đơn nào{{ $giu ? ' khớp bộ lọc' : '' }}.</div>
  @else
  <div class="pl-cuon"><table>
    <tr><th>Đơn</th>@if(!$seller)<th>Seller</th>@endif<th>Nội dung</th><th>Giao tới</th><th class="num">Tiền</th><th>Trạng thái</th><th></th></tr>
    @foreach($items as $d)
    <tr>
      <td><b>{{ $d->ma }}</b><div class="muted">{{ optional($d->created_at)->format('d/m/Y H:i') }}@if($d->ma_don_san) · {{ $d->ma_don_san }}@endif</div>@if($d->xuong_tao)<span class="badge b-chung" style="margin-top:4px">Xưởng tạo hộ</span>@endif</td>
      @if(!$seller)<td><a href="{{ route('admin.pod.don.index', ['seller' => $d->seller_id]) }}" style="color:var(--gd);font-weight:700">{{ optional($d->seller)->ten }}</a><div class="muted">{{ optional($d->seller)->ma }}</div></td>@endif
      <td>
        @foreach($d->dong as $x)
          <div>{{ $x->ten_mau }} · {{ $x->ten_bien_the }}@if($x->chat_lieu) · <b>{{ $x->chat_lieu }}</b>@endif @if($x->mau_sac) · {{ $x->mau_sac }}@endif × <b>{{ $x->so_luong }}</b>@if($x->chu_ca_nhan) <span class="muted">"{{ $x->chu_ca_nhan }}"@if($x->font) · {{ $x->font }}@endif</span>@endif</div>
        @endforeach
      </td>
      <td class="muted">{{ $d->noi_giao['ten'] ?? '—' }}@if($d->ma_van_don)<div>VĐ {{ $d->ma_van_don }}</div>@endif</td>
      <td class="num">{{ number_format($d->tong_tien, 0, ',', '.') }}đ</td>
      <td><span class="badge b-{{ $d->trang_thai }}">{{ $d->tenTrangThai() }}</span>@if($d->giao_luc)<div class="muted">{{ $d->giao_luc->format('d/m') }}</div>@endif @if(!$d->noi_giao && !in_array($d->trang_thai, ['da_giao', 'da_huy'], true))<div style="margin-top:4px"><span class="badge b-cho_nap">Chưa có địa chỉ</span></div>@endif</td>
      <td><a class="btn-sm" href="{{ route('admin.pod.don.show', $d) }}">Xem</a></td>
    </tr>
    @endforeach
  </table></div>
  @if($items->hasPages())<div class="pad">{{ $items->links('pagination::simple-default') }}</div>@endif
  @endif
</div>
@include('admin.pod._cuoi')
