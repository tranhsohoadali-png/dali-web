@include('tho-in._khung', ['tieuDe' => 'Hàng đợi in'])
<h2>Cần in ({{ $canLam->count() }})</h2>
<p class="mu" style="margin-bottom:10px">Đơn cũ nhất ở trên cùng. Bấm vào đơn để xem file in, nhãn và cập nhật.</p>
@forelse($canLam as $d)
  <a href="{{ route('tho-in.don', $d) }}" class="box" style="display:block;text-decoration:none;color:inherit">
    <div style="display:flex;justify-content:space-between;gap:10px;align-items:center">
      <b>{{ $d->ma }}</b><span class="st st-{{ $d->trang_thai }}">{{ $d->tenTrangThai() }}@if($d->trang_thai === 'dang_in' && $d->nguoi_in) · {{ $d->nguoi_in }}@endif</span>
    </div>
    <div class="mu">Đặt {{ optional($d->created_at)->format('d/m H:i') }} · giao tới {{ $d->noi_giao['ten'] ?? '—' }}</div>
    @foreach($d->dong as $x)
      <div class="dong">{{ $x->ten_mau }} · {{ $x->ten_bien_the }}@if($x->chat_lieu) · <b>{{ $x->chat_lieu }}</b>@endif @if($x->mau_sac) · <b>{{ $x->mau_sac }}</b>@endif × <span class="sl">{{ $x->so_luong }}</span>@if($x->chu_ca_nhan) <span class="mu">chữ: "{{ $x->chu_ca_nhan }}"</span>@endif</div>
    @endforeach
  </a>
@empty
  <div class="box mu">Không có đơn nào cần in.</div>
@endforelse

@if($daGiao->isNotEmpty())
<h2 style="margin-top:20px">Đã giao 7 ngày qua</h2>
<div class="box">
  @foreach($daGiao as $d)
    <div class="dong"><a href="{{ route('tho-in.don', $d) }}">{{ $d->ma }}</a> <span class="mu">· {{ optional($d->giao_luc)->format('d/m H:i') }}@if($d->ma_van_don) · {{ $d->ma_van_don }}@endif @if($d->nguoi_giao) · {{ $d->nguoi_giao }}@endif</span></div>
  @endforeach
</div>
@endif
@include('tho-in._het')
