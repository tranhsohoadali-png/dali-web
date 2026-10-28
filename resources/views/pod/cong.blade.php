<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Cổng seller POD | DALI</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bd:#D5ECB4;--tx:#1C3A0A;--mu:#6B7280;--bg:#F7FBF2;--r:12px}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro',system-ui,sans-serif}
body{background:var(--bg);color:var(--tx);font-size:14px;line-height:1.45}
button,input,select,textarea{font:inherit;color:inherit}
a{color:var(--gd)}
.hide{display:none!important}
header{position:sticky;top:0;z-index:20;background:#fff;border-bottom:1px solid var(--bd)}
.hrow{max-width:1100px;margin:0 auto;padding:10px 16px;display:flex;align-items:center;gap:12px;justify-content:space-between}
.logo{font-weight:800;font-size:16px;color:var(--gd)}.logo small{font-weight:600;color:var(--mu);font-size:12px;margin-left:6px}
.who{display:flex;align-items:center;gap:10px;font-size:13px}
.chip{background:var(--gl);color:var(--gd);border-radius:20px;padding:4px 10px;font-weight:700;white-space:nowrap;cursor:pointer;border:none}
.tabs{max-width:1100px;margin:0 auto;padding:0 12px;display:flex;gap:4px;overflow-x:auto}
.tabs a{padding:10px 12px;text-decoration:none;color:var(--mu);font-weight:600;font-size:13px;border-bottom:2px solid transparent;white-space:nowrap}
.tabs a.on{color:var(--gd);border-bottom-color:var(--g)}
main{max-width:1100px;margin:0 auto;padding:18px 16px 60px}
h2{font-size:18px;margin-bottom:4px}.sub{color:var(--mu);font-size:13px;margin-bottom:14px}
.box{background:#fff;border:1px solid var(--bd);border-radius:var(--r);padding:16px;margin-bottom:14px}
.btn{background:var(--g);color:#fff;border:none;border-radius:10px;padding:10px 18px;font-weight:700;cursor:pointer}
.btn:disabled{opacity:.5;cursor:default}
.btn2{background:#fff;color:var(--gd);border:1px solid var(--bd);border-radius:10px;padding:8px 14px;font-weight:600;cursor:pointer}
.btn-del{color:#B91C1C;border-color:#FECACA}
label.f{display:flex;flex-direction:column;gap:5px;font-size:12px;font-weight:600;color:var(--mu)}
label.f input,label.f select,label.f textarea{border:1px solid var(--bd);border-radius:9px;padding:9px 11px;font-size:14px;background:#fff;font-weight:400;color:var(--tx)}
.row{display:grid;gap:12px;grid-template-columns:repeat(auto-fit,minmax(200px,1fr))}
.msg{border-radius:10px;padding:10px 14px;margin-bottom:14px;font-size:13px}
.msg.ok{background:var(--gll);color:var(--gd);border:1px solid var(--bd)}
.msg.err{background:#FEF2F2;color:#B91C1C;border:1px solid #FECACA}
.grid{display:grid;gap:14px;grid-template-columns:repeat(auto-fill,minmax(200px,1fr))}
.card{background:#fff;border:1px solid var(--bd);border-radius:var(--r);overflow:hidden;cursor:pointer;display:flex;flex-direction:column}
.card .img{aspect-ratio:1;background:var(--gll);display:flex;align-items:center;justify-content:center;color:var(--mu);font-size:12px}
.card .img img{width:100%;height:100%;object-fit:cover}
.card .bd{padding:10px 12px}
.card b{display:block;font-size:14px}
.tag{display:inline-block;font-size:11px;font-weight:700;border-radius:20px;padding:2px 8px}
.t-rieng{background:#FCE7F3;color:#BE185D}.t-chung{background:#E0F2FE;color:#0369A1}
.st{font-size:11px;font-weight:700;border-radius:20px;padding:3px 9px;white-space:nowrap}
.st-cho_nap{background:#FEF3C7;color:#B45309}.st-cho_in{background:#E0F2FE;color:#0369A1}.st-dang_in{background:#EDE9FE;color:#6D28D9}.st-da_giao{background:var(--gl);color:var(--gd)}.st-da_huy{background:#F3F4F6;color:var(--mu)}
.mu{color:var(--mu);font-size:12px}
.gia{font-weight:800;color:var(--gd)}
table{width:100%;border-collapse:collapse}
td,th{padding:8px 6px;border-bottom:1px solid var(--gll);text-align:left;font-size:13px;vertical-align:top}
th{font-size:11px;color:var(--mu);text-transform:uppercase;letter-spacing:.5px}
.num{text-align:right;white-space:nowrap}
.modal{position:fixed;inset:0;background:rgba(20,40,10,.45);z-index:50;display:flex;align-items:flex-start;justify-content:center;overflow-y:auto;padding:24px 12px}
.mbox{background:#fff;border-radius:16px;max-width:760px;width:100%;padding:18px;position:relative}
.mx{position:absolute;top:10px;right:12px;border:none;background:none;font-size:22px;cursor:pointer;color:var(--mu)}
.gal{display:grid;grid-template-columns:1fr;gap:8px}
.gal .main{width:100%;aspect-ratio:1;object-fit:contain;background:var(--gll);border-radius:10px}
.thumbs{display:flex;gap:6px;flex-wrap:wrap}.thumbs img{width:56px;height:56px;object-fit:cover;border-radius:8px;border:2px solid transparent;cursor:pointer}.thumbs img.on{border-color:var(--g)}
.two{display:grid;gap:18px;grid-template-columns:1fr}
@media(min-width:720px){.two{grid-template-columns:300px 1fr}}
.opt{display:flex;justify-content:space-between;gap:10px;border:1px solid var(--bd);border-radius:10px;padding:9px 12px;margin-bottom:6px;cursor:pointer}
.opt.on{border-color:var(--g);background:var(--gll)}
.qr{width:220px;max-width:100%;border:1px solid var(--bd);border-radius:12px;background:#fff}
.copy{display:flex;align-items:center;gap:8px;justify-content:space-between;border-bottom:1px solid var(--gll);padding:8px 0}
.center{max-width:420px;margin:40px auto}
ol.cam{padding-left:20px;display:flex;flex-direction:column;gap:6px;font-size:13px}
</style>
</head>
<body>
<header id="hdr" class="hide">
  <div class="hrow">
    <div class="logo">DALI<small>Cổng seller POD</small></div>
    <div class="who"><span id="whoTen"></span><button class="chip" id="whoDu" onclick="go('vi')"></button><button class="btn2" onclick="dangXuat()">Đăng xuất</button></div>
  </div>
  <nav class="tabs" id="tabs">
    <a href="#chung" data-t="chung">Kho chung</a>
    <a href="#rieng" data-t="rieng">Kho riêng của tôi</a>
    <a href="#gio" data-t="gio">Đơn đang soạn <span id="gioN"></span></a>
    <a href="#don" data-t="don">Đơn của tôi</a>
    <a href="#vi" data-t="vi">Ví</a>
    <a href="#noigiao" data-t="noigiao">Nơi giao</a>
  </nav>
</header>
<main id="main"></main>
<div class="modal hide" id="modal" onclick="if(event.target===this)dongModal()"><div class="mbox" id="mbox"></div></div>

<script>
(function () {
  'use strict';
  var TOKEN_KEY = 'pod_token';
  var st = { me: null, mau: { chung: null, rieng: null }, mauLuc: 0, noiGiao: null, gio: [], tab: 'chung', msg: null, tim: '' };

  function lsGet(k) { try { return localStorage.getItem(k); } catch (e) { return null; } }
  function lsSet(k, v) { try { if (v === null) localStorage.removeItem(k); else localStorage.setItem(k, v); } catch (e) {} }
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function tien(n) { return (Number(n) || 0).toLocaleString('vi-VN') + 'đ'; }
  function gram(n) { return String(Number(n)).replace('.', ',') + ' g'; }
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

  /* ---------- Giỏ (đơn đang soạn) ---------- */
  function gioKey() { return 'pod_gio_' + (st.me ? st.me.ma : ''); }
  function napGio() { try { st.gio = JSON.parse(lsGet(gioKey()) || '[]') || []; } catch (e) { st.gio = []; } }
  function luuGio() { lsSet(gioKey(), JSON.stringify(st.gio)); var n = st.gio.length; $('gioN').textContent = n ? '(' + n + ')' : ''; }
  function giaDong(d) { return d.gia * d.so_luong + (d.chu ? d.phi_ca_nhan : 0); }

  /* ---------- Màn đăng nhập + điều khoản ---------- */
  function veDangNhap(loi) {
    $('hdr').classList.add('hide');
    main.innerHTML = '<div class="center box"><h2>Cổng seller POD</h2><p class="sub">Đăng nhập bằng tài khoản xưởng DALI cấp.</p>' +
      (loi ? '<div class="msg err">' + esc(loi) + '</div>' : '') +
      '<form id="fDn"><label class="f">Số điện thoại<input name="sdt" required autocomplete="username"></label><br>' +
      '<label class="f">Mật khẩu<input name="matkhau" type="password" required autocomplete="current-password"></label><br>' +
      '<button class="btn" style="width:100%">Đăng nhập</button></form>' +
      '<p class="mu" style="margin-top:12px">Chưa có tài khoản? Liên hệ xưởng qua Zalo 0856 911 698.</p></div>';
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
    $('hdr').classList.add('hide');
    main.innerHTML = '<div class="center box" style="max-width:620px"><h2>Điều khoản bảo mật thiết kế</h2><p class="sub">Đọc và đồng ý một lần trước khi dùng cổng.</p>' +
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
      '<label style="display:flex;gap:8px;align-items:center;margin-bottom:12px"><input type="checkbox" id="cbDy"> Tôi đã đọc và đồng ý</label>' +
      '<button class="btn" id="bDy" disabled>Đồng ý và vào cổng</button> <button class="btn2" onclick="dangXuat()">Đăng xuất</button></div>';
    $('cbDy').onchange = function () { $('bDy').disabled = !this.checked; };
    $('bDy').onclick = async function () { try { await api('dong-y', { body: {} }); khoiDong(); } catch (e) { baoLoi(e); } };
  }

  /* ---------- Khung chính ---------- */
  async function khoiDong() {
    if (!lsGet(TOKEN_KEY)) return veDangNhap();
    try { st.me = await api('toi'); } catch (e) { return; }
    if (!st.me.dong_y) return veDieuKhoan();
    $('hdr').classList.remove('hide');
    $('whoTen').textContent = st.me.ten + ' · ' + st.me.ma;
    capNhatDu(st.me.so_du);
    napGio(); luuGio();
    go((location.hash || '#chung').slice(1), true);
  }
  function capNhatDu(n) { st.me.so_du = n; $('whoDu').textContent = 'Ví ' + tien(n); }

  window.go = function (t, khongDoiHash) {
    if (!['chung', 'rieng', 'gio', 'don', 'vi', 'noigiao'].includes(t)) t = 'chung';
    st.tab = t;
    if (!khongDoiHash && location.hash !== '#' + t) { history.replaceState(null, '', '#' + t); }
    document.querySelectorAll('#tabs a').forEach(function (a) { a.classList.toggle('on', a.dataset.t === t); });
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
    // Link ảnh có hạn 10 phút: quá 8 phút thì lấy lại danh sách
    if (!st.mau[kho] || Date.now() - st.mauLuc > 8 * 60 * 1000) {
      if (Date.now() - st.mauLuc > 8 * 60 * 1000) st.mau = { chung: null, rieng: null };
      st.mau[kho] = (await api('mau?kho=' + kho)).mau; st.mauLuc = Date.now();
    }
    return st.mau[kho];
  }

  async function veKho(kho) {
    var tieuDe = kho === 'chung' ? 'Kho chung' : 'Kho riêng của tôi';
    var mo = kho === 'chung' ? 'Mẫu xưởng làm sẵn, mọi seller đều dùng được. Ảnh mockup dùng để đăng bán.' : 'Mẫu độc quyền làm riêng cho bạn. Seller khác không thấy những mẫu này.';
    var thongBao = veMsg();
    main.innerHTML = thongBao + '<h2>' + tieuDe + '</h2><p class="sub">' + mo + '</p><p class="mu">Đang tải…</p>';
    var ds = await layMau(kho);
    var q = st.tim.trim().toLowerCase();
    var loc = ds.filter(function (m) { return !q || (m.ten + ' ' + (m.nhom || '')).toLowerCase().includes(q); });
    var html = thongBao + '<h2>' + tieuDe + '</h2><p class="sub">' + mo + ' Giá in ' + tien(st.me.gia_gram) + '/gram.</p>' +
      '<div class="box" style="padding:10px"><input id="tim" placeholder="Tìm mẫu…" value="' + esc(st.tim) + '" style="width:100%;border:1px solid var(--bd);border-radius:9px;padding:9px 11px"></div>';
    if (!ds.length) html += '<div class="box mu">' + (kho === 'rieng' ? 'Bạn chưa có mẫu riêng. Muốn làm mẫu độc quyền, nhắn xưởng qua Zalo 0856 911 698.' : 'Kho chung chưa có mẫu.') + '</div>';
    else {
      html += '<div class="grid">' + loc.map(function (m, i) {
        var re = m.bien_the.length ? Math.min.apply(null, m.bien_the.map(function (b) { return b.gia; })) : null;
        return '<div class="card" data-i="' + ds.indexOf(m) + '"><div class="img">' + (m.anh[0] ? '<img src="' + esc(m.anh[0]) + '" alt="" loading="lazy">' : 'Chưa có ảnh') + '</div>' +
          '<div class="bd"><b>' + esc(m.ten) + '</b><div class="mu">' + esc(m.nhom || '') + '</div>' +
          '<div style="display:flex;justify-content:space-between;align-items:center;margin-top:6px"><span class="tag ' + (m.rieng ? 't-rieng">Riêng' : 't-chung">Chung') + '</span>' +
          (re !== null ? '<span class="gia">từ ' + tien(re) + '</span>' : '<span class="mu">chưa có giá</span>') + '</div></div></div>';
      }).join('') + '</div>';
      if (!loc.length) html += '<p class="mu">Không có mẫu khớp "' + esc(st.tim) + '".</p>';
    }
    main.innerHTML = html;
    var o = $('tim'); if (o) o.oninput = function () { st.tim = this.value; clearTimeout(o._t); o._t = setTimeout(function () { veKho(kho); setTimeout(function () { var x = $('tim'); if (x) { x.focus(); x.setSelectionRange(x.value.length, x.value.length); } }, 0); }, 250); };
    main.querySelectorAll('.card').forEach(function (c) { c.onclick = function () { moMau(ds[+c.dataset.i]); }; });
  }

  function moMau(m) {
    var chon = m.bien_the[0] ? m.bien_the[0].id : null;
    function bt() { return m.bien_the.find(function (b) { return b.id === chon; }); }
    function veM() {
      var b = bt();
      var h = '<button class="mx" onclick="dongModal()">×</button><div class="two"><div class="gal">' +
        (m.anh[0] ? '<img class="main" id="mMain" src="' + esc(m.anh[0]) + '" alt="">' : '<div class="main" style="display:flex;align-items:center;justify-content:center" >Chưa có ảnh</div>') +
        (m.anh.length > 1 ? '<div class="thumbs">' + m.anh.map(function (u, i) { return '<img src="' + esc(u) + '" data-u="' + esc(u) + '" class="' + (i ? '' : 'on') + '">'; }).join('') + '</div>' : '') +
        (m.anh.length ? '<p class="mu">Ảnh mockup: bấm giữ hoặc chuột phải để lưu. ' + (m.rieng ? 'Chỉ bạn thấy ảnh này.' : 'Được dùng để đăng bán.') + '</p>' : '') +
        '</div><div><span class="tag ' + (m.rieng ? 't-rieng">Mẫu riêng' : 't-chung">Kho chung') + '</span><h2 style="margin-top:6px">' + esc(m.ten) + '</h2>' +
        (m.mo_ta ? '<p class="sub" style="white-space:pre-line">' + esc(m.mo_ta) + '</p>' : '') +
        (m.bien_the.length ? '<div style="margin:10px 0 4px;font-weight:700">Phân loại</div>' + m.bien_the.map(function (x) {
          return '<div class="opt' + (x.id === chon ? ' on' : '') + '" data-id="' + x.id + '"><span>' + esc(x.ten) + ' <span class="mu">· ' + gram(x.gram) + '</span></span><span class="gia">' + tien(x.gia) + '</span></div>';
        }).join('') : '<p class="msg err">Mẫu chưa có phân loại, chưa đặt được.</p>');
      if (b) {
        h += (b.mau_sac.length ? '<label class="f" style="margin-top:8px">Màu<select id="mMau">' + b.mau_sac.map(function (c) { return '<option>' + esc(c) + '</option>'; }).join('') + '</select></label>' : '') +
          '<div class="row" style="margin-top:8px"><label class="f">Số lượng<input id="mSl" type="number" min="1" max="500" value="1"></label>' +
          '<label class="f">Chữ cá nhân hoá' + (m.phi_ca_nhan ? ' (+' + tien(m.phi_ca_nhan) + '/dòng)' : '') + '<input id="mChu" maxlength="300" placeholder="Bỏ trống nếu không"></label></div>' +
          '<button class="btn" id="mThem" style="margin-top:12px;width:100%">Thêm vào đơn đang soạn</button>';
      }
      h += '</div></div>';
      $('mbox').innerHTML = h;
      $('mbox').querySelectorAll('.opt').forEach(function (o) { o.onclick = function () { chon = +o.dataset.id; veM(); }; });
      $('mbox').querySelectorAll('.thumbs img').forEach(function (t) { t.onclick = function () { $('mMain').src = t.dataset.u; $('mbox').querySelectorAll('.thumbs img').forEach(function (x) { x.classList.toggle('on', x === t); }); }; });
      var nut = $('mThem');
      if (nut) nut.onclick = function () {
        var sl = Math.max(1, Math.min(500, parseInt($('mSl').value, 10) || 1));
        var chu = ($('mChu').value || '').trim();
        st.gio.push({ bien_the_id: b.id, ten_mau: m.ten, ten_bt: b.ten, gia: b.gia, phi_ca_nhan: m.phi_ca_nhan, mau_sac: $('mMau') ? $('mMau').value : '', chu: chu, so_luong: sl });
        luuGio(); dongModal();
        st.msg = { ok: 'Đã thêm "' + esc(m.ten) + '" vào đơn đang soạn. <a href="#gio">Xem đơn</a>' }; ve();
      };
    }
    veM();
    $('modal').classList.remove('hide');
  }
  window.dongModal = function () { $('modal').classList.add('hide'); $('mbox').innerHTML = ''; };

  /* ---------- Đơn đang soạn ---------- */
  async function veGio() {
    if (!st.noiGiao) st.noiGiao = (await api('noi-giao')).noi_giao;
    var tong = st.gio.reduce(function (s, d) { return s + giaDong(d); }, 0);
    var h = veMsg() + '<h2>Đơn đang soạn</h2><p class="sub">Một đơn giao tới một nơi. Giá chính thức do máy chủ tính lại khi gửi.</p>';
    if (!st.gio.length) { main.innerHTML = h + '<div class="box mu">Chưa có dòng nào. Chọn mẫu ở <a href="#chung">Kho chung</a> hoặc <a href="#rieng">Kho riêng</a>.</div>'; return; }
    h += '<div class="box"><table><tr><th>Mẫu</th><th>SL</th><th class="num">Tạm tính</th><th></th></tr>' + st.gio.map(function (d, i) {
      return '<tr><td><b>' + esc(d.ten_mau) + '</b><div class="mu">' + esc(d.ten_bt) + (d.mau_sac ? ' · ' + esc(d.mau_sac) : '') + (d.chu ? ' · "' + esc(d.chu) + '"' : '') + '</div></td>' +
        '<td><input type="number" min="1" max="500" value="' + d.so_luong + '" data-sl="' + i + '" style="width:70px;border:1px solid var(--bd);border-radius:8px;padding:6px"></td>' +
        '<td class="num">' + tien(giaDong(d)) + '</td><td><button class="btn2 btn-del" data-xoa="' + i + '">Bỏ</button></td></tr>';
    }).join('') + '<tr><td colspan="2" class="num"><b>Tổng tạm tính</b></td><td class="num gia">' + tien(tong) + '</td><td></td></tr></table></div>';
    h += '<div class="box"><div class="row">' +
      '<label class="f">Giao tới' + (st.noiGiao.length ? '<select id="gNg">' + st.noiGiao.map(function (n) { return '<option value="' + n.id + '">' + esc(n.ten) + ' — ' + esc(n.dia_chi) + '</option>'; }).join('') + '</select>' : '<span class="msg err" style="margin:0">Chưa có nơi giao. <a href="#noigiao">Thêm nơi giao</a></span>') + '</label>' +
      '<label class="f">Mã đơn Etsy/Amazon (để đối chiếu)<input id="gSan" maxlength="60"></label></div>' +
      '<div class="row" style="margin-top:10px"><label class="f">Ghi chú cho xưởng<textarea id="gGc" rows="2" maxlength="1000"></textarea></label>' +
      '<label class="f">Nhãn line bay (nếu line yêu cầu dán từng kiện)<input id="gNhan" type="file" accept="image/*,application/pdf"></label></div>' +
      '<p class="mu" style="margin-top:10px">Ví hiện có ' + tien(st.me.so_du) + '. ' + (st.me.so_du >= tong ? 'Đủ tiền: đơn vào hàng đợi in ngay khi gửi.' : 'Chưa đủ: đơn sẽ chờ nạp tiền, tự vào sản xuất khi nạp đủ.') + '</p>' +
      '<button class="btn" id="gGui" style="margin-top:10px"' + (st.noiGiao.length ? '' : ' disabled') + '>Gửi đơn</button></div>';
    main.innerHTML = h;
    main.querySelectorAll('[data-sl]').forEach(function (inp) { inp.onchange = function () { st.gio[+inp.dataset.sl].so_luong = Math.max(1, Math.min(500, parseInt(inp.value, 10) || 1)); luuGio(); veGio(); }; });
    main.querySelectorAll('[data-xoa]').forEach(function (b) { b.onclick = function () { st.gio.splice(+b.dataset.xoa, 1); luuGio(); veGio(); }; });
    var gui = $('gGui');
    if (gui) gui.onclick = async function () {
      gui.disabled = true; gui.textContent = 'Đang gửi…';
      var fd = new FormData();
      fd.append('dong', JSON.stringify(st.gio.map(function (d) { return { bien_the_id: d.bien_the_id, so_luong: d.so_luong, mau_sac: d.mau_sac, chu: d.chu }; })));
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
    if (!ds.length) { main.innerHTML = h + '<div class="box mu">Chưa có đơn nào.</div>'; return; }
    h += ds.map(function (d) {
      return '<div class="box"><div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap"><div><b>' + esc(d.ma) + '</b> <span class="mu">· ' + esc(d.luc) + (d.ma_don_san ? ' · ' + esc(d.ma_don_san) : '') + '</span></div>' +
        '<span class="st st-' + d.trang_thai + '">' + esc(d.ten_tt) + '</span></div>' +
        '<table style="margin-top:8px">' + d.dong.map(function (x) {
          return '<tr><td>' + esc(x.ten_mau) + ' <span class="mu">· ' + esc(x.ten_bien_the) + (x.mau_sac ? ' · ' + esc(x.mau_sac) : '') + (x.chu ? ' · "' + esc(x.chu) + '"' : '') + '</span></td><td class="num">× ' + x.so_luong + '</td><td class="num">' + tien(x.thanh_tien) + '</td></tr>';
        }).join('') + '<tr><td colspan="2" class="num"><b>Tổng</b></td><td class="num gia">' + tien(d.tong_tien) + '</td></tr></table>' +
        '<div class="mu" style="margin-top:6px">Giao tới: ' + esc(d.noi_giao ? d.noi_giao.ten : '—') + (d.ma_van_don ? ' · Mã vận đơn: <b>' + esc(d.ma_van_don) + '</b>' : '') + '</div>' +
        (d.trang_thai === 'cho_nap' || d.trang_thai === 'cho_in' ? '<button class="btn2 btn-del" style="margin-top:8px" data-huy="' + esc(d.ma) + '">Huỷ đơn</button>' : '') + '</div>';
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
    var h = veMsg() + '<h2>Ví</h2><p class="sub">Nạp trước, mỗi đơn trừ thẳng theo gram × ' + tien(st.me.gia_gram) + '.</p>' +
      '<div class="box"><div class="mu">Số dư</div><div style="font-size:28px;font-weight:800;color:var(--gd)">' + tien(v.so_du) + '</div>' +
      (v.can_them > 0 ? '<div class="msg err" style="margin:8px 0 0">Cần nạp thêm ' + tien(v.can_them) + ' để chạy các đơn đang chờ nạp tiền.</div>' : '') + '</div>' +
      '<div class="box"><b>Nạp tiền bằng chuyển khoản</b><div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:10px;align-items:flex-start">' +
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
      return '<div class="box" style="display:flex;justify-content:space-between;gap:10px"><div><b>' + esc(n.ten) + '</b><div>' + esc(n.dia_chi) + '</div><div class="mu">' + esc(n.sdt || '') + ' ' + esc(n.ghi_chu || '') + '</div></div><button class="btn2 btn-del" data-xng="' + n.id + '">Xoá</button></div>';
    }).join('') : '<div class="box mu">Chưa có nơi giao nào.</div>';
    h += '<div class="box"><b>Thêm nơi giao</b><form id="fNg" style="margin-top:10px"><div class="row">' +
      '<label class="f">Tên (vd: Line bay ABC Tân Bình)<input name="ten" required maxlength="120"></label>' +
      '<label class="f">Số điện thoại<input name="sdt" maxlength="20"></label></div>' +
      '<label class="f" style="margin-top:10px">Địa chỉ<input name="dia_chi" required maxlength="300"></label>' +
      '<label class="f" style="margin-top:10px">Ghi chú<input name="ghi_chu" maxlength="300"></label>' +
      '<button class="btn" style="margin-top:12px">Lưu nơi giao</button></form></div>';
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
    lsSet(TOKEN_KEY, null); st.me = null; st.mau = { chung: null, rieng: null }; st.noiGiao = null; veDangNhap();
  };

  khoiDong();
})();
</script>
</body>
</html>
