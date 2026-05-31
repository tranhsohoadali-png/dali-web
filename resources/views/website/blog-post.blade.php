<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $post->meta_title ?? $post->title }} | DALI Blog</title>
<meta name="description" content="{{ $post->meta_description ?? $post->excerpt }}">
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ $post->excerpt }}">
<meta property="og:image" content="{{ $post->cover_image ? asset('storage/'.$post->cover_image) : asset('images/logo_dali.png') }}">
@if(!empty($settings['ga_id']))<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga_id'] }}"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $settings["ga_id"] }}');</script>@endif
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--bd:#C8E89A;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
nav{background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:68px;padding:0 5%;display:flex;align-items:center;justify-content:space-between}
.nav-logo-t{font-size:26px;font-weight:900;letter-spacing:3px;color:#fff;text-decoration:none}.nav-logo-t span{color:#C6F135}
.nav-links{display:flex;gap:26px;list-style:none}.nav-links a{text-decoration:none;color:rgba(255,255,255,.75);font-size:14px;font-weight:500}.nav-links a:hover{color:#fff}
.btn-nav{background:#C6F135;color:#1C3A0A;border:none;border-radius:50px;padding:9px 20px;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:7px 5%;display:flex;align-items:center;gap:6px}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2.5px;font-weight:700;margin-left:8px}
.bc{padding:14px 5%;display:flex;align-items:center;gap:8px;font-size:13px;color:var(--tx3)}
.bc a{color:var(--tx2);text-decoration:none;font-weight:500}.bc a:hover{color:var(--g)}
.bc-sep{color:var(--bd2)}
.article-wrap{max-width:800px;margin:0 auto;padding:0 5% 60px}
.post-header{margin-bottom:28px;padding-bottom:20px;border-bottom:1.5px solid var(--bd)}
.post-cat-badge{display:inline-block;background:var(--gl);color:var(--gd);font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;margin-bottom:12px;border:1px solid var(--bd)}
.post-title{font-size:clamp(24px,3.5vw,36px);font-weight:900;color:var(--char);line-height:1.25;margin-bottom:14px}
.post-meta{display:flex;align-items:center;gap:16px;font-size:13px;color:var(--tx3);flex-wrap:wrap}
.post-cover-wrap{border-radius:18px;overflow:hidden;border:1.5px solid var(--bd);margin-bottom:28px;background:linear-gradient(135deg,var(--gl),#CCEF90);height:360px;display:flex;align-items:center;justify-content:center;font-size:72px}
.post-cover-wrap img{width:100%;height:100%;object-fit:cover;display:block}
.post-content{font-size:16px;color:var(--tx2);line-height:1.9}
.post-content h2{font-size:22px;font-weight:800;color:var(--char);margin:28px 0 12px}
.post-content h3{font-size:18px;font-weight:700;color:var(--char);margin:22px 0 10px}
.post-content p{margin-bottom:16px}
.post-content ul,.post-content ol{margin:0 0 16px 24px}
.post-content li{margin-bottom:6px}
.post-content img{max-width:100%;border-radius:12px;margin:0;border:1.5px solid var(--bd)}
.post-content figure{margin:22px 0}
.post-content figure img{width:100%}
.post-content figcaption{font-size:13px;color:var(--tx3);text-align:center;margin-top:8px;font-style:italic}
.post-content .img-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:22px 0}
.post-content .img-row figure{margin:0}
.post-content table{width:100%;border-collapse:collapse;margin:20px 0;font-size:14px}
.post-content th{background:var(--gll);color:var(--gd);font-weight:800;text-align:left;padding:10px 14px;border:1px solid var(--bd)}
.post-content td{padding:10px 14px;border:1px solid var(--bd);color:var(--tx2)}
.post-content .lead{font-size:17px;color:var(--tx);font-weight:500;line-height:1.8;margin-bottom:20px}
.post-content .tip-box{background:linear-gradient(135deg,var(--gll),#fff);border:1.5px solid var(--bd);border-radius:14px;padding:16px 20px;margin:20px 0}
.post-content .tip-box strong{color:var(--gd)}
.post-content .faq-q{font-size:16px;font-weight:800;color:var(--char);margin:18px 0 6px}
.post-content .faq-q::before{content:'❓ ';}
.post-content a{color:var(--g);font-weight:600;text-decoration:underline}
@media(max-width:600px){.post-content .img-row{grid-template-columns:1fr}}
.post-content blockquote{background:var(--gll);border-left:4px solid var(--g);border-radius:0 12px 12px 0;padding:16px 20px;margin:20px 0;font-style:italic;color:var(--gd)}
.post-content code{background:var(--gl);padding:2px 6px;border-radius:4px;font-size:14px}
.post-content pre{background:var(--char);color:var(--gn);padding:16px 20px;border-radius:10px;overflow-x:auto;margin-bottom:16px;font-size:14px}
.cta-box{background:linear-gradient(135deg,#3A9A12,var(--g));border-radius:18px;padding:28px;text-align:center;color:#fff;margin:32px 0}
.cta-box h3{font-size:20px;font-weight:900;margin-bottom:8px}
.cta-box p{font-size:14px;opacity:.85;margin-bottom:18px}
.btn-cta{background:#fff;color:var(--gd);border:none;border-radius:50px;padding:12px 28px;font-size:14px;font-weight:800;cursor:pointer;text-decoration:none;display:inline-block;transition:all .2s}
.btn-cta:hover{background:var(--gll);transform:translateY(-2px)}
.related-section{margin-top:48px;padding-top:32px;border-top:1.5px solid var(--bd)}
.related-title{font-size:20px;font-weight:900;color:var(--char);margin-bottom:20px}
.related-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.related-card{background:#fff;border-radius:14px;overflow:hidden;border:1.5px solid var(--bd);text-decoration:none;display:block;color:inherit;transition:transform .3s}
.related-card:hover{transform:translateY(-4px)}
.related-cover{height:140px;overflow:hidden;background:linear-gradient(135deg,var(--gl),#CCEF90);display:flex;align-items:center;justify-content:center;font-size:36px}
.related-cover img{width:100%;height:100%;object-fit:cover}
.related-body{padding:12px 14px}
.related-title-t{font-size:13px;font-weight:700;color:var(--char);line-height:1.4}
.related-date{font-size:11px;color:var(--tx3);margin-top:4px}
footer{background:linear-gradient(175deg,#0F2E00,#1C5200);color:rgba(255,255,255,.7);padding:30px 5%}
.footer-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:18px;display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.3)}
@media(max-width:600px){.related-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}" class="nav-logo-t">DAL<span>I</span></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">Trang chủ</a></li>
    <li><a href="{{ route('products') }}">Sản phẩm</a></li>
    <li><a href="{{ route('blog') }}">Blog</a></li>
    <li><a href="{{ route('track-order') }}">Tra cứu đơn</a></li>
  </ul>
  <a href="{{ route('products') }}" class="btn-nav">Mua sắm ngay</a>
</nav>
<div class="sak"><span>🌸</span><span>✿</span><span>🍃</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>

<div class="bc">
  <a href="{{ route('home') }}">Trang chủ</a>
  <span class="bc-sep">›</span>
  <a href="{{ route('blog') }}">Blog</a>
  <span class="bc-sep">›</span>
  <a href="{{ route('blog') }}?category={{ $post->category }}">{{ $post->category }}</a>
  <span class="bc-sep">›</span>
  <span style="color:var(--tx)">{{ Str::limit($post->title, 50) }}</span>
</div>

<div class="article-wrap">
  <div class="post-header">
    <div class="post-cat-badge">{{ $post->category }}</div>
    <h1 class="post-title">{{ $post->title }}</h1>
    <div class="post-meta">
      <span>📅 {{ $post->published_at?->format('d/m/Y') }}</span>
      <span>⏱ {{ $post->read_time }} phút đọc</span>
      <span>👁 {{ number_format($post->view_count) }} lượt xem</span>
    </div>
  </div>

  <div class="post-cover-wrap">
    @if($post->cover_image)
      <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}">
    @else
      📖
    @endif
  </div>

  <div class="post-content">
    @if(\Illuminate\Support\Str::contains($post->content, ['<p>','<h2>','<ul>','<figure>','<div']))
      {!! $post->content !!}
    @else
      {!! nl2br(e($post->content)) !!}
    @endif
  </div>

  {{-- CTA trong bài --}}
  <div class="cta-box">
    <h3>🎨 Muốn thử vẽ tranh số hóa?</h3>
    <p>Khám phá hơn 500 mẫu tranh DALI – ai cũng có thể tạo ra kiệt tác của riêng mình!</p>
    <a href="{{ route('products') }}" class="btn-cta">Xem tất cả tranh DALI →</a>
  </div>

  {{-- Related posts --}}
  @if($related->count())
  <div class="related-section">
    <div class="related-title">Bài viết liên quan</div>
    <div class="related-grid">
      @foreach($related as $r)
      <a href="{{ route('blog.post', $r->slug) }}" class="related-card">
        <div class="related-cover">
          @if($r->cover_image)<img src="{{ asset('storage/'.$r->cover_image) }}" alt="{{ $r->title }}">@else📖@endif
        </div>
        <div class="related-body">
          <div class="related-title-t">{{ $r->title }}</div>
          <div class="related-date">{{ $r->published_at?->format('d/m/Y') }}</div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif
</div>

<footer><div class="footer-bottom"><span>© 2024 DALI Tranh Tô Màu Số Hóa</span><span>🇻🇳 Việt Nam</span></div></footer>
@include('partials.float-widget')
</body>
</html>