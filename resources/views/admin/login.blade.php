<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đăng nhập | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>

:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro','Segoe UI',sans-serif}
body{background:var(--bg);color:var(--tx)}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc a{color:var(--g);text-decoration:none;font-weight:600}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;font-size:12px;flex-shrink:0}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:var(--gd)}
.alert-err{background:#FFF0F0;border-left:3px solid #EF4444;border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:#991B1B}
.card{background:#fff;border-radius:16px;border:0.5px solid var(--bd);overflow:hidden;margin-bottom:14px;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-h{padding:14px 22px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;justify-content:space-between}
.card-t{font-size:14px;font-weight:900;color:var(--char)}
.btn-add{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:9px;cursor:pointer;text-decoration:none;box-shadow:0 3px 12px rgba(107,191,31,.3);transition:all .2s}
.btn-add:hover{transform:translateY(-1px);box-shadow:0 5px 18px rgba(107,191,31,.4)}
.btn-back{padding:9px 18px;background:#fff;border:1.5px solid var(--bd);color:var(--tx3);font-size:13px;font-weight:600;border-radius:9px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:all .2s}
.btn-back:hover{border-color:var(--g);color:var(--g)}
.btn-save{display:inline-flex;align-items:center;gap:7px;padding:11px 26px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;font-size:13px;font-weight:800;border:none;border-radius:10px;cursor:pointer;transition:all .2s}
.btn-save:hover{transform:translateY(-1px)}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:11px 14px;background:var(--gll);border-bottom:1px solid var(--bd);text-align:left}
td{padding:12px 14px;border-bottom:0.5px solid var(--gl);font-size:13px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.badge-g{display:inline-flex;align-items:center;gap:4px;background:var(--gl);color:var(--gd);font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px}
.badge-g::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--g)}
.flabel{font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:7px}
.req{color:var(--pk)}
.dinput,.dselect,.dtextarea{width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:10px;padding:11px 14px;font-size:13px;color:var(--tx);font-weight:500;outline:none;transition:all .2s;font-family:inherit}
.dinput:focus,.dselect:focus,.dtextarea:focus{border-color:var(--g);background:#fff;box-shadow:0 0 0 3px rgba(107,191,31,.1)}
.dtextarea{resize:vertical;min-height:80px}
.divider{height:1.5px;background:linear-gradient(90deg,transparent,var(--bd) 25%,var(--bd) 75%,transparent);margin:8px 0 18px}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:14px}
.grid3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px}
.grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px}
.f1{margin-bottom:14px}
.fnote{font-size:10px;color:var(--tx3);margin-top:4px}
.stat-card{background:#fff;border-radius:12px;border:0.5px solid var(--bd);padding:16px 18px}
.stat-num{font-size:26px;font-weight:900;margin-bottom:3px}
.stat-label{font-size:12px;color:var(--tx3);font-weight:500}
.fb{padding:20px 22px}
.sec-title{font-size:10px;font-weight:800;letter-spacing:3px;color:var(--tx3);text-transform:uppercase;display:flex;align-items:center;gap:8px;margin-bottom:14px}
.sec-title::before,.sec-title::after{content:'';flex:1;height:1.5px;background:linear-gradient(90deg,transparent,var(--bd))}
.sec-title::before{background:linear-gradient(90deg,var(--bd),transparent)}

.login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1C5200,#2D7A08,#3A9A12);padding:20px}
.login-card{background:#fff;border-radius:20px;width:100%;max-width:400px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2)}
.login-logo{padding:32px 32px 24px;text-align:center;background:linear-gradient(135deg,#1C5200,#2D7A08)}
.login-logo img{height:48px;filter:brightness(0) invert(1);display:block;margin:0 auto 10px}
.login-title{font-size:13px;color:rgba(255,255,255,.6);letter-spacing:2px}
.login-body{padding:28px 32px 32px}
.login-h{font-size:20px;font-weight:900;color:var(--char);margin-bottom:6px}
.login-sub{font-size:13px;color:var(--tx3);margin-bottom:24px}
.err-msg{font-size:12px;color:#EF4444;margin-top:5px;font-weight:600}
.btn-login{width:100%;padding:13px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:11px;font-size:15px;font-weight:800;cursor:pointer;margin-top:8px;transition:all .2s}
.btn-login:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(107,191,31,.4)}
.login-hint{margin-top:16px;text-align:center;font-size:12px;color:var(--tx3);background:var(--gll);border-radius:9px;padding:10px;border:1px solid var(--bd)}
</style>
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="login-logo">
      <img src="{{ asset('images/logo_dali.png') }}" alt="DALI">
      <div class="login-title">ADMIN PANEL</div>
    </div>
    <div class="login-body">
      <div class="login-h">Đăng nhập quản trị</div>
      <div class="login-sub">Nhập mật khẩu để truy cập trang admin</div>
      <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="f1">
          <label class="flabel">Mật khẩu <span class="req">*</span></label>
          <input type="password" name="password" class="dinput" placeholder="Nhập mật khẩu..." autofocus>
          @error('password')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn-login">Đăng nhập →</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>