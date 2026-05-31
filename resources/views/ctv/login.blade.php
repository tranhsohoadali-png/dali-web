<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đăng nhập CTV | DALI</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gn:#C6F135;--bd:#D0EAA8;--bg:#F0FBE4;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8EC860}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);min-height:100vh;display:flex;flex-direction:column}
/* top stripe */
.stripe{height:5px;background:linear-gradient(90deg,#1A4D00,var(--g),var(--gn),var(--g),#1A4D00)}
.wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:20px}
.box{width:100%;max-width:420px}
/* hero logo */
.logo-area{text-align:center;margin-bottom:28px}
.logo-icon{width:70px;height:70px;background:linear-gradient(135deg,#1A4D00,#3A9A12);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;box-shadow:0 8px 24px rgba(58,154,18,.35)}
.logo-icon img{width:44px;height:44px;object-fit:contain;filter:brightness(0) invert(1)}
.logo-text{font-size:28px;font-weight:900;letter-spacing:3px;color:var(--gd)}
.logo-text span{color:var(--g)}
.logo-sub{font-size:12px;color:var(--tx3);font-weight:600;margin-top:3px;letter-spacing:.5px}
/* card */
.card{background:#fff;border:1.5px solid var(--bd);border-radius:22px;padding:28px 24px;box-shadow:0 4px 24px rgba(58,154,18,.1)}
.card-head{text-align:center;margin-bottom:24px}
.card-head h2{font-size:18px;font-weight:900;color:var(--gd)}
.card-head p{font-size:13px;color:var(--tx3);margin-top:4px}
.field{margin-bottom:16px}
.field label{display:block;font-size:12px;font-weight:700;color:var(--tx2);margin-bottom:6px}
.field input{width:100%;border:1.5px solid var(--bd);border-radius:12px;padding:13px 14px;font-size:14.5px;font-family:inherit;background:#F9FFF4;color:var(--tx);transition:border .2s}
.field input:focus{outline:none;border-color:var(--g);background:#fff}
.btn{width:100%;background:linear-gradient(135deg,#2D7A08,#3A9A12,var(--g));color:#fff;border:none;border-radius:50px;padding:14px;font-size:15px;font-weight:900;cursor:pointer;font-family:inherit;transition:all .2s;margin-top:4px;letter-spacing:.5px}
.btn:hover{opacity:.92;transform:translateY(-1px);box-shadow:0 6px 20px rgba(58,154,18,.35)}
.flash{padding:12px 14px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.flash.err{background:#FEE2E2;color:#991B1B;border:1px solid #FECACA}
.note{text-align:center;margin-top:18px;font-size:12.5px;color:var(--tx3)}
.note a{color:var(--g);font-weight:700;text-decoration:none}
.footer-link{text-align:center;margin-top:28px;font-size:12px;color:var(--tx3)}
.footer-link a{color:var(--tx2);font-weight:600;text-decoration:none}
</style>
</head>
<body>
<div class="stripe"></div>
<div class="wrap">
  <div class="box">
    <div class="logo-area">
      <div class="logo-icon"><img src="{{ asset('images/logo_dali.png') }}" alt="DALI"></div>
      <div class="logo-text">DAL<span>I</span></div>
      <div class="logo-sub">CỔNG CỘNG TÁC VIÊN</div>
    </div>
    <div class="card">
      <div class="card-head">
        <h2>🔐 Đăng nhập</h2>
        <p>Dành riêng cho cộng tác viên DALI</p>
      </div>
      @if(session('error'))<div class="flash err">⚠ {{ session('error') }}</div>@endif
      @if($errors->any())<div class="flash err">⚠ {{ $errors->first() }}</div>@endif
      <form method="POST" action="{{ route('ctv.login.post') }}">
        @csrf
        <div class="field">
          <label>📱 Số điện thoại</label>
          <input type="text" name="phone" value="{{ old('phone') }}" placeholder="0901 234 567" required autofocus autocomplete="username" inputmode="tel">
        </div>
        <div class="field">
          <label>🔑 Mật khẩu</label>
          <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn">Đăng nhập →</button>
      </form>
      <div class="note">
        Chưa có tài khoản? <a href="tel:{{ config('app.shop_phone','0856911698') }}">Liên hệ DALI</a> để đăng ký.
      </div>
    </div>
    <div class="footer-link">
      <a href="{{ route('home') }}">← Về trang chủ DALI</a>
    </div>
  </div>
</div>
</body>
</html>
