<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('tieu_de', 'Xưởng in 3D cho seller POD') | DALI POD</title>
<meta name="description" content="@yield('mo_ta', 'Xưởng DALI in 3D theo đơn cho seller POD bán Etsy, Amazon, TikTok Shop. Giá in PLA theo gram, ví nạp trước, mẫu độc quyền giữ kín cho riêng từng seller.')">
@php
  $urlTrang = rtrim(config('pod.url'), '/') . '/' . ltrim(request()->path() === '/' ? '' : request()->path(), '/');
  // ảnh khi gửi link (Zalo, Facebook, Messenger): trang mẫu dùng ảnh mẫu; trang khác dùng ảnh chung 1200×630.
  // Ảnh chung lấy ở tên miền chính: nginx tên miền POD chỉ mở /images/logo_dali.png, đường khác bị chuyển hướng.
  $anhRieng = trim($__env->yieldContent('og_anh'));
  $anhChiaSe = $anhRieng !== '' ? $anhRieng : 'https://tranhdali.vn/images/og-pod.jpg?v=20261029';
@endphp
<link rel="canonical" href="{{ $urlTrang }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="DALI POD">
<meta property="og:locale" content="vi_VN">
<meta property="og:url" content="{{ $urlTrang }}">
<meta property="og:title" content="@yield('tieu_de', 'Xưởng in 3D cho seller POD') | DALI POD">
<meta property="og:description" content="@yield('mo_ta', 'Xưởng DALI in 3D theo đơn cho seller POD. Giá in theo gram, mẫu độc quyền giữ kín.')">
<meta property="og:image" content="{{ $anhChiaSe }}">
<meta property="og:image:secure_url" content="{{ $anhChiaSe }}">
@if($anhRieng === '')<meta property="og:image:type" content="image/jpeg"><meta property="og:image:width" content="1200"><meta property="og:image:height" content="630">@endif
<meta property="og:image:alt" content="@yield('tieu_de', 'Xưởng in 3D cho seller POD') · DALI POD">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="{{ $anhChiaSe }}">
<meta name="theme-color" content="#2F6B05">
<link rel="icon" href="/favicon.ico">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Pacifico&family=Lobster&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--g2:#8CC63E;--gd:#2F6B05;--gdd:#14320A;--ink:#16300A;--mu:#5B6B52;--bg:#FBFEF7;--gl:#EEF8E2;--gll:#F6FCEE;--bd:#DCEFC6;--pk:#FF8FB1;--r:16px}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',system-ui,sans-serif;background:var(--bg);color:var(--ink);font-size:16px;line-height:1.6}
a{color:var(--gd)}
img{max-width:100%;display:block}
.wrap{max-width:1140px;margin:0 auto;padding:0 20px}
/* đầu trang */
.dau{position:sticky;top:0;z-index:30;background:rgba(255,255,255,.94);border-bottom:1px solid var(--bd)}
@supports (backdrop-filter:blur(8px)){.dau{backdrop-filter:blur(8px)}}
.dau .wrap{display:flex;align-items:center;justify-content:space-between;gap:16px;height:68px}
.logo{display:flex;align-items:center;gap:10px;text-decoration:none}
.logo img{height:34px;width:auto}
.logo span{font-size:12px;font-weight:800;letter-spacing:1.5px;color:#fff;background:var(--gd);border-radius:6px;padding:3px 7px}
.menu{display:flex;align-items:center;gap:6px}
.menu a{text-decoration:none;color:var(--ink);font-weight:600;font-size:15px;padding:8px 12px;border-radius:10px}
.menu a:hover,.menu a.on{background:var(--gl);color:var(--gd)}
.nut{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:12px;padding:12px 22px;font-weight:700;font-size:15px;text-decoration:none;border:2px solid transparent;cursor:pointer;font-family:inherit;line-height:1.2}
.nut-chinh{background:var(--g);color:#fff}.nut-chinh:hover{background:var(--gd)}
.nut-vien{background:#fff;color:var(--gd);border-color:var(--bd)}.nut-vien:hover{border-color:var(--g)}
.nut-nho{padding:9px 16px;font-size:14px;border-radius:10px}
.menu-dt{display:none}
@media(max-width:860px){
  .menu{display:none}
  .menu-dt{display:block;position:relative}
  .menu-dt summary{list-style:none;cursor:pointer;border:1px solid var(--bd);border-radius:10px;padding:8px 12px;font-weight:700;color:var(--gd)}
  .menu-dt summary::-webkit-details-marker{display:none}
  .menu-dt nav{position:absolute;right:0;top:46px;background:#fff;border:1px solid var(--bd);border-radius:14px;padding:8px;min-width:220px;box-shadow:0 12px 30px rgba(20,50,10,.12);display:flex;flex-direction:column}
  .menu-dt nav a{padding:10px 12px;border-radius:10px;text-decoration:none;color:var(--ink);font-weight:600}
}
/* khối chung */
section{padding:72px 0}
.nhan{display:inline-block;font-size:13px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--gd);background:var(--gl);border-radius:20px;padding:5px 12px;margin-bottom:14px}
h1{font-size:clamp(32px,5vw,52px);line-height:1.12;letter-spacing:-.5px;font-weight:800}
h2{font-size:clamp(26px,3.4vw,36px);line-height:1.2;letter-spacing:-.3px;font-weight:800;margin-bottom:12px}
h3{font-size:19px;font-weight:700;margin-bottom:6px}
.phu{color:var(--mu);font-size:17px;max-width:640px}
.the{background:#fff;border:1px solid var(--bd);border-radius:var(--r);padding:24px}
.luoi{display:grid;gap:20px}
@media(min-width:720px){.l2{grid-template-columns:1fr 1fr}.l3{grid-template-columns:repeat(3,1fr)}.l4{grid-template-columns:repeat(4,1fr)}}
.mau-the{display:block;text-decoration:none;color:inherit;background:#fff;border:1px solid var(--bd);border-radius:var(--r);overflow:hidden;transition:transform .15s,box-shadow .15s}
.mau-the:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(20,50,10,.10)}
.mau-the .anh{aspect-ratio:1;background:var(--gll);display:flex;align-items:center;justify-content:center;color:var(--mu);font-size:13px}
.mau-the .anh img{width:100%;height:100%;object-fit:cover}
.mau-the .chu{padding:12px 14px}
.mau-the b{display:block;font-size:15px;line-height:1.35}
.mau-the small{color:var(--mu);font-size:13px}
.mau-the .gia-tu{display:block;margin-top:4px;font-weight:800;color:var(--gd);font-size:14px}
.chip{display:inline-block;padding:7px 14px;border-radius:20px;border:1px solid var(--bd);background:#fff;text-decoration:none;color:var(--ink);font-weight:600;font-size:14px}
.chip.on{background:var(--gd);border-color:var(--gd);color:#fff}
table.bang{width:100%;border-collapse:collapse}
.bang th{text-align:left;font-size:13px;text-transform:uppercase;letter-spacing:.6px;color:var(--mu);padding:12px 14px;border-bottom:2px solid var(--bd)}
.bang td{padding:13px 14px;border-bottom:1px solid var(--gl)}
.bang .so{text-align:right;white-space:nowrap;font-weight:700}
.tb-loi{background:#FEF2F2;color:#B91C1C;border:1px solid #FECACA;border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:15px}
.tb-ok{background:var(--gll);color:var(--gd);border:1px solid var(--bd);border-radius:12px;padding:16px 18px;font-size:16px}
/* chân trang */
.chan{background:var(--gdd);color:#CFE6BC;padding:48px 0 28px;margin-top:40px}
.chan a{color:#fff;text-decoration:none}
.chan .luoi{grid-template-columns:1fr}
@media(min-width:720px){.chan .luoi{grid-template-columns:1.4fr 1fr 1fr}}
.chan h4{color:#fff;font-size:15px;margin-bottom:10px}
.chan p,.chan li{font-size:14px;line-height:1.8}
.chan ul{list-style:none}
.chan .cuoi{border-top:1px solid rgba(255,255,255,.12);margin-top:28px;padding-top:18px;font-size:13px;color:#9FC285}
@yield('css')
</style>
</head>
<body>
@php $tab = $tab ?? ''; @endphp
<header class="dau">
  <div class="wrap">
    <a class="logo" href="/" aria-label="DALI POD trang chủ"><img src="/images/logo_dali.png" alt="DALI" width="75" height="34"><span>POD</span></a>
    <nav class="menu" aria-label="Menu chính">
      <a href="/kho-mau" class="{{ $tab === 'kho' ? 'on' : '' }}">Kho mẫu</a>
      <a href="/bang-gia" class="{{ $tab === 'gia' ? 'on' : '' }}">Bảng giá</a>
      <a href="/#quy-trinh">Quy trình</a>
      <a href="/#bao-mat">Bảo mật mẫu</a>
      <a href="/cong" class="nut nut-vien nut-nho" style="margin-left:8px">Đăng nhập</a>
      <a href="/dang-ky" class="nut nut-chinh nut-nho" style="color:#fff">Đăng ký seller</a>
    </nav>
    <details class="menu-dt">
      <summary>Menu</summary>
      <nav aria-label="Menu điện thoại">
        <a href="/kho-mau">Kho mẫu</a><a href="/bang-gia">Bảng giá</a><a href="/#quy-trinh">Quy trình</a><a href="/#bao-mat">Bảo mật mẫu</a>
        <a href="/cong">Đăng nhập</a><a href="/dang-ky" style="color:var(--gd)">Đăng ký seller</a>
      </nav>
    </details>
  </div>
</header>

<main>
@yield('noi_dung')
</main>

<footer class="chan">
  <div class="wrap">
    <div class="luoi">
      <div>
        <h4>DALI POD · Xưởng in 3D cho seller</h4>
        <p>Công ty TNHH Sản xuất và Thương mại Dali<br>MST 0601177989<br>Thôn Bắc Cường, Xã Minh Thái, Tỉnh Ninh Bình</p>
      </div>
      <div>
        <h4>Seller</h4>
        <ul><li><a href="/kho-mau">Kho mẫu chung</a></li><li><a href="/bang-gia">Bảng giá</a></li><li><a href="/dang-ky">Đăng ký seller</a></li><li><a href="/cong">Đăng nhập cổng seller</a></li></ul>
      </div>
      <div>
        <h4>Liên hệ xưởng</h4>
        <ul><li>Zalo / ĐT: <a href="https://zalo.me/0856911698" rel="nofollow">0856 911 698</a></li><li>8h–20h, thứ 2 đến thứ 7</li><li><a href="https://tranhdali.vn" rel="noopener">tranhdali.vn</a></li></ul>
      </div>
    </div>
    <div class="cuoi">Giá chưa gồm phí vận chuyển. Thiết kế của seller được giữ kín theo cam kết bảo mật.</div>
  </div>
</footer>
</body>
</html>
