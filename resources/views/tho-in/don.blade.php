@include('tho-in._khung', ['tieuDe' => 'Đơn ' . $don->ma])
<p><a class="btn2" href="{{ route('tho-in.hang-doi') }}">← Hàng đợi</a></p>
<div class="box" style="margin-top:10px">
  <div style="display:flex;justify-content:space-between;gap:10px;align-items:center"><h2 style="margin:0">{{ $don->ma }}</h2><span class="st st-{{ $don->trang_thai }}">{{ $don->tenTrangThai() }}</span></div>
  <div class="mu">Đặt {{ optional($don->created_at)->format('d/m/Y H:i') }}@if($don->nguoi_in) · in: {{ $don->nguoi_in }}@endif @if($don->nguoi_giao) · giao: {{ $don->nguoi_giao }} {{ optional($don->giao_luc)->format('d/m H:i') }}@endif</div>
</div>
<div class="box">
  <b>Cần in</b>
  @foreach($don->dong as $x)
    <div class="dong">
      <div>{{ $x->ten_mau }} · {{ $x->ten_bien_the }}@if($x->chat_lieu) · nhựa <b>{{ $x->chat_lieu }}</b>@endif @if($x->mau_sac) · <b>{{ $x->mau_sac }}</b>@endif × <span class="sl">{{ $x->so_luong }}</span></div>
      @if($x->chu_ca_nhan)<div>Chữ cá nhân hoá: <b>{{ $x->chu_ca_nhan }}</b>@if($x->font) · font <b>{{ $x->font }}</b>@endif</div>@endif
      <div class="mu" style="word-break:break-all">File in: {{ $fileIn[$x->bien_the_id] ?? 'chưa ghi, hỏi chủ xưởng' }}</div>
    </div>
  @endforeach
  @if($don->ghi_chu)<div class="dong">Ghi chú của seller: {{ $don->ghi_chu }}</div>@endif
</div>
<div class="box">
  <b>Giao tới</b>
  @if($don->noi_giao)<div>{{ $don->noi_giao['ten'] ?? '' }}</div><div>{{ $don->noi_giao['dia_chi'] ?? '' }}</div><div class="mu">{{ $don->noi_giao['sdt'] ?? '' }} {{ $don->noi_giao['ghi_chu'] ?? '' }}</div>@endif
  <div style="margin-top:8px">@if($coNhan)<a class="btn2" href="{{ route('tho-in.nhan', $don) }}">Tải nhãn line bay</a>@else<span class="mu">Đơn này không có nhãn riêng.</span>@endif</div>
  @if($don->ma_don_san)<div class="mu" style="margin-top:6px">Mã đơn sàn: {{ $don->ma_don_san }}</div>@endif
  @if($don->ma_van_don)<div class="mu">Mã vận đơn: {{ $don->ma_van_don }}</div>@endif
</div>
@if($don->trang_thai === 'cho_in')
  <form method="POST" action="{{ route('tho-in.trang-thai', $don) }}" class="box">@csrf
    <input type="hidden" name="trang_thai" value="dang_in">
    <button class="btn" style="width:100%">Bắt đầu in đơn này</button>
  </form>
@endif
@if(in_array($don->trang_thai, ['cho_in', 'dang_in'], true))
  <form method="POST" action="{{ route('tho-in.trang-thai', $don) }}" class="box">@csrf
    <input type="hidden" name="trang_thai" value="da_giao">
    <label>Mã vận đơn trong nước (nếu có)<input name="ma_van_don" maxlength="60" value="{{ $don->ma_van_don }}"></label>
    <button class="btn" style="width:100%;margin-top:10px">Đã giao</button>
  </form>
@endif
<p class="mu">Muốn huỷ hay sửa đơn: báo chủ xưởng.</p>
@include('tho-in._het')
