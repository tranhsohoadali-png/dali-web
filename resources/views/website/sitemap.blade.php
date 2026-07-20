<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{{ url('/') }}</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>
  <url><loc>{{ route('products') }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
  <url><loc>{{ route('thiet-ke') }}</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc>{{ route('guide') }}</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <url><loc>{{ route('blog') }}</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>
  {{-- Trang pháp lý / tin cậy (AdSense soi mấy trang này).
       Đã BỎ /tra-cuu-don-hang khỏi sitemap vì trang đó để noindex. --}}
  <url><loc>{{ route('about') }}</loc><changefreq>yearly</changefreq><priority>0.6</priority></url>
  <url><loc>{{ route('contact') }}</loc><changefreq>yearly</changefreq><priority>0.6</priority></url>
  <url><loc>{{ route('privacy') }}</loc><changefreq>yearly</changefreq><priority>0.4</priority></url>
  <url><loc>{{ route('terms') }}</loc><changefreq>yearly</changefreq><priority>0.4</priority></url>
  <url><loc>{{ route('return-policy') }}</loc><changefreq>yearly</changefreq><priority>0.5</priority></url>
  @foreach($products as $p)
  <url>
    <loc>{{ route('product', $p->slug) }}</loc>
    <lastmod>{{ $p->updated_at->format('Y-m-d') }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  @endforeach
  @foreach($categories as $cat)
  <url>
    <loc>{{ route('category', $cat->slug) }}</loc>
    <lastmod>{{ $cat->updated_at->format('Y-m-d') }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
  </url>
  @endforeach
  @foreach($posts as $post)
  <url>
    <loc>{{ route('blog.post', $post->slug) }}</loc>
    <lastmod>{{ $post->updated_at->format('Y-m-d') }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
  @endforeach
</urlset>
