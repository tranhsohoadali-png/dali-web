<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Công cụ tách màu | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro','Segoe UI',sans-serif}
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A;--bd:#C8E89A}
html,body{height:100%;overflow:hidden}
body{background:var(--bg);color:var(--tx)}
.layout{display:flex;height:100vh;overflow:hidden}
.main{flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden}

/* ── Topbar ── */
.tb{background:#fff;border-bottom:2px solid var(--gl);height:58px;padding:0 20px;
  display:flex;align-items:center;justify-content:space-between;flex-shrink:0;
  box-shadow:0 1px 8px rgba(107,191,31,.07)}
.tb-left{display:flex;align-items:center;gap:10px}
.tb-bc{font-size:10px;color:var(--tx3);letter-spacing:.4px}
.tb-bc b{color:var(--g)}
.tb-title{font-size:15px;font-weight:900;color:var(--char);display:flex;align-items:center;gap:7px}
.tb-right{display:flex;align-items:center;gap:8px}
.pill{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:20px;
  font-size:11px;font-weight:700;letter-spacing:.5px;border:1px solid}
.pill-ok{background:#E8F9D0;border-color:var(--bd);color:var(--gd)}
.pill-off{background:#FFF0F5;border-color:#FECDD3;color:#E11D48}
.dot{width:6px;height:6px;border-radius:50%}
.dot-ok{background:var(--g);animation:blink 1.4s ease-in-out infinite}
.dot-off{background:#E11D48}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}
.btn{display:inline-flex;align-items:center;gap:6px;background:var(--g);color:#fff;
  text-decoration:none;padding:8px 14px;border-radius:9px;font-weight:700;font-size:12px;
  border:none;cursor:pointer;transition:all .2s}
.btn:hover{background:var(--gd);transform:translateY(-1px)}
.btn-out{background:transparent;color:var(--gd);border:1.5px solid var(--bd)}
.btn-out:hover{background:var(--gl)}

/* ── Frame area ── */
.frame-wrap{flex:1;position:relative;overflow:hidden;background:#fff}
.tool-frame{width:100%;height:100%;border:0;display:block}

/* ── Offline state ── */
.tool-off{
  position:absolute;inset:0;display:none;
  flex-direction:column;align-items:center;justify-content:center;
  text-align:center;padding:40px;background:var(--gll);
}
.off-icon{font-size:60px;margin-bottom:18px;opacity:.7}
.off-title{font-size:20px;font-weight:900;color:var(--char);margin-bottom:10px}
.off-desc{font-size:13.5px;color:var(--tx2);line-height:1.75;max-width:480px;margin:0 auto 22px}
code{background:var(--gl);border:1px solid var(--bd);padding:2px 8px;border-radius:6px;
  font-size:12px;font-family:monospace;color:var(--char)}
.steps{background:#fff;border:1.5px solid var(--bd);border-radius:12px;padding:16px 20px;
  text-align:left;max-width:400px;margin:0 auto 20px;font-size:13px;color:var(--tx)}
.steps li{padding:5px 0;border-bottom:1px dashed var(--bd)}
.steps li:last-child{border-bottom:none}
.steps b{color:var(--gd)}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">

    {{-- Topbar --}}
    <div class="tb">
      <div class="tb-left">
        <div>
          <div class="tb-bc">Admin › <b>Tách màu & Mã DALI</b></div>
          <div class="tb-title">
            <i class="ri-palette-fill" style="color:var(--g)"></i>
            Công cụ tách màu + khớp mã DALI
          </div>
        </div>
      </div>
      <div class="tb-right">
        <span class="pill pill-off" id="statusPill">
          <span class="dot dot-off"></span> Chưa kết nối
        </span>
        <a href="{{ $toolUrl }}" target="_blank" rel="noopener" class="btn btn-out">
          <i class="ri-external-link-line"></i> Mở tab mới
        </a>
        <button onclick="reloadFrame()" class="btn">
          <i class="ri-refresh-line"></i> Kết nối lại
        </button>
      </div>
    </div>

    {{-- Iframe nhúng tool qua proxy (tránh mixed-content HTTPS/HTTP) --}}
    <div class="frame-wrap">
      <iframe
        src="{{ $proxyUrl }}"
        class="tool-frame"
        id="toolFrame"
        allow="clipboard-write; downloads"
        sandbox="allow-scripts allow-same-origin allow-forms allow-downloads allow-popups"
      ></iframe>

      {{-- Hiện khi tool chưa chạy --}}
      <div class="tool-off" id="toolOff">
        <div class="off-icon">🎨</div>
        <div class="off-title">Công cụ tách màu chưa chạy</div>
        <div class="off-desc">
          Công cụ Django chạy cục bộ tại <code>http://127.0.0.1:18001</code>.
          Cần bật công cụ trước khi dùng trong admin.
        </div>
        <ol class="steps">
          <li>Mở thư mục <b>index_color_dali_merged</b> trên máy tính</li>
          <li>Chạy file <b>run.bat</b> (cửa sổ đen hiện ra là OK)</li>
          <li>Bấm nút <b>"Kết nối lại"</b> bên trên</li>
        </ol>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
          <button onclick="reloadFrame()" class="btn">
            <i class="ri-refresh-line"></i> Kết nối lại
          </button>
          <a href="{{ $toolUrl }}" target="_blank" rel="noopener" class="btn btn-out">
            <i class="ri-external-link-line"></i> Mở trực tiếp tại 18001
          </a>
          <a href="{{ route('admin.settings') }}" class="btn btn-out">
            <i class="ri-settings-3-line"></i> Thay đổi URL tool
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
var frame  = document.getElementById('toolFrame');
var off    = document.getElementById('toolOff');
var pill   = document.getElementById('statusPill');
var timer  = null;
var loaded = false;

function setOnline(){
  loaded = true;
  off.style.display = 'none';
  pill.className = 'pill pill-ok';
  pill.innerHTML = '<span class="dot dot-ok"></span> Đang kết nối';
}
function setOffline(){
  loaded = false;
  off.style.display = 'flex';
  pill.className = 'pill pill-off';
  pill.innerHTML = '<span class="dot dot-off"></span> Chưa kết nối';
}

frame.addEventListener('load', function(){
  // Nếu iframe tải nhưng tool offline → controller trả HTML offline page (503)
  // Kiểm tra bằng cách thử fetch /admin/cong-cu-tach-mau/proxy/result
  // Nếu OK thì online
  clearTimeout(timer);
  // iframe load thành công → thử ping tool
  fetch('{{ $proxyUrl }}', {method:'HEAD'})
    .then(function(r){ r.ok ? setOnline() : setOffline(); })
    .catch(function(){ setOffline(); });
});

frame.addEventListener('error', function(){ setOffline(); });

// Timeout 8s nếu iframe không trigger load
timer = setTimeout(function(){ if(!loaded) setOffline(); }, 8000);

function reloadFrame(){
  loaded = false;
  pill.className = 'pill pill-off';
  pill.innerHTML = '<span class="dot dot-off" style="animation:blink 0.5s infinite"></span> Đang kết nối...';
  off.style.display = 'none';
  frame.src = frame.src.split('?')[0] + '?t=' + Date.now();
  clearTimeout(timer);
  timer = setTimeout(function(){ if(!loaded) setOffline(); }, 8000);
}
</script>
</body>
</html>
