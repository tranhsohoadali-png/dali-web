<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Cổng seller | DALI POD</title>
<link rel="icon" href="/favicon.ico">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Pacifico&family=Lobster&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#2F6B05;--gdd:#14320A;--ink:#16300A;--mu:#5B6B52;--bg:#F7FBF2;--gl:#EEF8E2;--gll:#F6FCEE;--bd:#DCEFC6;--pk:#B4235A;--pkl:#FDE6EE;--r:14px}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',system-ui,sans-serif;background:var(--bg);color:var(--ink);font-size:15px;line-height:1.5}
button,input,select,textarea{font:inherit;color:inherit}
a{color:var(--gd)}
.hide{display:none!important}
svg.ic{width:18px;height:18px;flex:none;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
/* đầu trang */
.dau{position:sticky;top:0;z-index:20;background:#fff;border-bottom:1px solid var(--bd);height:60px;display:flex;align-items:center;justify-content:space-between;padding:0 18px;gap:12px}
.logo{display:flex;align-items:center;gap:8px;text-decoration:none}
.logo img{height:28px;width:auto}
.logo span{font-size:11px;font-weight:800;letter-spacing:1.4px;color:#fff;background:var(--gd);border-radius:6px;padding:2px 6px}
.logo b{color:var(--mu);font-weight:600;font-size:14px;margin-left:4px}
.ai{display:flex;align-items:center;gap:10px;font-size:14px}
.vi{display:flex;align-items:center;gap:6px;background:var(--gl);color:var(--gd);border:none;border-radius:20px;padding:6px 12px;font-weight:700;cursor:pointer;white-space:nowrap}
.ten{color:var(--mu)}
@media(max-width:640px){.ten,.logo b{display:none}}
/* bố cục */
.khung{display:flex;min-height:calc(100vh - 60px)}
.ben{width:236px;flex:none;background:#fff;border-right:1px solid var(--bd);padding:16px 12px;display:flex;flex-direction:column;gap:4px;position:sticky;top:60px;height:calc(100vh - 60px)}
.ben a{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:11px;text-decoration:none;color:var(--ink);font-weight:600;font-size:14.5px}
.ben a:hover{background:var(--gll)}
.ben a.on{background:var(--gl);color:var(--gd)}
.ben a.rieng.on{background:var(--pkl);color:var(--pk)}
.ben .dem{margin-left:auto;background:var(--g);color:#fff;border-radius:20px;font-size:11px;font-weight:800;padding:1px 8px}
.ben .nhom{font-size:11px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#9AAE8C;padding:14px 12px 4px}
.ben .duoi{margin-top:auto;font-size:13px;color:var(--mu);padding:12px;border-top:1px solid var(--gl)}
main{flex:1;min-width:0;padding:26px 28px 70px;max-width:1100px}
.tabs{display:none}
@media(max-width:900px){
  .ben{display:none}
  .tabs{display:flex;gap:4px;overflow-x:auto;background:#fff;border-bottom:1px solid var(--bd);padding:0 10px;position:sticky;top:60px;z-index:15}
  .tabs a{padding:11px 12px;text-decoration:none;color:var(--mu);font-weight:600;font-size:14px;border-bottom:2px solid transparent;white-space:nowrap}
  .tabs a.on{color:var(--gd);border-bottom-color:var(--g)}
  main{padding:18px 16px 60px}
}
/* thành phần */
h2{font-size:22px;letter-spacing:-.2px;margin-bottom:4px}
.sub{color:var(--mu);font-size:14px;margin-bottom:18px}
.box{background:#fff;border:1px solid var(--bd);border-radius:var(--r);padding:18px;margin-bottom:14px}
.btn{background:var(--g);color:#fff;border:none;border-radius:11px;padding:11px 20px;font-weight:700;cursor:pointer}
.btn:hover{background:var(--gd)}
.btn:disabled{opacity:.5;cursor:default}
.btn2{background:#fff;color:var(--gd);border:1px solid var(--bd);border-radius:11px;padding:8px 14px;font-weight:600;cursor:pointer}
.btn-del{color:#B91C1C;border-color:#FECACA}
label.f{display:flex;flex-direction:column;gap:6px;font-size:13px;font-weight:600;color:var(--mu)}
label.f input,label.f select,label.f textarea{border:1.5px solid var(--bd);border-radius:11px;padding:10px 12px;font-size:15px;background:var(--gll);font-weight:400;color:var(--ink);outline:none}
label.f input:focus,label.f select:focus,label.f textarea:focus{border-color:var(--g);background:#fff}
.row{display:grid;gap:14px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
.msg{border-radius:12px;padding:12px 16px;margin-bottom:16px;font-size:14px}
.msg.ok{background:var(--gll);color:var(--gd);border:1px solid var(--bd)}
.msg.err{background:#FEF2F2;color:#B91C1C;border:1px solid #FECACA}
.timbox{position:relative;margin-bottom:16px}
.timbox input{width:100%;border:1.5px solid var(--bd);border-radius:12px;padding:11px 14px 11px 40px;background:#fff;font-size:15px;outline:none}
.timbox input:focus{border-color:var(--g)}
.timbox svg{position:absolute;left:13px;top:13px;color:var(--mu)}
.grid{display:grid;gap:16px;grid-template-columns:repeat(auto-fill,minmax(200px,1fr))}
.card{background:#fff;border:1px solid var(--bd);border-radius:var(--r);overflow:hidden;cursor:pointer;display:flex;flex-direction:column;transition:transform .12s,box-shadow .12s}
.card:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(20,50,10,.09)}
.card .img{aspect-ratio:1;background:var(--gll);display:flex;align-items:center;justify-content:center;color:var(--mu);font-size:12px;position:relative}
.card .img img{width:100%;height:100%;object-fit:cover}
.card .bd{padding:11px 13px}
.card b{display:block;font-size:14.5px;line-height:1.35}
.tag{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;border-radius:20px;padding:2px 9px}
.t-rieng{background:var(--pkl);color:var(--pk)}.t-chung{background:#E0F2FE;color:#0369A1}
.card .img .tag{position:absolute;top:10px;left:10px}
.st{font-size:12px;font-weight:700;border-radius:20px;padding:3px 10px;white-space:nowrap}
.st-cho_nap{background:#FEF3C7;color:#B45309}.st-cho_in{background:#E0F2FE;color:#0369A1}.st-dang_in{background:#EDE9FE;color:#6D28D9}.st-da_giao{background:var(--gl);color:var(--gd)}.st-da_huy{background:#F3F4F6;color:var(--mu)}
.mu{color:var(--mu);font-size:13px}
.gia{font-weight:800;color:var(--gd)}
table{width:100%;border-collapse:collapse}
td,th{padding:9px 6px;border-bottom:1px solid var(--gll);text-align:left;font-size:14px;vertical-align:top}
th{font-size:11px;color:var(--mu);text-transform:uppercase;letter-spacing:.6px}
.num{text-align:right;white-space:nowrap}
.trong{text-align:center;padding:36px 20px;color:var(--mu)}
.trong b{display:block;color:var(--ink);font-size:16px;margin-bottom:6px}
.bang-rieng{background:linear-gradient(160deg,#FFF5F8,#fff 60%);border:1px solid #F7D3DF;border-radius:var(--r);padding:16px 18px;margin-bottom:18px;display:flex;gap:12px;align-items:flex-start}
.bang-rieng svg{color:var(--pk);margin-top:2px}
/* hộp chi tiết mẫu */
.modal{position:fixed;inset:0;background:rgba(20,40,10,.5);z-index:50;display:flex;align-items:flex-start;justify-content:center;overflow-y:auto;padding:24px 12px}
.mbox{background:#fff;border-radius:20px;max-width:820px;width:100%;padding:22px;position:relative}
.mx{position:absolute;top:10px;right:14px;border:none;background:none;font-size:26px;cursor:pointer;color:var(--mu);line-height:1}
.gal .main{width:100%;aspect-ratio:1;object-fit:contain;background:var(--gll);border-radius:14px}
.thumbs{display:flex;gap:6px;flex-wrap:wrap;margin-top:8px}.thumbs img{width:58px;height:58px;object-fit:cover;border-radius:9px;border:2px solid transparent;cursor:pointer}.thumbs img.on{border-color:var(--g)}
.two{display:grid;gap:22px;grid-template-columns:1fr}
@media(min-width:720px){.two{grid-template-columns:320px 1fr}}
.opt{display:flex;justify-content:space-between;gap:10px;border:1.5px solid var(--bd);border-radius:11px;padding:10px 13px;margin-bottom:7px;cursor:pointer}
.nhom-chon{margin-top:12px}.nhom-chon .tieu{font-weight:700;font-size:13px;margin-bottom:6px}
.chips{display:flex;flex-wrap:wrap;gap:7px}
.chon{display:inline-flex;align-items:center;gap:6px;border:1.5px solid var(--bd);background:#fff;border-radius:10px;padding:7px 12px;font-size:14px;font-weight:600;cursor:pointer}
.chon.on{border-color:var(--g);background:var(--gll);box-shadow:0 0 0 2px var(--gl)}
.chon .cham{width:16px;height:16px;border-radius:50%;border:1px solid rgba(0,0,0,.15);display:inline-block}
.gia-chon{display:flex;justify-content:space-between;align-items:flex-end;gap:12px;margin-top:14px;padding:12px 14px;background:var(--gll);border-radius:12px}
.gia-chon b{font-size:24px;color:var(--gd)}
.opt.on{border-color:var(--g);background:var(--gll)}
/* ví */
.du{background:linear-gradient(130deg,var(--gd),var(--g));color:#fff;border-radius:18px;padding:22px 24px;margin-bottom:14px}
.du .so{font-size:34px;font-weight:800;line-height:1.1;margin-top:4px}
.qr{width:210px;max-width:100%;border:1px solid var(--bd);border-radius:14px;background:#fff}
.copy{display:flex;align-items:center;gap:8px;justify-content:space-between;border-bottom:1px solid var(--gll);padding:9px 0}
/* đăng nhập */
.vao{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px 16px;background:radial-gradient(900px 400px at 80% -10%,#E3F5CF,transparent 60%),var(--bg)}
.vao .box{width:100%;max-width:430px;padding:28px}
.vao .logo{margin-bottom:18px}
ol.cam{padding-left:20px;display:flex;flex-direction:column;gap:6px;font-size:14px}
</style>
</head>
<body>
<header class="dau hide" id="hdr">
  <a class="logo" href="/"><img src="/images/logo_dali.png" alt="DALI" width="62" height="28"><span>POD</span><b>Cổng seller</b></a>
  <div class="ai"><span class="ten" id="whoTen"></span><button class="vi" id="whoDu" onclick="go('vi')"></button><button class="btn2" onclick="dangXuat()">Đăng xuất</button></div>
</header>
<div class="khung hide" id="khung">
  <nav class="ben" id="ben" aria-label="Menu cổng seller">
    <div class="nhom">Kho mẫu</div>
    <a href="#chung" data-t="chung"><svg class="ic" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>Kho chung</a>
    <a href="#rieng" data-t="rieng" class="rieng"><svg class="ic" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>Mẫu độc quyền<span class="dem hide" id="riengN" style="background:var(--pk)"></span></a>
    <div class="nhom">Đơn hàng</div>
    <a href="#gio" data-t="gio"><svg class="ic" viewBox="0 0 24 24"><path d="M5 7h14l-1.5 9h-11z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>Đơn đang soạn<span class="dem hide" id="gioN"></span></a>
    <a href="#don" data-t="don"><svg class="ic" viewBox="0 0 24 24"><path d="M8 6h12M8 12h12M8 18h12"/><circle cx="4" cy="6" r="1"/><circle cx="4" cy="12" r="1"/><circle cx="4" cy="18" r="1"/></svg>Đơn của tôi</a>
    <div class="nhom">Tài khoản</div>
    <a href="#vi" data-t="vi"><svg class="ic" viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M16 12.5h2"/><path d="M3 10h18"/></svg>Ví</a>
    <a href="#noigiao" data-t="noigiao"><svg class="ic" viewBox="0 0 24 24"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>Nơi giao</a>
    <div class="duoi">Cần hỗ trợ? Zalo <a href="https://zalo.me/0856911698" rel="nofollow">0856 911 698</a><br><a href="/" style="text-decoration:none">← Về trang DALI POD</a></div>
  </nav>
  <div style="flex:1;min-width:0">
    <nav class="tabs" id="tabs" aria-label="Menu cổng seller">
      <a href="#chung" data-t="chung">Kho chung</a><a href="#rieng" data-t="rieng">Mẫu độc quyền</a><a href="#gio" data-t="gio">Đơn đang soạn <span id="gioN2"></span></a><a href="#don" data-t="don">Đơn của tôi</a><a href="#vi" data-t="vi">Ví</a><a href="#noigiao" data-t="noigiao">Nơi giao</a>
    </nav>
    <main id="main"></main>
  </div>
</div>
<div id="vao"></div>
<div class="modal hide" id="modal" onclick="if(event.target===this)dongModal()"><div class="mbox" id="mbox"></div></div>

@include('pod._ve_ten')
<script>
(function () {
  'use strict';
  var TOKEN_KEY = 'pod_token';
  var st = { me: null, mau: { chung: null, rieng: null }, mauLuc: {}, noiGiao: null, gio: [], tab: 'chung', msg: null, tim: '' };
  var IC_KHOA = '<svg class="ic" viewBox="0 0 24 24" style="width:12px;height:12px"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>';
  var IC_TIM = '<svg class="ic" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>';

  function lsGet(k) { try { return localStorage.getItem(k); } catch (e) { return null; } }
  function lsSet(k, v) { try { if (v === null) localStorage.removeItem(k); else localStorage.setItem(k, v); } catch (e) {} }
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function tien(n) { return (Number(n) || 0).toLocaleString('vi-VN') + 'đ'; }
  function gram(n) { return String(Number(n)).replace('.', ',') + ' g'; }
  function giaNhua() { var g = (st.me && st.me.gia) || {}; return 'Giá in PETG ' + tien(g.PETG) + ', PLA ' + tien(g.PLA) + ' mỗi gram'; }
  function $(id) { return document.getElementById(id); }
  var main = $('main');

  async function api(path, opt) {
    opt = opt || {};
    var h = { 'Accept': 'application/json' };
    var tk = lsGet(TOKEN_KEY); if (tk) h['X-Pod-Token'] = tk;
    var body = opt.body;
    if (body && !(body instanceof FormData)) { h['Content-Type'] = 'application/json'; body = JSON.stringify(body); }
    var r = await fetch('/api/pod/' + path, { method: opt.method || (body ? 'POST' : 'GET'), headers: h, body: body });
    var j = {}; try { j = await r.json(); } catch (e) {}
    if (r.status === 401 && path !== 'dang-nhap') { lsSet(TOKEN_KEY, null); veDangNhap('Phiên đăng nhập đã hết, vui lòng đăng nhập lại.'); throw new Error('401'); }
    if (r.status === 403 && j.can_dong_y) { veDieuKhoan(); throw new Error('403'); }
    if (!r.ok) {
      var m = j.error || j.message || ('Lỗi ' + r.status);
      if (j.errors) { var k = Object.keys(j.errors)[0]; if (k) m = j.errors[k][0]; }
      var e = new Error(m); e.api = true; throw e;
    }
    return j;
  }
  function baoLoi(e) { if (e && e.api) { st.msg = { err: e.message }; ve(); } else if (e && e.message !== '401' && e.message !== '403') { st.msg = { err: 'Không kết nối được, thử lại.' }; ve(); } }

  /* ---------- Đơn đang soạn (giỏ, lưu trên máy) ---------- */
  function gioKey() { return 'pod_gio_' + (st.me ? st.me.ma : ''); }
  function napGio() { try { st.gio = JSON.parse(lsGet(gioKey()) || '[]') || []; } catch (e) { st.gio = []; } }
  function luuGio() {
    lsSet(gioKey(), JSON.stringify(st.gio));
    var n = st.gio.length;
    $('gioN').textContent = n; $('gioN').classList.toggle('hide', !n);
    $('gioN2').textContent = n ? '(' + n + ')' : '';
  }
  function giaDong(d) { return d.gia * d.so_luong + (d.chu ? d.phi_ca_nhan : 0); }

  /* ---------- Đăng nhập + điều khoản ---------- */
  function anKhung() { $('hdr').classList.add('hide'); $('khung').classList.add('hide'); }
  var LOGO = '<a class="logo" href="/"><img src="/images/logo_dali.png" alt="DALI" width="62" height="28"><span>POD</span><b>Cổng seller</b></a>';
  function veDangNhap(loi) {
    anKhung();
    $('vao').innerHTML = '<div class="vao"><div class="box">' + LOGO + '<h2>Đăng nhập</h2><p class="sub">Dùng số điện thoại và mật khẩu của tài khoản seller.</p>' +
      (loi ? '<div class="msg err">' + esc(loi) + '</div>' : '') +
      '<form id="fDn"><label class="f">Số điện thoại<input name="sdt" required autocomplete="username" inputmode="tel"></label><br>' +
      '<label class="f">Mật khẩu<input name="matkhau" type="password" required autocomplete="current-password"></label><br>' +
      '<button class="btn" style="width:100%">Đăng nhập</button></form>' +
      '<p class="mu" style="margin-top:16px;text-align:center">Chưa có tài khoản? <a href="/dang-ky">Đăng ký seller</a> · Quên mật khẩu: Zalo 0856 911 698</p></div></div>';
    $('fDn').onsubmit = async function (ev) {
      ev.preventDefault();
      var fd = new FormData(ev.target);
      try {
        var j = await api('dang-nhap', { body: { sdt: fd.get('sdt'), matkhau: fd.get('matkhau') } });
        lsSet(TOKEN_KEY, j.token); khoiDong();
      } catch (e) { if (e.api) veDangNhap(e.message); }
    };
  }

  function veDieuKhoan() {
    anKhung();
    $('vao').innerHTML = '<div class="vao"><div class="box" style="max-width:640px">' + LOGO + '<h2>Điều khoản bảo mật thiết kế</h2><p class="sub">Đọc và đồng ý một lần trước khi dùng cổng.</p>' +
      '<b>Xưởng cam kết với seller</b><ol class="cam" style="margin:8px 0 14px">' +
      '<li>Không chia sẻ, bán lại hay đăng tải file thiết kế của seller (STL, 3MF, file gốc) cho bất kỳ bên thứ ba nào.</li>' +
      '<li>Không in và không bán sản phẩm từ thiết kế riêng của seller cho người khác.</li>' +
      '<li>Không dùng hình ảnh sản phẩm của seller để quảng cáo khi chưa được đồng ý.</li>' +
      '<li>Ảnh và thông tin seller gửi chỉ dùng để sản xuất đúng đơn hàng đó.</li>' +
      '<li>File chỉ được lưu lại để in lại cho chính seller. Cam kết giữ nguyên sau khi ngừng hợp tác.</li></ol>' +
      '<b>Seller đồng ý</b><ol class="cam" style="margin:8px 0 14px">' +
      '<li>Không cho người khác dùng chung tài khoản.</li>' +
      '<li>Giá in tính theo gram tại thời điểm đặt đơn, trừ thẳng vào ví nạp trước.</li>' +
      '<li>Đơn đã lên máy in thì không huỷ được.</li></ol>' +
      '<label style="display:flex;gap:8px;align-items:center;margin-bottom:14px"><input type="checkbox" id="cbDy"> Tôi đã đọc và đồng ý</label>' +
      '<button class="btn" id="bDy" disabled>Đồng ý và vào cổng</button> <button class="btn2" onclick="dangXuat()">Đăng xuất</button></div></div>';
    $('cbDy').onchange = function () { $('bDy').disabled = !this.checked; };
    $('bDy').onclick = async function () { try { await api('dong-y', { body: {} }); khoiDong(); } catch (e) { baoLoi(e); } };
  }

  /* ---------- Khung chính ---------- */
  async function khoiDong() {
    if (!lsGet(TOKEN_KEY)) return veDangNhap();
    try { st.me = await api('toi'); } catch (e) { return; }
    if (!st.me.dong_y) return veDieuKhoan();
    $('vao').innerHTML = '';
    $('hdr').classList.remove('hide'); $('khung').classList.remove('hide');
    $('whoTen').textContent = st.me.ten + ' · ' + st.me.ma;
    capNhatDu(st.me.so_du);
    napGio(); luuGio();
    go((location.hash || '#chung').slice(1), true);
    if (st.tab !== 'rieng') layMau('rieng').catch(function () {}); // số mẫu độc quyền trên menu
  }
  function capNhatDu(n) { st.me.so_du = n; $('whoDu').innerHTML = '<svg class="ic" viewBox="0 0 24 24" style="width:15px;height:15px"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/></svg>' + tien(n); }

  window.go = function (t, khongDoiHash) {
    if (!['chung', 'rieng', 'gio', 'don', 'vi', 'noigiao'].includes(t)) t = 'chung';
    st.tab = t;
    if (!khongDoiHash && location.hash !== '#' + t) history.replaceState(null, '', '#' + t);
    document.querySelectorAll('#ben a[data-t], #tabs a').forEach(function (a) { a.classList.toggle('on', a.dataset.t === t); });
    window.scrollTo(0, 0);
    ve();
  };
  window.addEventListener('hashchange', function () { if (st.me) go(location.hash.slice(1), true); });

  function veMsg() {
    if (!st.msg) return '';
    var h = st.msg.ok ? '<div class="msg ok">' + st.msg.ok + '</div>' : '<div class="msg err">' + esc(st.msg.err) + '</div>';
    st.msg = null; return h;
  }

  async function ve() {
    var t = st.tab;
    try {
      if (t === 'chung' || t === 'rieng') await veKho(t);
      else if (t === 'gio') await veGio();
      else if (t === 'don') await veDon();
      else if (t === 'vi') await veVi();
      else if (t === 'noigiao') await veNoiGiao();
    } catch (e) { baoLoi(e); }
  }

  /* ---------- Kho mẫu ---------- */
  async function layMau(kho) {
    // Link ảnh có hạn 10 phút: quá 8 phút thì lấy lại. Mốc thời gian RIÊNG từng kho, và gán sau khi
    // await xong (không thay cả st.mau) — hai kho tải song song không được giẫm lên nhau.
    if (!st.mau[kho] || Date.now() - (st.mauLuc[kho] || 0) > 8 * 60 * 1000) {
      var ds = (await api('mau?kho=' + kho)).mau;
      st.mau[kho] = ds; st.mauLuc[kho] = Date.now();
    }
    if (kho === 'rieng') { var n = st.mau.rieng.length; $('riengN').textContent = n; $('riengN').classList.toggle('hide', !n); }
    return st.mau[kho];
  }

  async function veKho(kho) {
    var rieng = kho === 'rieng';
    var tieuDe = rieng ? 'Mẫu độc quyền của bạn' : 'Kho chung';
    var mo = rieng ? 'Mẫu xưởng thiết kế riêng cho shop bạn.' : 'Mẫu xưởng làm sẵn, mọi seller dùng được. Ảnh mockup được dùng để đăng bán.';
    var thongBao = veMsg();
    main.innerHTML = thongBao + '<h2>' + tieuDe + '</h2><p class="sub">' + mo + '</p><p class="mu">Đang tải…</p>';
    var ds = await layMau(kho);
    var q = st.tim.trim().toLowerCase();
    var loc = ds.filter(function (m) { return !q || (m.ten + ' ' + (m.nhom || '')).toLowerCase().includes(q); });
    var html = thongBao + '<h2>' + tieuDe + '</h2><p class="sub">' + mo + ' ' + giaNhua() + '.</p>';
    if (rieng) html += '<div class="bang-rieng"><svg class="ic" viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg><div><b>Chỉ tài khoản của bạn thấy những mẫu này.</b><div class="mu">Seller khác không biết mẫu tồn tại; xưởng không in mẫu này cho ai khác. Phí thiết kế được hoàn vào ví khi mẫu đạt 20 đơn.</div></div></div>';
    if (ds.length > 6) html += '<div class="timbox">' + IC_TIM + '<input id="tim" placeholder="Tìm mẫu theo tên hoặc nhóm" value="' + esc(st.tim) + '"></div>';
    if (!ds.length) {
      html += rieng
        ? '<div class="box trong"><b>Bạn chưa có mẫu độc quyền</b>Gửi ý tưởng hoặc ảnh mẫu cho xưởng qua Zalo <a href="https://zalo.me/0856911698" rel="nofollow">0856 911 698</a>. Xưởng báo phí thiết kế, làm xong mẫu sẽ nằm ở đây. <a href="/bang-gia#thiet-ke" target="_blank">Xem phí thiết kế</a></div>'
        : '<div class="box trong"><b>Kho chung chưa có mẫu</b>Xưởng đang cập nhật.</div>';
    } else {
      html += '<div class="grid">' + loc.map(function (m) {
        var re = m.bien_the.length ? Math.min.apply(null, m.bien_the.map(function (b) { return b.gia; })) : null;
        return '<div class="card" data-i="' + ds.indexOf(m) + '" role="button" tabindex="0"><div class="img">' + (m.anh[0] ? '<img src="' + esc(m.anh[0]) + '" alt="" loading="lazy">' : 'Chưa có ảnh') +
          (m.rieng ? '<span class="tag t-rieng">' + IC_KHOA + ' Độc quyền</span>' : '') + '</div>' +
          '<div class="bd"><b>' + esc(m.ten) + '</b><div class="mu">' + esc(m.nhom || '') + '</div>' +
          '<div style="margin-top:6px">' + (re !== null ? '<span class="gia">từ ' + tien(re) + '</span>' : '<span class="mu">chưa có giá</span>') + '</div></div></div>';
      }).join('') + '</div>';
      if (!loc.length) html += '<p class="mu">Không có mẫu khớp "' + esc(st.tim) + '".</p>';
    }
    main.innerHTML = html;
    var o = $('tim'); if (o) o.oninput = function () { st.tim = this.value; clearTimeout(o._t); o._t = setTimeout(function () { veKho(kho).then(function () { var x = $('tim'); if (x) { x.focus(); x.setSelectionRange(x.value.length, x.value.length); } }); }, 250); };
    main.querySelectorAll('.card').forEach(function (c) {
      c.onclick = function () { moMau(ds[+c.dataset.i]); };
      c.onkeydown = function (ev) { if (ev.key === 'Enter') moMau(ds[+c.dataset.i]); };
    });
  }

  async function moMau(m) {
    // Mẫu khắc tên: lấy dữ liệu khung xem trước (không có trong danh sách vì nặng)
    // + ảnh hình chiếu cho ảnh kích thước (link có chữ ký, chỉ có ở chi tiết)
    if ((m.co_xem_truoc && !m.xem_truoc) || (m.co_kich_thuoc && m.hinh_chieu == null)) {
      try { var ct = (await api('mau/' + encodeURIComponent(m.ma))).mau; m.xem_truoc = ct.xem_truoc; m.hinh_chieu = ct.hinh_chieu; } catch (e) { baoLoi(e); return; }
    }
    var xt = m.xem_truoc, bt0 = m.bien_the[0] || {};
    var fonts = (xt && xt.fonts) || [];
    var sel = { kieu: bt0.kieu || null, ten: bt0.ten, cl: bt0.chat_lieu, mau: '', font: fonts[0] || '', chu: '', sl: 1 };
    function giong(b, k) { return (!k.kieu || b.kieu === sel.kieu) && (!k.ten || b.ten === sel.ten) && (!k.cl || b.chat_lieu === sel.cl); }
    function duyNhat(ds) { return ds.filter(function (v, i) { return v !== null && v !== undefined && ds.indexOf(v) === i; }); }
    function bt() { return m.bien_the.find(function (b) { return giong(b, { kieu: 1, ten: 1, cl: 1 }); }); }
    function chuan() { // chọn lại cỡ/nhựa còn có sau khi đổi kiểu hoặc cỡ
      if (!m.bien_the.some(function (b) { return giong(b, { kieu: 1, ten: 1 }); })) { var a = m.bien_the.find(function (b) { return giong(b, { kieu: 1 }); }) || bt0; sel.ten = a.ten; }
      if (!bt()) { var c = m.bien_the.find(function (b) { return giong(b, { kieu: 1, ten: 1 }); }) || bt0; sel.cl = c.chat_lieu; }
      var b = bt(); if (b && b.mau_sac.length && b.mau_sac.indexOf(sel.mau) < 0) sel.mau = b.mau_sac[0];
    }
    function khungNay() { return xt ? PodVeTen.khung(xt, sel.kieu) : null; }
    function toiDa() { var k = khungNay(); return (k && k.toi_da) || (xt && xt.toi_da) || m.ten_toi_da || 0; }
    function ktNay() { var b = bt(); return (b && b.kich_thuoc) || null; }
    function veXemTruoc() {
      var kt = ktNay();
      if ($('mKt')) PodVeTen.veKt($('mKt'), { kt: kt, hinh: m.hinh_chieu, xt: xt, kieu: sel.kieu, chu: sel.chu, mau: sel.mau, font: sel.font });
      var cv = $('mCv'), k = khungNay(); if (!cv || !k) return;
      PodVeTen.datCao(cv, k);
      PodVeTen.choFont(sel.font).then(function () { PodVeTen.ve(cv, k, sel.chu || 'Tên của bạn', sel.mau, sel.font, { ti: kt && kt.ti_le }); });
    }
    function nhom(tieuDe, khoa, ds, nhan) {
      if (ds.length < 2 && khoa !== 'mau' && khoa !== 'font') return '';
      if (!ds.length) return '';
      return '<div class="nhom-chon"><div class="tieu">' + tieuDe + '</div><div class="chips">' + ds.map(function (v) {
        return '<button type="button" class="chon' + (String(sel[khoa]) === String(v) ? ' on' : '') + '" data-k="' + khoa + '" data-v="' + esc(v) + '">' + nhan(v) + '</button>';
      }).join('') + '</div></div>';
    }
    function veM() {
      chuan();
      var b = bt(), td = toiDa();
      var kieuDs = duyNhat(m.bien_the.map(function (x) { return x.kieu; }));
      var coDs = duyNhat(m.bien_the.filter(function (x) { return !sel.kieu || x.kieu === sel.kieu; }).map(function (x) { return x.ten; }));
      var clDs = duyNhat(m.bien_the.filter(function (x) { return (!sel.kieu || x.kieu === sel.kieu) && x.ten === sel.ten; }).map(function (x) { return x.chat_lieu; }));
      var giaCl = function (cl) { var x = m.bien_the.find(function (y) { return (!sel.kieu || y.kieu === sel.kieu) && y.ten === sel.ten && y.chat_lieu === cl; }); return x ? ' <span class="mu">' + tien(x.gia) + '</span>' : ''; };
      var anhChinh = xt
        ? '<canvas class="main" id="mCv" width="900" height="1000" style="background:#F4EFE6;aspect-ratio:auto;height:auto"></canvas>' +
          '<button class="btn2" id="mTai" style="margin-top:8px;width:100%">Tải ảnh xem trước (PNG)</button>'
        : (m.anh[0] ? '<img class="main" id="mMain" src="' + esc(m.anh[0]) + '" alt="">' : '<div class="main" style="display:flex;align-items:center;justify-content:center;color:var(--mu)">Chưa có ảnh</div>');
      var h = '<button class="mx" onclick="dongModal()" aria-label="Đóng">×</button><div class="two"><div class="gal">' + anhChinh +
        (!xt && m.anh.length > 1 ? '<div class="thumbs">' + m.anh.map(function (u, i) { return '<img src="' + esc(u) + '" data-u="' + esc(u) + '" class="' + (i ? '' : 'on') + '">'; }).join('') + '</div>' : '') +
        (xt ? '<p class="mu" style="margin-top:8px">Ảnh vẽ từ file in thật; đổi kiểu, màu, font, tên là thấy ngay. Gửi ảnh này cho khách duyệt hoặc đăng bán.</p>'
            : (m.anh.length ? '<p class="mu" style="margin-top:8px">Bấm giữ hoặc chuột phải vào ảnh để lưu. ' + (m.rieng ? 'Chỉ bạn thấy ảnh này.' : 'Ảnh được dùng để đăng bán.') + '</p>' : '')) +
        (m.co_kich_thuoc ? '<div class="nhom-chon"><div class="tieu">Ảnh kích thước · ' + esc(sel.ten || '') + '</div>' +
          '<canvas id="mKt" width="900" height="560" style="width:100%;height:auto;display:block;border:1px solid var(--bd);border-radius:12px;background:#fff"></canvas>' +
          '<button class="btn2" id="mTaiKt" style="margin-top:8px;width:100%">Tải ảnh kích thước (PNG)</button></div>' : '') +
        '</div><div>' + (m.rieng ? '<span class="tag t-rieng">' + IC_KHOA + ' Mẫu độc quyền</span>' : '<span class="tag t-chung">Kho chung</span>') + '<h2 style="margin-top:8px">' + esc(m.ten) + '</h2>' +
        (m.mo_ta ? '<p class="sub" style="white-space:pre-line">' + esc(m.mo_ta) + '</p>' : '');
      if (!m.bien_the.length) { h += '<p class="msg err">Mẫu chưa có phân loại, chưa đặt được.</p></div></div>'; $('mbox').innerHTML = h; return; }
      h += nhom('Kiểu', 'kieu', kieuDs, function (v) { return esc((xt && xt.kieu && xt.kieu[v] && xt.kieu[v].ten) || v); }) +
        nhom('Kích thước', 'ten', coDs, esc) +
        nhom('Nhựa', 'cl', clDs, function (v) { return esc(v) + giaCl(v); }) +
        nhom('Màu nhựa', 'mau', b ? b.mau_sac : [], function (v) { return '<i class="cham" style="background:' + PodVeTen.nenChip(v) + '"></i>' + esc(v); }) +
        nhom('Font chữ', 'font', fonts, function (v) { return '<span style="font:' + PodVeTen.css(v, 18).replace(/"/g, "'") + '">' + esc(sel.chu || 'Emma') + '</span><small class="mu"> ' + esc(v) + '</small>'; });
      if (b) {
        h += '<label class="f" style="margin-top:12px">' + (td ? 'Tên in trên mẫu (tối đa ' + td + ' ký tự)' : 'Chữ cá nhân hoá') + (m.phi_ca_nhan ? ' (+' + tien(m.phi_ca_nhan) + '/dòng)' : '') +
          '<input id="mChu" maxlength="' + (td || 300) + '" value="' + esc(sel.chu) + '" placeholder="' + (td ? 'Vd: Emma' : 'Bỏ trống nếu không') + '"></label>' +
          '<div class="gia-chon"><div><div class="mu">Giá in 1 chiếc · ' + gram(b.gram) + '</div><b>' + tien(b.gia) + '</b></div>' +
          '<label class="f" style="width:110px">Số lượng<input id="mSl" type="number" min="1" max="500" value="' + sel.sl + '"></label></div>' +
          '<div class="msg err hide" id="mLoi" style="margin:10px 0 0"></div>' +
          '<button class="btn" id="mThem" style="margin-top:12px;width:100%">Thêm vào đơn đang soạn</button>';
      }
      h += '</div></div>';
      $('mbox').innerHTML = h;
      $('mbox').querySelectorAll('.chon').forEach(function (o) {
        o.onclick = function () { luuO(); sel[o.dataset.k] = o.dataset.v; veM(); };
      });
      $('mbox').querySelectorAll('.thumbs img').forEach(function (t) { t.onclick = function () { $('mMain').src = t.dataset.u; $('mbox').querySelectorAll('.thumbs img').forEach(function (x) { x.classList.toggle('on', x === t); }); }; });
      if ($('mChu')) $('mChu').oninput = function () {
        sel.chu = $('mChu').value.trim(); if ($('mLoi')) $('mLoi').classList.add('hide');
        $('mbox').querySelectorAll('.chon[data-k="font"] span').forEach(function (s) { s.textContent = sel.chu || 'Emma'; });
        veXemTruoc();
      };
      if ($('mTaiKt')) $('mTaiKt').onclick = function () {
        PodVeTen.tai($('mKt'), [m.ten, 'kich thuoc', (khungNay() || {}).ten, sel.ten].filter(Boolean).join('-').replace(/[\\/:*?"<>|]+/g, '') + '.png');
      };
      if ($('mTai')) $('mTai').onclick = function () {
        PodVeTen.tai($('mCv'), [m.ten, (khungNay() || {}).ten, sel.chu || 'mau', sel.mau].filter(Boolean).join('-').replace(/[\\/:*?"<>|]+/g, '') + '.png');
      };
      veXemTruoc();
      var nut = $('mThem');
      if (nut) nut.onclick = function () {
        luuO();
        if (td && !sel.chu) { $('mLoi').textContent = 'Nhập tên cần in trên mẫu.'; $('mLoi').classList.remove('hide'); $('mChu').focus(); return; }
        var k = khungNay(), tenKieu = sel.kieu && k && k.ten ? k.ten + ' · ' : '';
        st.gio.push({ bien_the_id: b.id, ten_mau: m.ten, ten_bt: tenKieu + b.ten, chat_lieu: b.chat_lieu, gia: b.gia, phi_ca_nhan: m.phi_ca_nhan,
          mau_sac: sel.mau || '', font: fonts.length ? sel.font : '', chu: sel.chu, so_luong: sel.sl });
        luuGio(); dongModal();
        st.msg = { ok: 'Đã thêm "' + esc(m.ten) + '"' + (sel.chu ? ' tên "' + esc(sel.chu) + '"' : '') + ' vào đơn đang soạn. <a href="#gio">Xem đơn</a>' }; ve();
      };
    }
    function luuO() { // giữ tên + số lượng khi vẽ lại hộp
      if ($('mChu')) sel.chu = $('mChu').value.trim();
      if ($('mSl')) sel.sl = Math.max(1, Math.min(500, parseInt($('mSl').value, 10) || 1));
    }
    veM();
    $('modal').classList.remove('hide');
  }
  window.dongModal = function () { $('modal').classList.add('hide'); $('mbox').innerHTML = ''; };
  document.addEventListener('keydown', function (ev) { if (ev.key === 'Escape' && !$('modal').classList.contains('hide')) dongModal(); });

  /* ---------- Đơn đang soạn ---------- */
  async function veGio() {
    if (!st.noiGiao) st.noiGiao = (await api('noi-giao')).noi_giao;
    var tong = st.gio.reduce(function (s, d) { return s + giaDong(d); }, 0);
    var h = veMsg() + '<h2>Đơn đang soạn</h2><p class="sub">Một đơn giao tới một nơi. Giá chính thức do máy chủ tính lại khi gửi.</p>';
    if (!st.gio.length) { main.innerHTML = h + '<div class="box trong"><b>Chưa có dòng nào</b>Chọn mẫu ở <a href="#chung">Kho chung</a> hoặc <a href="#rieng">Mẫu độc quyền</a>.</div>'; return; }
    h += '<div class="box"><table><tr><th>Mẫu</th><th>SL</th><th class="num">Tạm tính</th><th></th></tr>' + st.gio.map(function (d, i) {
      return '<tr><td><b>' + esc(d.ten_mau) + '</b><div class="mu">' + esc(d.ten_bt) + (d.chat_lieu ? ' · ' + esc(d.chat_lieu) : '') + (d.mau_sac ? ' · ' + esc(d.mau_sac) : '') + (d.chu ? ' · "' + esc(d.chu) + '"' : '') + (d.font ? ' · ' + esc(d.font) : '') + '</div></td>' +
        '<td><input type="number" min="1" max="500" value="' + d.so_luong + '" data-sl="' + i + '" style="width:72px;border:1.5px solid var(--bd);border-radius:9px;padding:6px 8px"></td>' +
        '<td class="num">' + tien(giaDong(d)) + '</td><td class="num"><button class="btn2 btn-del" data-xoa="' + i + '">Bỏ</button></td></tr>';
    }).join('') + '<tr><td colspan="2" class="num"><b>Tổng tạm tính</b></td><td class="num gia">' + tien(tong) + '</td><td></td></tr></table></div>';
    h += '<div class="box"><div class="row">' +
      '<label class="f">Giao tới' + (st.noiGiao.length ? '<select id="gNg">' + st.noiGiao.map(function (n) { return '<option value="' + n.id + '">' + esc(n.ten) + ' — ' + esc(n.dia_chi) + '</option>'; }).join('') + '</select>' : '<span class="msg err" style="margin:0">Chưa có nơi giao. <a href="#noigiao">Thêm nơi giao</a></span>') + '</label>' +
      '<label class="f">Mã đơn Etsy / Amazon (để đối chiếu)<input id="gSan" maxlength="60"></label></div>' +
      '<div class="row" style="margin-top:12px"><label class="f">Ghi chú cho xưởng<textarea id="gGc" rows="2" maxlength="1000"></textarea></label>' +
      '<label class="f">Nhãn line bay (nếu line yêu cầu dán từng kiện)<input id="gNhan" type="file" accept="image/*,application/pdf"></label></div>' +
      '<p class="mu" style="margin-top:12px">Ví hiện có ' + tien(st.me.so_du) + '. ' + (st.me.so_du >= tong ? 'Đủ tiền: đơn vào hàng đợi in ngay khi gửi.' : 'Chưa đủ: đơn sẽ chờ nạp tiền, tự vào sản xuất khi nạp đủ.') + '</p>' +
      '<button class="btn" id="gGui" style="margin-top:12px"' + (st.noiGiao.length ? '' : ' disabled') + '>Gửi đơn · ' + tien(tong) + '</button></div>';
    main.innerHTML = h;
    main.querySelectorAll('[data-sl]').forEach(function (inp) { inp.onchange = function () { st.gio[+inp.dataset.sl].so_luong = Math.max(1, Math.min(500, parseInt(inp.value, 10) || 1)); luuGio(); veGio(); }; });
    main.querySelectorAll('[data-xoa]').forEach(function (b) { b.onclick = function () { st.gio.splice(+b.dataset.xoa, 1); luuGio(); veGio(); }; });
    var gui = $('gGui');
    if (gui) gui.onclick = async function () {
      gui.disabled = true; gui.textContent = 'Đang gửi…';
      var fd = new FormData();
      fd.append('dong', JSON.stringify(st.gio.map(function (d) { return { bien_the_id: d.bien_the_id, so_luong: d.so_luong, mau_sac: d.mau_sac, chu: d.chu, font: d.font || '' }; })));
      fd.append('noi_giao_id', $('gNg').value);
      if ($('gSan').value.trim()) fd.append('ma_don_san', $('gSan').value.trim());
      if ($('gGc').value.trim()) fd.append('ghi_chu', $('gGc').value.trim());
      if ($('gNhan').files[0]) fd.append('nhan', $('gNhan').files[0]);
      try {
        var j = await api('don', { body: fd });
        st.gio = []; luuGio(); capNhatDu(j.so_du);
        st.msg = { ok: j.don.trang_thai === 'cho_in'
          ? 'Đơn <b>' + esc(j.don.ma) + '</b> đã vào hàng đợi in, đã trừ ' + tien(j.don.tong_tien) + '.'
          : 'Đơn <b>' + esc(j.don.ma) + '</b> đang chờ nạp tiền (' + tien(j.don.tong_tien) + '). <a href="#vi">Nạp tiền</a> — đơn tự vào sản xuất khi đủ.' };
        go('don');
      } catch (e) { gui.disabled = false; gui.textContent = 'Gửi đơn'; baoLoi(e); }
    };
  }

  /* ---------- Đơn của tôi ---------- */
  async function veDon() {
    var ds = (await api('don')).don;
    var h = veMsg() + '<h2>Đơn của tôi</h2><p class="sub">Đơn Chờ nạp tiền và Chờ in còn huỷ được; đơn đã lên máy in thì không.</p>';
    if (!ds.length) { main.innerHTML = h + '<div class="box trong"><b>Chưa có đơn nào</b>Chọn mẫu rồi gửi đơn ở mục Đơn đang soạn.</div>'; return; }
    h += ds.map(function (d) {
      return '<div class="box"><div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:center"><div><b>' + esc(d.ma) + '</b> <span class="mu">· ' + esc(d.luc) + (d.ma_don_san ? ' · ' + esc(d.ma_don_san) : '') + '</span></div>' +
        '<span class="st st-' + d.trang_thai + '">' + esc(d.ten_tt) + '</span></div>' +
        '<table style="margin-top:8px">' + d.dong.map(function (x) {
          return '<tr><td>' + esc(x.ten_mau) + ' <span class="mu">· ' + esc(x.ten_bien_the) + (x.chat_lieu ? ' · ' + esc(x.chat_lieu) : '') + (x.mau_sac ? ' · ' + esc(x.mau_sac) : '') + (x.chu ? ' · "' + esc(x.chu) + '"' : '') + (x.font ? ' · ' + esc(x.font) : '') + '</span></td><td class="num">× ' + x.so_luong + '</td><td class="num">' + tien(x.thanh_tien) + '</td></tr>';
        }).join('') + '<tr><td colspan="2" class="num"><b>Tổng</b></td><td class="num gia">' + tien(d.tong_tien) + '</td></tr></table>' +
        '<div class="mu" style="margin-top:6px">Giao tới: ' + esc(d.noi_giao ? d.noi_giao.ten : '—') + (d.ma_van_don ? ' · Mã vận đơn: <b>' + esc(d.ma_van_don) + '</b>' : '') + '</div>' +
        (d.trang_thai === 'cho_nap' || d.trang_thai === 'cho_in' ? '<button class="btn2 btn-del" style="margin-top:10px" data-huy="' + esc(d.ma) + '">Huỷ đơn</button>' : '') + '</div>';
    }).join('');
    main.innerHTML = h;
    main.querySelectorAll('[data-huy]').forEach(function (b) {
      b.onclick = async function () {
        if (!b.dataset.chac) { b.dataset.chac = '1'; b.textContent = 'Bấm lần nữa để huỷ'; return; }
        try { var j = await api('don/' + encodeURIComponent(b.dataset.huy) + '/huy', { body: {} }); capNhatDu(j.so_du); st.msg = { ok: 'Đã huỷ đơn ' + esc(b.dataset.huy) + '.' }; veDon(); } catch (e) { baoLoi(e); }
      };
    });
  }

  /* ---------- Ví ---------- */
  async function veVi() {
    var v = await api('vi'); capNhatDu(v.so_du);
    var n = v.nap;
    var h = veMsg() + '<h2>Ví</h2><p class="sub">Nạp trước, mỗi đơn trừ thẳng theo gram. ' + giaNhua() + '.</p>' +
      '<div class="du"><div style="opacity:.85;font-weight:600">Số dư</div><div class="so">' + tien(v.so_du) + '</div>' +
      (v.can_them > 0 ? '<div style="margin-top:8px;background:rgba(255,255,255,.18);border-radius:10px;padding:8px 12px;font-size:14px">Cần nạp thêm <b>' + tien(v.can_them) + '</b> để chạy các đơn đang chờ nạp tiền.</div>' : '') + '</div>' +
      '<div class="box"><b>Nạp tiền bằng chuyển khoản</b><div style="display:flex;gap:18px;flex-wrap:wrap;margin-top:12px;align-items:flex-start">' +
      '<img class="qr" src="' + esc(n.qr) + '" alt="Mã QR chuyển khoản">' +
      '<div style="flex:1;min-width:220px">' + [['Ngân hàng', n.ngan_hang], ['Số tài khoản', n.stk], ['Chủ tài khoản', n.chu_tk], ['Nội dung (bắt buộc)', n.noi_dung]].map(function (r) {
        return '<div class="copy"><span><span class="mu">' + r[0] + '</span><br><b>' + esc(r[1]) + '</b></span><button class="btn2" data-copy="' + esc(r[1]) + '">Chép</button></div>';
      }).join('') +
      '<p class="mu" style="margin-top:10px">Xưởng xác nhận tiền về trong giờ làm việc (8h–20h, thứ 2 đến thứ 7). Đơn chờ nạp tiền tự vào hàng đợi in ngay khi xác nhận.</p></div></div></div>' +
      '<div class="box"><b>Sổ ví</b>' + (v.lich_su.length ? '<table style="margin-top:8px"><tr><th>Lúc</th><th>Loại</th><th>Ghi chú</th><th class="num">Số tiền</th></tr>' + v.lich_su.map(function (x) {
        return '<tr><td class="mu">' + esc(x.luc) + '</td><td>' + esc(x.loai) + '</td><td>' + esc(x.ghi_chu || '') + '</td><td class="num" style="font-weight:700;color:' + (x.so_tien < 0 ? '#B91C1C' : 'var(--gd)') + '">' + (x.so_tien > 0 ? '+' : '') + tien(x.so_tien) + '</td></tr>';
      }).join('') + '</table>' : '<p class="mu" style="margin-top:6px">Chưa có giao dịch.</p>') + '</div>';
    main.innerHTML = h;
    main.querySelectorAll('[data-copy]').forEach(function (b) {
      b.onclick = function () {
        var s = b.dataset.copy, ok = function () { b.textContent = 'Đã chép'; setTimeout(function () { b.textContent = 'Chép'; }, 1500); };
        if (navigator.clipboard) navigator.clipboard.writeText(s).then(ok, function () {}); else { var t = document.createElement('textarea'); t.value = s; document.body.appendChild(t); t.select(); try { document.execCommand('copy'); ok(); } catch (e) {} t.remove(); }
      };
    });
  }

  /* ---------- Nơi giao ---------- */
  async function veNoiGiao() {
    st.noiGiao = (await api('noi-giao')).noi_giao;
    var h = veMsg() + '<h2>Nơi giao</h2><p class="sub">Kho gom hoặc line bay xưởng giao hàng tới. Chọn khi gửi đơn.</p>';
    h += st.noiGiao.length ? st.noiGiao.map(function (n) {
      return '<div class="box" style="display:flex;justify-content:space-between;gap:10px;align-items:flex-start"><div><b>' + esc(n.ten) + '</b><div>' + esc(n.dia_chi) + '</div><div class="mu">' + esc(n.sdt || '') + ' ' + esc(n.ghi_chu || '') + '</div></div><button class="btn2 btn-del" data-xng="' + n.id + '">Xoá</button></div>';
    }).join('') : '<div class="box trong"><b>Chưa có nơi giao nào</b>Thêm kho gom hoặc line bay bạn hay gửi ở dưới.</div>';
    h += '<div class="box"><b>Thêm nơi giao</b><form id="fNg" style="margin-top:12px"><div class="row">' +
      '<label class="f">Tên (vd: Line bay ABC Tân Bình)<input name="ten" required maxlength="120"></label>' +
      '<label class="f">Số điện thoại<input name="sdt" maxlength="20"></label></div>' +
      '<label class="f" style="margin-top:12px">Địa chỉ<input name="dia_chi" required maxlength="300"></label>' +
      '<label class="f" style="margin-top:12px">Ghi chú<input name="ghi_chu" maxlength="300"></label>' +
      '<button class="btn" style="margin-top:14px">Lưu nơi giao</button></form></div>';
    main.innerHTML = h;
    $('fNg').onsubmit = async function (ev) {
      ev.preventDefault(); var fd = new FormData(ev.target);
      try { await api('noi-giao', { body: Object.fromEntries(fd.entries()) }); st.msg = { ok: 'Đã lưu nơi giao.' }; veNoiGiao(); } catch (e) { baoLoi(e); }
    };
    main.querySelectorAll('[data-xng]').forEach(function (b) {
      b.onclick = async function () {
        if (!b.dataset.chac) { b.dataset.chac = '1'; b.textContent = 'Bấm lần nữa để xoá'; return; }
        try { await api('noi-giao/' + b.dataset.xng, { method: 'DELETE' }); veNoiGiao(); } catch (e) { baoLoi(e); }
      };
    });
  }

  window.dangXuat = async function () {
    try { await api('dang-xuat', { body: {} }); } catch (e) {}
    lsSet(TOKEN_KEY, null); st.me = null; st.mau = { chung: null, rieng: null }; st.mauLuc = {}; st.noiGiao = null; veDangNhap();
  };

  khoiDong();
})();
</script>
</body>
</html>
