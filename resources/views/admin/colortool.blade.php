<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Công cụ tách màu | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro','Segoe UI',sans-serif}
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
html,body{height:100%}
body{background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh;height:100vh}
.main{flex:1;display:flex;flex-direction:column;min-width:0}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:17px;font-weight:800;color:var(--char)}
.frame-wrap{flex:1;position:relative;background:#fff;min-height:0}
.tool-frame{width:100%;height:100%;border:0;display:block}
.tool-off{position:absolute;inset:0;display:none;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:30px;color:var(--tx2);background:var(--gll)}
.btn{display:inline-block;background:var(--g);color:#fff;text-decoration:none;padding:10px 20px;border-radius:10px;font-weight:700;font-size:13px;border:none;cursor:pointer;transition:all .2s}
.btn:hover{background:var(--gd)}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div>
        <div class="tb-bc">Admin › <b>Công cụ tách màu</b></div>
        <div class="tb-title">Tạo bản đồ màu đánh số + mã DALI</div>
      </div>
      <a href="{{ $toolUrl }}" target="_blank" rel="noopener" class="btn" style="font-size:12px;padding:8px 16px">Mở tab mới ↗</a>
    </div>
    <div class="frame-wrap">
      <iframe src="{{ $toolUrl }}" class="tool-frame" id="toolFrame" allow="clipboard-write; downloads"></iframe>
      <div class="tool-off" id="toolOff">
        <div style="font-size:44px;margin-bottom:14px">🎨</div>
        <h3 style="font-size:18px;color:var(--char);margin-bottom:8px">Chưa kết nối được công cụ tách màu</h3>
        <p style="font-size:13px;max-width:460px;line-height:1.7;margin-bottom:18px">
          Hãy bật công cụ: chạy <b>run.bat</b> (Django, cổng 18001) trên máy đặt công cụ.
          Nếu chạy ở địa chỉ khác, cập nhật trong
          <a href="{{ route('admin.settings') }}" style="color:var(--g);font-weight:700">Cài đặt → Công cụ tách màu</a>.
        </p>
        <a href="{{ $toolUrl }}" target="_blank" rel="noopener" class="btn">Thử mở trực tiếp ↗</a>
      </div>
    </div>
  </div>
</div>
<script>
// Nếu iframe không tải được trong 7s (công cụ chưa chạy) → hiện hướng dẫn
(function(){
  var f=document.getElementById('toolFrame'), off=document.getElementById('toolOff'), loaded=false;
  f.addEventListener('load', function(){ loaded=true; off.style.display='none'; });
  setTimeout(function(){ if(!loaded){ off.style.display='flex'; } }, 7000);
})();
</script>
</body>
</html>
