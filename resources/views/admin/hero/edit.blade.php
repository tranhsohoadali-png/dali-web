<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - Hero Section | DALI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --g:    #6BBF1F;
            --gb:   #8ED63A;
            --gd:   #3E7A0A;
            --gl:   #E8F9D0;
            --gll:  #F4FDE8;
            --gn:   #B5E55A;
            --pk:   #FF8FB1;
            --pkl:  #FFF0F5;
            --bl:   #74C7FF;
            --yl:   #FFE066;
            --tx:   #1A4D00;
            --tx2:  #4A8A1A;
            --tx3:  #8FC860;
            --bd:   #C8E89A;
            --bd2:  #A8D870;
            --bg:   #F2FDE8;
            --wh:   #FFFFFF;
            --card: #FFFFFF;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: "Be Vietnam Pro", sans-serif; background: var(--bg); color: var(--tx); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--bd2); border-radius: 4px; }

        /* ─── SIDEBAR ─── */
        .sidebar {
            width: 232px; flex-shrink: 0;
            background: linear-gradient(175deg, #1C5200 0%, #2D7A08 50%, #3A9A12 100%);
            display: flex; flex-direction: column;
            position: relative; overflow: hidden;
        }
        .sb-orb1 { position:absolute; top:-50px; right:-50px; width:160px; height:160px; border-radius:50%; background:rgba(181,229,90,.08); pointer-events:none; }
        .sb-orb2 { position:absolute; bottom:-40px; left:-40px; width:130px; height:130px; border-radius:50%; background:rgba(255,143,177,.06); pointer-events:none; }
        .sb-orb3 { position:absolute; top:45%; left:-20px; width:80px; height:80px; border-radius:50%; background:rgba(181,229,90,.05); pointer-events:none; }

        .sb-logo-area {
            padding: 20px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .sb-logo-img {
            height: 42px;
            width: auto;
            object-fit: contain;
            display: block;
            filter: brightness(0) invert(1);
        }
        .sb-tagline {
            font-size: 9px; color: rgba(255,255,255,.45);
            letter-spacing: 2.5px; text-transform: uppercase; margin-top: 7px;
        }

        .sb-nav { flex:1; padding:14px 10px; overflow-y:auto; }
        .sb-section {
            font-size: 9px; letter-spacing: 2.5px; color: rgba(255,255,255,.25);
            padding: 8px 10px 5px; text-transform: uppercase;
        }
        .sb-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            color: rgba(255,255,255,.6); font-size: 13px; font-weight: 500;
            margin-bottom: 2px; cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all .2s;
        }
        .sb-item:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sb-item.on {
            background: rgba(255,255,255,.18); color: #fff; font-weight: 700;
            border-color: rgba(255,255,255,.28);
            box-shadow: 0 2px 14px rgba(0,0,0,.15), inset 0 1px 0 rgba(255,255,255,.15);
        }
        .sb-item svg { width:17px; height:17px; flex-shrink:0; }

        .sb-footer {
            padding: 14px 16px;
            border-top: 1px solid rgba(255,255,255,.12);
        }
        .sb-av { display:flex; align-items:center; gap:10px; }
        .sb-av-ring {
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,.18);
            border: 2px solid rgba(255,255,255,.35);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 800; color: #fff;
        }
        .sb-av-name { font-size:13px; font-weight:700; color:#fff; }
        .sb-av-role { font-size:10px; color:rgba(255,255,255,.45); }
        .sb-logout {
            display:block; margin-top:10px;
            font-size:10px; color:rgba(255,255,255,.3);
            text-decoration:none; letter-spacing:1px;
            transition: color .2s;
        }
        .sb-logout:hover { color: var(--pk); }

        /* ─── TOPBAR ─── */
        .topbar {
            background: #fff;
            border-bottom: 2px solid var(--gl);
            padding: 0 24px; height: 66px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .tb-bc { font-size: 10px; color: var(--tx3); letter-spacing: .5px; }
        .tb-bc span { color: var(--g); font-weight: 700; }
        .tb-title {
            font-size: 19px; font-weight: 900;
            background: linear-gradient(90deg, #2D7A08, var(--g), #8ED63A);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: .3px; margin-top: 2px;
        }
        .tb-right { display:flex; align-items:center; gap:12px; }
        .live-pill {
            display:inline-flex; align-items:center; gap:5px;
            padding: 5px 12px; border-radius: 20px;
            background: linear-gradient(135deg, var(--gl), #DAFAB0);
            border: 1px solid var(--bd2);
            font-size: 10px; font-weight: 800; color: var(--gd);
            letter-spacing: 1.5px;
        }
        .live-dot { width:6px; height:6px; border-radius:50%; background:var(--g); animation: blink 1.5s ease-in-out infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }
        .greeting { font-size:13px; color:var(--tx2); font-weight:500; }

        /* ─── SAKURA ─── */
        .sakura-bar {
            background: linear-gradient(90deg, #fff8fa, #fff, #f2fde8, #fff, #fff8fa);
            border-bottom: 1px solid #F0EBF8;
            padding: 7px 24px;
            display: flex; align-items: center; gap: 6px;
        }
        .petal { font-size:16px; animation: drift 5s ease-in-out infinite; display:inline-block; }
        .petal:nth-child(2){animation-delay:1s} .petal:nth-child(3){animation-delay:2s}
        .petal:nth-child(4){animation-delay:3s} .petal:nth-child(5){animation-delay:4s}
        @keyframes drift { 0%,100%{transform:translateY(0) rotate(0)} 50%{transform:translateY(-4px) rotate(10deg)} }
        .sakura-text { font-size:10px; color:#C8DFA0; letter-spacing:2.5px; font-weight:700; margin-left:8px; }

        /* ─── CONTENT ─── */
        .content { flex:1; overflow-y:auto; padding: 22px 24px; }

        /* ─── ALERT ─── */
        .alert-ok {
            background: var(--gll);
            border-left: 3px solid var(--g);
            border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .alert-ok p { font-size:13px; color:var(--gd); font-weight:600; }

        /* ─── CARD ─── */
        .form-card {
            background: var(--card);
            border: 1.5px solid var(--bd);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 4px 28px rgba(107,191,31,.08), 0 1px 4px rgba(0,0,0,.04);
        }
        .card-rainbow { height:4px; background:linear-gradient(90deg, #3A9A12 0%, #6BBF1F 30%, #B5E55A 55%, #FF8FB1 78%, #A78BFA 100%); }
        .card-header {
            padding: 18px 24px; border-bottom: 1.5px solid var(--gl);
            background: linear-gradient(135deg, var(--gll) 0%, #fff 100%);
            display: flex; align-items: center; gap: 14px;
        }
        .card-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--gl), #CCEF90);
            border: 1.5px solid var(--bd2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            box-shadow: 0 2px 8px rgba(107,191,31,.15);
        }
        .card-title { font-size:15px; font-weight:900; color:var(--tx); }
        .card-sub { font-size:12px; color:var(--tx3); margin-top:3px; }

        /* ─── FORM BODY ─── */
        .form-body { padding: 22px 24px; display:flex; flex-direction:column; gap:20px; }

        .section-heading {
            font-size:10px; font-weight:800; letter-spacing:3px;
            color: var(--tx3); text-transform:uppercase;
            display:flex; align-items:center; gap:8px;
        }
        .section-heading::before,.section-heading::after {
            content:''; flex:1; height:1.5px;
            background: linear-gradient(90deg, transparent, var(--bd));
        }
        .section-heading::before { background: linear-gradient(90deg, var(--bd), transparent); }

        .grid2 { display:grid; grid-template-columns:1fr 1fr; gap:18px; }

        .field-label {
            display:flex; align-items:center; gap:6px;
            font-size:12px; font-weight:700; color:var(--tx); margin-bottom:8px;
        }
        .field-label svg { width:14px; height:14px; color:var(--g); }
        .field-hint { font-size:10px; color:var(--tx3); margin-bottom:8px; }

        .upload-drop {
            border: 2px dashed var(--bd);
            border-radius: 12px; padding: 26px 16px;
            text-align: center; cursor: pointer;
            background: var(--gll);
            transition: all .25s; display:block;
        }
        .upload-drop:hover {
            border-color: var(--g);
            background: #EAFFC8;
            box-shadow: 0 0 0 4px rgba(107,191,31,.08);
        }
        .drop-icon {
            width:40px; height:40px; color:var(--bd2);
            margin:0 auto 10px; transition: all .2s;
        }
        .upload-drop:hover .drop-icon { color:var(--g); transform:scale(1.1); }
        .drop-text { font-size:12px; color:var(--tx3); line-height:1.7; font-weight:500; }
        .upload-drop:hover .drop-text { color:var(--gd); }

        .preview-box {
            border-radius:12px; overflow:hidden;
            border:1.5px solid var(--bd); margin-top:10px; position:relative;
        }
        .preview-box img { width:100%; height:200px; object-fit:cover; display:block; }
        .preview-del {
            position:absolute; top:8px; right:8px;
            background:rgba(255,143,177,.9); color:#fff;
            border:none; border-radius:7px; padding:7px; cursor:pointer;
            opacity:0; transition:opacity .2s;
        }
        .preview-box:hover .preview-del { opacity:1; }

        .dali-input {
            width:100%;
            background: var(--gll);
            border: 1.5px solid var(--bd);
            border-radius: 10px; padding: 11px 14px;
            font-size:13px; font-family:"Be Vietnam Pro",sans-serif;
            color: var(--tx); font-weight:500;
            outline:none; transition: all .2s;
        }
        .dali-input::placeholder { color:var(--tx3); }
        .dali-input:focus {
            border-color: var(--g);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(107,191,31,.12);
        }
        .field-note { font-size:10px; color:var(--tx3); margin-top:5px; }

        .divider {
            height:1.5px;
            background:linear-gradient(90deg, transparent, var(--bd) 25%, var(--bd) 75%, transparent);
        }

        /* ─── BUTTONS ─── */
        .btn-save {
            display:inline-flex; align-items:center; gap:8px;
            padding:12px 28px;
            background:linear-gradient(135deg, #3A9A12, #6BBF1F);
            color:#fff; font-size:14px; font-weight:800;
            border:none; border-radius:11px; cursor:pointer;
            box-shadow: 0 4px 18px rgba(107,191,31,.35);
            transition: all .2s; position:relative; overflow:hidden;
        }
        .btn-save::after {
            content:'';
            position:absolute; top:0; left:-100%; width:100%; height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);
            transition: left .45s;
        }
        .btn-save:hover::after { left:100%; }
        .btn-save:hover {
            background:linear-gradient(135deg, #2E7D08, #5AAF15);
            transform:translateY(-2px);
            box-shadow: 0 7px 22px rgba(107,191,31,.45);
        }
        .btn-save svg { width:16px; height:16px; }

        .btn-cancel {
            display:inline-flex; align-items:center;
            padding:12px 24px;
            background:#fff; border:1.5px solid var(--bd);
            color:var(--tx3); font-size:13px; font-weight:600;
            border-radius:11px; cursor:pointer; transition:all .2s;
        }
        .btn-cancel:hover { border-color:var(--pk); color:var(--pk); background:var(--pkl); }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        @keyframes slideL { from{opacity:0;transform:translateX(-18px)} to{opacity:1;transform:translateX(0)} }
        @keyframes slideD { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:translateY(0)} }
        .anim-up  { animation: fadeUp .35s ease-out; }
        .anim-l   { animation: slideL .3s ease-out; }
        .anim-d   { animation: slideD .3s ease-out; }

        /* ─── MOBILE ─── */
        @media(max-width:768px){
            .sidebar{position:fixed;inset:0;z-index:40;transform:translateX(-100%);transition:transform .3s}
            .sidebar.open{transform:translateX(0)}
            .mob-toggle{display:block}
            .grid2{grid-template-columns:1fr}
            .content{padding:16px}
            .form-body{padding:16px}
            .topbar{padding:0 16px}
        }
        .mob-toggle{display:none;background:none;border:none;color:var(--tx2);cursor:pointer;padding:4px}
    </style>
</head>
<body>

<div class="flex" style="min-height:100vh" x-data="{ open: false }">

    <!-- ═══ SIDEBAR ═══ -->
    @include('admin.partials.sidebar')

    <div style="flex:1;display:flex;flex-direction:column;overflow:hidden">

        <!-- Topbar -->
        <header class="topbar anim-d">
            <div style="display:flex;align-items:center;gap:14px">
                <button @click="open=!open" class="mob-toggle" aria-label="Menu">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <div class="tb-bc">Trang chủ › <span>Màn hình chính</span></div>
                    <div class="tb-title">Chỉnh sửa Hero Section</div>
                </div>
            </div>
            <div class="tb-right">
                <div class="live-pill"><span class="live-dot"></span>TRỰC TIẾP</div>
                <div class="greeting">👋 Xin chào, Admin!</div>
            </div>
        </header>

        <!-- Sakura strip -->
        <div class="sakura-bar">
            <span class="petal">🌸</span>
            <span class="petal">✿</span>
            <span class="petal">🍃</span>
            <span class="petal">🌸</span>
            <span class="petal">✦</span>
            <span class="sakura-text">DALI · TÔ ĐIỂM CUỘC SỐNG</span>
        </div>

        <!-- Content -->
        <main class="content">

            @if(session('success'))
            <div class="alert-ok anim-up" x-data="{show:true}" x-show="show">
                <div style="display:flex;align-items:center;gap:10px">
                    <svg width="18" height="18" fill="currentColor" style="color:var(--g);flex-shrink:0" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <p>{{ session('success') }}</p>
                </div>
                <button @click="show=false" style="background:none;border:none;color:var(--g);cursor:pointer">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            @endif

            <!-- Form card -->
            <div class="form-card anim-up">
                <div class="card-rainbow"></div>
                <div class="card-header">
                    <div class="card-icon">🖼️</div>
                    <div>
                        <div class="card-title">Cập nhật Hero Section</div>
                        <div class="card-sub">Thay đổi hình ảnh và nội dung hiển thị trên trang chủ</div>
                    </div>
                </div>

                <form action="{{ route('admin.hero.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">

                        <!-- HÌNH ẢNH -->
                        <div class="section-heading">🖼️ Hình ảnh</div>

                        <div class="grid2">
                            <!-- Ảnh chính -->
                            <div>
                                <div class="field-label">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>
                                    Ảnh Hero Chính
                                </div>
                                <div class="field-hint">Định dạng JPG, PNG hoặc GIF · Tối đa 5MB</div>
                                <input type="file" id="main_image" name="main_image" accept="image/*" style="display:none" onchange="xemTruoc(this,'prev_main')">
                                <label for="main_image" class="upload-drop">
                                    <svg class="drop-icon" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 20m0 0l-3.172-3.172a4 4 0 00-5.656 0l-10.172 10.172"/>
                                    </svg>
                                    <div class="drop-text">
                                        Nhấn vào đây hoặc kéo thả file<br>
                                        <span style="font-size:11px;opacity:.6">để tải ảnh lên</span>
                                    </div>
                                </label>
                                @if($hero->main_image)
                                <div class="preview-box" id="prev_main">
                                    <img src="{{ asset('storage/'.$hero->main_image) }}" alt="Ảnh chính">
                                    <button type="button" onclick="xoaAnh('main_image','prev_main')" class="preview-del">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </button>
                                </div>
                                @else
                                <div id="prev_main"></div>
                                @endif
                            </div>

                            <!-- Ảnh nổi -->
                            <div>
                                <div class="field-label">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>
                                    Ảnh Hero Nổi
                                </div>
                                <div class="field-hint">Ảnh phụ hiển thị nổi · Tối đa 5MB</div>
                                <input type="file" id="float_image" name="float_image" accept="image/*" style="display:none" onchange="xemTruoc(this,'prev_float')">
                                <label for="float_image" class="upload-drop">
                                    <svg class="drop-icon" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 20m0 0l-3.172-3.172a4 4 0 00-5.656 0l-10.172 10.172"/>
                                    </svg>
                                    <div class="drop-text">
                                        Nhấn vào đây hoặc kéo thả file<br>
                                        <span style="font-size:11px;opacity:.6">để tải ảnh lên</span>
                                    </div>
                                </label>
                                @if($hero->float_image)
                                <div class="preview-box" id="prev_float">
                                    <img src="{{ asset('storage/'.$hero->float_image) }}" alt="Ảnh nổi">
                                    <button type="button" onclick="xoaAnh('float_image','prev_float')" class="preview-del">
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </button>
                                </div>
                                @else
                                <div id="prev_float"></div>
                                @endif
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- NỘI DUNG CHỮ -->
                        <div class="section-heading">✏️ Nội dung chữ</div>

                        <div class="grid2">
                            <div>
                                <div class="field-label">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"/><path d="M10 15a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2h-6a2 2 0 01-2-2v-2z"/></svg>
                                    Tiêu đề nổi bật
                                </div>
                                <input type="text" id="tag_text" name="tag_text"
                                    value="{{ $hero->tag_text ?? '' }}"
                                    placeholder="Ví dụ: Giao toàn quốc"
                                    class="dali-input">
                                <div class="field-note">Dòng chữ lớn hiển thị trên hero section</div>
                            </div>
                            <div>
                                <div class="field-label">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V5z"/><path d="M10 15a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2h-6a2 2 0 01-2-2v-2z"/></svg>
                                    Chú thích phụ
                                </div>
                                <input type="text" id="tag_subtext" name="tag_subtext"
                                    value="{{ $hero->tag_subtext ?? '' }}"
                                    placeholder="Ví dụ: ⚡ 2–3 ngày"
                                    class="dali-input">
                                <div class="field-note">Dòng nhỏ bên dưới tiêu đề</div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- ACTIONS -->
                        <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
                            <button type="submit" class="btn-save">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                Lưu thay đổi
                            </button>
                            <button type="button" onclick="window.history.back()" class="btn-cancel">
                                Huỷ bỏ
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </main>
    </div>

    <!-- Mobile overlay -->
    <div @click="open=false" x-show="open" style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:30;display:none"></div>
</div>

<script>
function xemTruoc(input, previewId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById(previewId).innerHTML = `
            <div class="preview-box">
                <img src="${e.target.result}" alt="Xem trước" style="width:100%;height:200px;object-fit:cover;display:block">
            </div>`;
    };
    reader.readAsDataURL(input.files[0]);
}
function xoaAnh(inputId, previewId) {
    document.getElementById(inputId).value = '';
    document.getElementById(previewId).innerHTML = '';
}
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('[x-data*="show:true"]').forEach(el => el.style.display = 'none');
    }, 5000);
});
</script>
</body>
</html>