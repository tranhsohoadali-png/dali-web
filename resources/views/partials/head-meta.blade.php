{{-- SEO Meta Tags --}}
<meta name="description" content="{{ $metaDesc ?? ($settings['meta_description'] ?? 'Bộ tranh tô màu số hóa DALI – ai cũng có thể tạo ra kiệt tác hội họa của riêng mình.') }}">
<meta name="keywords" content="{{ $settings['meta_keywords'] ?? 'tranh tô màu số hóa, paint by numbers, DALI tranh' }}">
<meta name="robots" content="index,follow">
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $ogType ?? 'website' }}">
<meta property="og:title" content="{{ $ogTitle ?? ($settings['meta_title'] ?? 'DALI – Tô Điểm Cuộc Sống') }}">
<meta property="og:description" content="{{ $ogDesc ?? ($settings['meta_description'] ?? '') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $ogImage ?? asset('images/logo_dali.png') }}">
<meta property="og:locale" content="vi_VN">
<meta property="og:site_name" content="DALI Tranh Tô Màu Số Hóa">

{{-- Google Analytics GA4 --}}
@if(!empty($settings['ga_id']))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga_id'] }}"></script>
<script>
window.dataLayer=window.dataLayer||[];
function gtag(){dataLayer.push(arguments);}
gtag('js',new Date());
gtag('config','{{ $settings["ga_id"] }}');
</script>
@endif

{{-- Facebook Pixel --}}
@if(!empty($settings['fb_pixel_id']))
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init','{{ $settings["fb_pixel_id"] }}');
fbq('track','PageView');
</script>
@endif
