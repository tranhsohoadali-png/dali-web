@extends('ctv.layout')
@section('title','Tìm tranh & Lấy link')
@section('content')

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:4px">🔗 Tìm tranh & Lấy link giới thiệu</div>
<p style="font-size:13px;color:var(--tx3);margin-bottom:16px">Chọn tranh, copy link, gửi cho khách — khi khách đặt hàng qua link bạn sẽ nhận hoa hồng tự động trong 30 ngày.</p>

{{-- Link giới thiệu trang chủ --}}
<div class="card" style="margin-bottom:16px">
  <div style="font-size:12px;font-weight:700;color:var(--tx);margin-bottom:8px">🏠 Link giới thiệu trang chủ DALI</div>
  <div style="display:flex;gap:8px;align-items:center">
    <input id="refHome" type="text" value="{{ $baseRef }}" readonly
      style="flex:1;background:var(--gll);border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;color:var(--gd);font-weight:600;outline:none;min-width:0">
    <button onclick="copyRef('refHome',this)" style="flex:0 0 auto;background:var(--g);color:#fff;border:none;border-radius:9px;padding:9px 14px;font-size:12px;font-weight:800;cursor:pointer;white-space:nowrap">📋 Copy</button>
  </div>
  <div style="font-size:11px;color:var(--tx3);margin-top:6px"><i class="ri-information-line"></i> Khách click link này → cookie lưu 30 ngày → bất kỳ đơn nào trong 30 ngày = bạn nhận hoa hồng</div>
</div>

{{-- Tìm kiếm & lọc --}}
<form method="GET" action="{{ route('ctv.products') }}" style="margin-bottom:16px">
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <select name="category" onchange="this.form.submit()"
      style="background:#fff;border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;color:var(--tx);outline:none;font-family:inherit;flex:0 0 auto">
      <option value="">Tất cả danh mục</option>
      @foreach($categories as $cat)
      <option value="{{ $cat->slug }}" {{ $catSlug==$cat->slug ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
      @endforeach
    </select>
    <input type="text" name="q" value="{{ $q }}" placeholder="🔍 Tìm tên tranh..."
      style="flex:1;min-width:160px;background:#fff;border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;color:var(--tx);outline:none;font-family:inherit">
    <button type="submit" style="background:var(--g);color:#fff;border:none;border-radius:9px;padding:9px 16px;font-size:13px;font-weight:700;cursor:pointer">Tìm</button>
    @if($q||$catSlug)<a href="{{ route('ctv.products') }}" style="display:flex;align-items:center;color:var(--pk);font-size:12px;font-weight:700;text-decoration:none;padding:9px 4px">✕ Xoá lọc</a>@endif
  </div>
</form>

<div style="font-size:12px;color:var(--tx3);margin-bottom:12px">Tìm thấy <b style="color:var(--g)">{{ $products->total() }}</b> tranh</div>

{{-- Lưới sản phẩm --}}
@forelse($products as $p)
@php
  $refUrl  = url('/san-pham/' . $p->slug) . '?ref=' . $ctv->code;
  $trackedUrl = url('/ref/' . $ctv->code . '?to=' . urlencode(url('/san-pham/' . $p->slug)));
  // Link trực tiếp đến sản phẩm kèm ref code - dùng trang sản phẩm với param
  $directLink = route('product', $p->slug) . '?ref=' . $ctv->code;
  $inputId = 'ref_'.$p->id;
@endphp
<div class="card" style="margin-bottom:12px;padding:12px">
  <div style="display:flex;gap:12px;align-items:flex-start">
    {{-- Ảnh --}}
    <a href="{{ route('product',$p->slug) }}" target="_blank" style="flex:0 0 72px">
      <img src="{{ $p->main_image ? asset('storage/'.$p->main_image) : 'https://placehold.co/72x72?text=DALI' }}"
        alt="{{ $p->name }}" style="width:72px;height:72px;object-fit:cover;border-radius:10px;border:1px solid var(--bd);display:block">
    </a>
    {{-- Thông tin --}}
    <div style="flex:1;min-width:0">
      <a href="{{ route('product',$p->slug) }}" target="_blank"
        style="font-size:13px;font-weight:800;color:var(--tx);text-decoration:none;line-height:1.35;display:block;margin-bottom:4px">{{ $p->name }}</a>
      <div style="font-size:11px;color:var(--tx3);margin-bottom:6px">
        {{ $p->category->name ?? '' }}
        @if($p->colors_count) · {{ $p->colors_count }} màu @endif
        @if($p->sizes()->count()) · {{ $p->sizes()->count() }} kích thước @endif
      </div>
      <div style="font-size:15px;font-weight:900;color:var(--g);margin-bottom:8px">
        @if($p->has_multiple_sizes)<span style="font-size:11px;color:var(--tx3);font-weight:600">Từ</span>@endif
        {{ $p->display_price }}
        @unless($ctv->isAgent())
        <span style="font-size:10px;background:var(--gl);color:var(--gd);padding:2px 7px;border-radius:20px;font-weight:700;margin-left:5px">
          HH {{ rtrim(rtrim(number_format($ctv->commission_rate,1),'0'),'.') }}%
        </span>
        @endunless
      </div>
      {{-- Link giới thiệu --}}
      <div style="background:var(--gll);border-radius:8px;padding:8px 10px;border:1px solid var(--bd)">
        <div style="font-size:10px;font-weight:700;color:var(--tx3);margin-bottom:5px">🔗 LINK GIỚI THIỆU SẢN PHẨM NÀY</div>
        <div style="display:flex;gap:7px;align-items:center">
          <input id="{{ $inputId }}" type="text" value="{{ $directLink }}" readonly
            style="flex:1;background:#fff;border:1px solid var(--bd);border-radius:7px;padding:7px 10px;font-size:11px;color:var(--gd);font-weight:600;outline:none;min-width:0">
          <button onclick="copyRef('{{ $inputId }}',this)"
            style="flex:0 0 auto;background:var(--gd);color:#fff;border:none;border-radius:7px;padding:7px 11px;font-size:11px;font-weight:800;cursor:pointer;white-space:nowrap">📋 Copy</button>
        </div>
        <div style="display:flex;gap:8px;margin-top:7px">
          <a href="https://zalo.me/share?link={{ urlencode($directLink) }}" target="_blank"
            style="flex:1;background:#00AAFF;color:#fff;text-decoration:none;border-radius:7px;padding:7px;font-size:11px;font-weight:700;text-align:center">Chia sẻ Zalo</a>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($directLink) }}" target="_blank"
            style="flex:1;background:#1877F2;color:#fff;text-decoration:none;border-radius:7px;padding:7px;font-size:11px;font-weight:700;text-align:center">Chia sẻ Facebook</a>
          <a href="{{ route('product',$p->slug) }}?ref={{ $ctv->code }}" target="_blank"
            style="flex:1;background:var(--gl);color:var(--gd);text-decoration:none;border-radius:7px;padding:7px;font-size:11px;font-weight:700;text-align:center">🌐 Xem thử</a>
        </div>
      </div>
    </div>
  </div>
</div>
@empty
<div class="card" style="text-align:center;padding:40px 20px">
  <div style="font-size:40px;margin-bottom:12px">🎨</div>
  <div style="font-weight:700;color:var(--tx);margin-bottom:6px">Không tìm thấy tranh</div>
  <a href="{{ route('ctv.products') }}" style="color:var(--g);font-size:13px">Xem tất cả →</a>
</div>
@endforelse

{{-- Phân trang --}}
@if($products->hasPages())
<div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-top:8px;padding-bottom:8px">
  @if(!$products->onFirstPage())
  <a href="{{ $products->previousPageUrl() }}" style="padding:8px 14px;border-radius:8px;border:1.5px solid var(--bd);background:#fff;color:var(--tx2);text-decoration:none;font-size:13px;font-weight:600">‹ Trước</a>
  @endif
  @foreach($products->getUrlRange(max(1,$products->currentPage()-2), min($products->lastPage(),$products->currentPage()+2)) as $page => $url)
  <a href="{{ $url }}" style="padding:8px 14px;border-radius:8px;border:1.5px solid {{ $page==$products->currentPage() ? 'var(--g)' : 'var(--bd)' }};background:{{ $page==$products->currentPage() ? 'var(--g)' : '#fff' }};color:{{ $page==$products->currentPage() ? '#fff' : 'var(--tx2)' }};text-decoration:none;font-size:13px;font-weight:600">{{ $page }}</a>
  @endforeach
  @if($products->hasMorePages())
  <a href="{{ $products->nextPageUrl() }}" style="padding:8px 14px;border-radius:8px;border:1.5px solid var(--bd);background:#fff;color:var(--tx2);text-decoration:none;font-size:13px;font-weight:600">Sau ›</a>
  @endif
</div>
@endif

<div id="copyToast" style="display:none;position:fixed;bottom:80px;left:50%;transform:translateX(-50%);background:#1A4D00;color:#fff;padding:10px 20px;border-radius:50px;font-size:13px;font-weight:700;z-index:9999;white-space:nowrap;box-shadow:0 4px 16px rgba(0,0,0,.2)">✅ Đã copy link!</div>

<script>
function copyRef(id, btn) {
  var inp = document.getElementById(id);
  if (!inp) return;
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(inp.value).then(function() { flashCopy(btn); });
  } else {
    inp.select(); document.execCommand('copy'); flashCopy(btn);
  }
}
function flashCopy(btn) {
  var t = document.getElementById('copyToast');
  t.style.display = 'block';
  setTimeout(function(){ t.style.display='none'; }, 2000);
  if (btn) { var orig = btn.textContent; btn.textContent = '✅ Copied!'; btn.style.background='#3A9A12'; setTimeout(function(){ btn.textContent=orig; btn.style.background=''; }, 2000); }
}
</script>
@endsection
