<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Blog | DALI – Tô Điểm Cuộc Sống</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>[class^="ri-"],[class*=" ri-"]{vertical-align:-.125em;font-style:normal;line-height:1}</style>
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--bd:#C8E89A;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx);line-height:1.6}
nav{background:linear-gradient(175deg,#1C5200,#2D7A08,#3A9A12);height:68px;padding:0 5%;display:flex;align-items:center;justify-content:space-between}
.nav-logo-t{font-size:26px;font-weight:900;letter-spacing:3px;color:#fff;text-decoration:none}.nav-logo-t span{color:#C6F135}
.nav-links{display:flex;gap:26px;list-style:none}.nav-links a{text-decoration:none;color:rgba(255,255,255,.75);font-size:14px;font-weight:500}.nav-links a:hover,.nav-links a.act{color:#fff}
.btn-nav{background:#C6F135;color:#1C3A0A;border:none;border-radius:50px;padding:9px 20px;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none}
.nav-right{display:flex;align-items:center;gap:12px}
.nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:4px}
.nav-hamburger span{display:block;width:22px;height:2px;background:#fff;border-radius:2px}
.mobile-nav{display:none;position:fixed;top:68px;left:0;right:0;bottom:0;background:linear-gradient(175deg,#1C5200,#2D7A08);z-index:99;padding:28px 5%;flex-direction:column;gap:4px}
.mobile-nav.open{display:flex}
.mobile-nav a{font-size:17px;font-weight:600;color:rgba(255,255,255,.85);text-decoration:none;padding:13px 16px;border-bottom:1px solid rgba(255,255,255,.1);border-radius:8px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:7px 5%;display:flex;align-items:center;gap:6px}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2.5px;font-weight:700;margin-left:8px}
.page-hero{background:linear-gradient(175deg,#1C5200,#2D7A08);padding:44px 5% 36px;color:#fff;text-align:center}
.page-hero h1{font-size:clamp(24px,3.5vw,36px);font-weight:900;margin-bottom:8px}
.page-hero p{font-size:14px;opacity:.75}
.blog-wrap{padding:36px 5%;max-width:1100px;margin:0 auto}
.cat-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px}
.cat-tab{padding:7px 16px;border-radius:50px;border:1.5px solid var(--bd);background:#fff;font-size:12px;font-weight:700;color:var(--tx2);cursor:pointer;text-decoration:none;transition:all .2s}
.cat-tab:hover,.cat-tab.active{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border-color:var(--g)}
.posts-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.post-card{background:#fff;border-radius:18px;overflow:hidden;border:1.5px solid var(--bd);transition:transform .3s,box-shadow .3s;text-decoration:none;display:block;color:inherit}
.post-card:hover{transform:translateY(-8px);box-shadow:0 16px 44px rgba(58,122,10,.12)}
.post-cover{height:200px;overflow:hidden;background:linear-gradient(135deg,var(--gl),#CCEF90);display:flex;align-items:center;justify-content:center;font-size:56px}
.post-cover img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
.post-card:hover .post-cover img{transform:scale(1.06)}
.post-body{padding:18px}
.post-cat{display:inline-block;background:var(--gl);color:var(--gd);font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;margin-bottom:8px;border:1px solid var(--bd);line-height:1.5}
.post-title{font-size:16px;font-weight:800;color:var(--char);margin-bottom:8px;line-height:1.4}
.post-excerpt{font-size:13px;color:var(--tx2);line-height:1.7;margin-bottom:12px}
.post-meta{display:flex;align-items:center;gap:12px;font-size:11px;color:var(--tx3)}
.no-posts{text-align:center;padding:52px 20px;grid-column:1/-1}
.no-posts-icon{font-size:52px;margin-bottom:14px}
.pagination{display:flex;gap:6px;margin-top:28px;flex-wrap:wrap;justify-content:center}
.pagination a,.pagination span{padding:7px 13px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1.5px solid var(--bd);color:var(--tx2);background:#fff;transition:all .2s}
.pagination a:hover{background:var(--g);color:#fff;border-color:var(--g)}
.pagination .active{background:var(--g);color:#fff;border-color:var(--g)}
footer{background:linear-gradient(175deg,#0F2E00,#1C5200);color:rgba(255,255,255,.7);padding:30px 5%;margin-top:32px}
.footer-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:18px;display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.3)}
/* ── Bài nổi bật (featured) ── */
.featured{display:grid;grid-template-columns:1.15fr 1fr;background:#fff;border:1.5px solid var(--bd);border-radius:22px;overflow:hidden;margin-bottom:30px;text-decoration:none;color:inherit;box-shadow:0 10px 36px rgba(58,122,10,.08);transition:transform .3s,box-shadow .3s}
.featured:hover{transform:translateY(-4px);box-shadow:0 22px 54px rgba(58,122,10,.16)}
.featured-cover{position:relative;min-height:330px;background:linear-gradient(135deg,var(--gl),#CCEF90);overflow:hidden;display:flex;align-items:center;justify-content:center;font-size:70px;color:var(--gd)}
.featured-cover img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.featured:hover .featured-cover img{transform:scale(1.05)}
.featured-pin{position:absolute;top:16px;left:16px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:11px;font-weight:800;padding:6px 13px;border-radius:20px;letter-spacing:.5px;box-shadow:0 6px 16px rgba(58,122,10,.3)}
.featured-body{padding:32px 34px;display:flex;flex-direction:column;justify-content:center}
.featured-body .fb-cat{font-size:11px;font-weight:800;color:var(--g);letter-spacing:1px;text-transform:uppercase;margin-bottom:10px}
.featured-body h2{font-size:clamp(20px,2.4vw,28px);font-weight:900;color:var(--char);line-height:1.3;margin-bottom:12px}
.featured-body p{font-size:14px;color:var(--tx2);line-height:1.75;margin-bottom:18px}
.featured-meta{display:flex;gap:14px;font-size:12px;color:var(--tx3);margin-bottom:18px;flex-wrap:wrap}
.featured-readmore{font-size:13px;font-weight:800;color:var(--g);display:inline-flex;align-items:center;gap:6px}
.featured:hover .featured-readmore{gap:10px}

/* ── Thẻ bài viết: nhãn chủ đề nổi trên ảnh ── */
.post-cover{position:relative}
.post-cover::after{content:'';position:absolute;inset:0;background:linear-gradient(to top,rgba(28,58,10,.18),transparent 45%);opacity:0;transition:opacity .3s}
.post-card:hover .post-cover::after{opacity:1}
.post-cat-ov{position:absolute;top:12px;left:12px;background:rgba(255,255,255,.95);color:var(--gd);font-size:10.5px;font-weight:800;padding:4px 11px;border-radius:20px;letter-spacing:.4px;z-index:2;box-shadow:0 4px 12px rgba(0,0,0,.08);backdrop-filter:blur(4px)}
.post-readmore{font-size:12px;font-weight:800;color:var(--g);display:inline-flex;align-items:center;gap:5px;margin-top:12px;transition:gap .25s}
.post-card:hover .post-readmore{gap:9px}
.post-meta{border-top:1px dashed var(--bd);padding-top:10px;margin-top:12px}

@media(max-width:900px){.posts-grid{grid-template-columns:repeat(2,1fr)}.nav-links{display:none}.nav-hamburger{display:flex}nav{padding:0 4%}}
@media(max-width:760px){.featured{grid-template-columns:1fr}.featured-cover{min-height:210px}.featured-body{padding:24px}}
@media(max-width:600px){.posts-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}" class="nav-logo-t">DAL<span>I</span></a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">Trang chủ</a></li>
    <li><a href="{{ route('products') }}">Sản phẩm</a></li>
    <li><a href="{{ route('blog') }}" class="act">Blog</a></li>
    <li><a href="{{ route('track-order') }}">Tra cứu đơn</a></li>
    <li><a href="{{ route('ctv.login') }}" style="background:rgba(198,241,53,.15);border:1.5px solid rgba(198,241,53,.4);border-radius:50px;padding:4px 12px;font-size:12px;font-weight:700;color:#C6F135;text-decoration:none"><i class="ri-team-line" style="margin-right:4px"></i>CTV</a></li>
  </ul>
  <div class="nav-right">
    <a href="{{ route('products') }}" class="btn-nav">Mua sắm ngay</a>
    <button class="nav-hamburger" onclick="document.getElementById('mnav').classList.toggle('open')"><span></span><span></span><span></span></button>
  </div>
</nav>
<div class="mobile-nav" id="mnav">
  <a href="{{ route('home') }}"><i class="ri-home-5-line"></i> Trang chủ</a>
  <a href="{{ route('products') }}"><i class="ri-palette-line"></i> Sản phẩm</a>
  <a href="{{ route('blog') }}"><i class="ri-quill-pen-line"></i> Blog</a>
  <a href="{{ route('cart') }}"><i class="ri-shopping-cart-2-line"></i> Giỏ hàng</a>
  <a href="{{ route('track-order') }}"><i class="ri-search-line"></i> Tra cứu đơn</a>
</div>
<div class="sak"><span><i class="ri-flower-line"></i></span><span><i class="ri-flower-line"></i></span><span><i class="ri-leaf-line"></i></span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>

<div class="page-hero">
  <h1><i class="ri-book-open-line"></i> Blog DALI</h1>
  <p>Hướng dẫn tô màu, cảm hứng nghệ thuật và tin tức mới nhất từ DALI</p>
</div>

<div class="blog-wrap">
  {{-- Category tabs --}}
  <div class="cat-tabs">
    <a href="{{ route('blog') }}" class="cat-tab {{ !request('category') ? 'active' : '' }}"><i class="ri-book-2-line"></i> Tất cả</a>
    @foreach($categories as $cat)
    <a href="{{ route('blog') }}?category={{ $cat }}" class="cat-tab {{ request('category')==$cat ? 'active' : '' }}">{{ $cat }}</a>
    @endforeach
  </div>

  @if($posts->count())
    @php
      $showFeatured = $posts->currentPage() == 1 && !request('category');
      $featured  = $showFeatured ? $posts->first() : null;
      $gridPosts = $showFeatured ? $posts->slice(1) : $posts;
    @endphp

    {{-- Bài nổi bật --}}
    @if($featured)
    <a href="{{ route('blog.post', $featured->slug) }}" class="featured">
      <div class="featured-cover">
        <span class="featured-pin"><i class="ri-fire-fill"></i> Nổi bật</span>
        @if($featured->cover_image)
          <img src="{{ asset('storage/'.$featured->cover_image) }}" alt="{{ $featured->title }}">
        @else
          <i class="ri-book-open-line"></i>
        @endif
      </div>
      <div class="featured-body">
        <div class="fb-cat">{{ $featured->category }}</div>
        <h2>{{ $featured->title }}</h2>
        @if($featured->excerpt)<p>{{ Str::limit($featured->excerpt, 180) }}</p>@endif
        <div class="featured-meta">
          <span><i class="ri-calendar-line"></i> {{ $featured->published_at?->format('d/m/Y') }}</span>
          <span><i class="ri-timer-line"></i> {{ $featured->read_time }} phút đọc</span>
          <span><i class="ri-eye-line"></i> {{ number_format($featured->view_count) }} lượt xem</span>
        </div>
        <span class="featured-readmore">Đọc bài viết <i class="ri-arrow-right-line"></i></span>
      </div>
    </a>
    @endif

    @if($gridPosts->count())
    <div class="posts-grid">
      @foreach($gridPosts as $post)
      <a href="{{ route('blog.post', $post->slug) }}" class="post-card">
        <div class="post-cover">
          <span class="post-cat-ov">{{ $post->category }}</span>
          @if($post->cover_image)
            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}">
          @else
            <i class="ri-book-open-line"></i>
          @endif
        </div>
        <div class="post-body">
          <div class="post-title">{{ $post->title }}</div>
          @if($post->excerpt)
          <div class="post-excerpt">{{ Str::limit($post->excerpt, 100) }}</div>
          @endif
          <span class="post-readmore">Đọc bài <i class="ri-arrow-right-line"></i></span>
          <div class="post-meta">
            <span><i class="ri-calendar-line"></i> {{ $post->published_at?->format('d/m/Y') }}</span>
            <span><i class="ri-timer-line"></i> {{ $post->read_time }} phút đọc</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>
    @endif
  @else
    <div class="posts-grid">
      <div class="no-posts">
        <div class="no-posts-icon"><i class="ri-quill-pen-line"></i></div>
        <div style="font-size:18px;font-weight:800;color:var(--char);margin-bottom:8px">Chưa có bài viết nào</div>
        <div style="font-size:14px;color:var(--tx3)">Quay lại sau nhé!</div>
      </div>
    </div>
  @endif

  @if($posts->hasPages())
  <div class="pagination">
    @if($posts->onFirstPage())
      <span style="opacity:.35;cursor:default">‹ Trước</span>
    @else
      <a href="{{ $posts->previousPageUrl() }}" rel="prev">‹ Trước</a>
    @endif
    @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
      @if($page == $posts->currentPage())
        <span class="active">{{ $page }}</span>
      @else
        <a href="{{ $url }}">{{ $page }}</a>
      @endif
    @endforeach
    @if($posts->hasMorePages())
      <a href="{{ $posts->nextPageUrl() }}" rel="next">Sau ›</a>
    @else
      <span style="opacity:.35;cursor:default">Sau ›</span>
    @endif
  </div>
  @endif
</div>

<footer><div class="footer-bottom"><span>© 2024 DALI Tranh Tô Màu Số Hóa</span><span>🇻🇳 Việt Nam</span></div></footer>
@include('partials.float-widget')
@include('partials.bottom-nav')
</body>
</html>