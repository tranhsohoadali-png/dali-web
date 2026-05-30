<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{{ url('/') }}</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>
  <url><loc>{{ route('products') }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
  <url><loc>{{ route('track-order') }}</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
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
    <loc>{{ route('products') }}?category={{ $cat->slug }}</loc>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
  </url>
  @endforeach
</urlset>
