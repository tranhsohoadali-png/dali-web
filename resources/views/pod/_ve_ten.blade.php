{{-- Bộ vẽ ảnh xem trước mẫu khắc tên (dùng chung cổng seller + trang mẫu công khai).
     Khung 2D lấy từ file 3D thật (SVG path đơn vị mm), tên vẽ bằng font Pacifico vào ô tên của mẫu.
     Gần giống bản in, không phải ảnh chụp từ file in. --}}
<script>
window.PodVeTen = (function () {
  'use strict';
  // Tên màu nhựa -> mã màu để vẽ (tên màu là thứ ghi vào đơn; mã chỉ để xem trước)
  var BANG = {
    'trắng': '#F4F2EC', 'kem': '#EADBC0', 'be': '#D8C29D', 'đỏ': '#C62D2D', 'đỏ đô': '#8E1F2A', 'hồng': '#E8558B',
    'xanh rêu': '#2F5D3A', 'xanh lá': '#3E8E41', 'xanh lá đậm': '#245C36', 'xanh dương': '#2F6DB3', 'xanh ngọc': '#2AA79B',
    'vàng': '#F2C230', 'vàng đồng': '#C9A24B', 'gold': '#C9A24B', 'cam': '#EE7D2B', 'tím': '#7D4FB0', 'đen': '#2A2A2A',
    'nâu': '#7A4E2D', 'bạc': '#B9BEC4', 'xám': '#8C9196', 'trắng ngọc trai': '#EFEAE0', 'trắng ngọc trai (silk)': '#EFEAE0'
  };
  function mauHex(ten) {
    var k = String(ten || '').trim().toLowerCase();
    if (/^#[0-9a-f]{6}$/i.test(k)) return k;
    return BANG[k] || '#D9C9A6';
  }
  function toi(hex, k) { // làm tối màu để vẽ thành bên + bóng
    var n = parseInt(hex.slice(1), 16), r = n >> 16, g = (n >> 8) & 255, b = n & 255;
    var f = function (v) { return Math.max(0, Math.round(v * (1 - k))); };
    return 'rgb(' + f(r) + ',' + f(g) + ',' + f(b) + ')';
  }
  var DAM = { 'Dancing Script': '700 ' }; // font cần nét đậm cho đủ dày khi in
  function css(font, co) { return (DAM[font] || '') + co + 'px "' + font + '"'; }
  var hua = {};
  function choFont(font) {
    font = font || 'Pacifico';
    if (!hua[font]) hua[font] = (document.fonts && document.fonts.load)
      ? document.fonts.load(css(font, 40), 'Aaăâêôơưđ Ẵ').catch(function () {})
      : Promise.resolve();
    return hua[font];
  }
  function nen(ctx, W, H, sang) { // nhựa sáng -> nền xanh thông đậm cho nổi
    var g = ctx.createLinearGradient(0, 0, W, H);
    g.addColorStop(0, sang ? '#3A5240' : '#FAF6EF'); g.addColorStop(1, sang ? '#24362A' : '#EDE4D5');
    ctx.fillStyle = g; ctx.fillRect(0, 0, W, H);
  }
  function laSang(hex) { var n = parseInt(hex.slice(1), 16); return (0.299 * (n >> 16) + 0.587 * ((n >> 8) & 255) + 0.114 * (n & 255)) / 255 > 0.7; }
  function tam(W, H) { var c = document.createElement('canvas'); c.width = W; c.height = H; return c; }
  function to(mask, mau) { // tô màu lên hình mặt nạ
    var c = tam(mask.width, mask.height), x = c.getContext('2d');
    x.drawImage(mask, 0, 0); x.globalCompositeOperation = 'source-in'; x.fillStyle = mau; x.fillRect(0, 0, c.width, c.height);
    return c;
  }
  /**
   * Móc khoá tên (d.ve = 'moc_khoa'), dựng giống mẫu app Thay tên “Móc khoá tên mũ Noel” (mm):
   * đế = chữ nở d.vien ∪ mũ ∪ tai lỗ; mũ (d.mu: path thân/viền, gốc = góc trên-trái chữ đầu, trục y xuống) nhân d.mu.ti_le;
   * lỗ Ø2·d.lo_r: dò x sau chữ đầu, hạ từ trên xuống tới khi sắp chạm chữ (khe d.khe_lo) / mũ — lấy chỗ thấp nhất.
   * mau = “Đỏ + chữ trắng” (màu đế + màu chữ); thân mũ luôn đỏ. Canvas tự đặt chiều cao theo tên.
   * tuy.ti = hệ số cỡ (To 1.3: chữ, viền, mũ, vành lỗ nhân theo, lỗ Ø giữ nguyên như app Thay tên); tuy.trong = nền trong suốt.
   * Trả { hop: khung vật trên canvas (px), mm: [rộng, cao] thật }.
   */
  function veMocKhoa(cv, d0, ten, mau, font, tuy) {
    tuy = tuy || {};
    var ti = tuy.ti || 1, d = Object.assign({}, d0, { cao_hoa: d0.cao_hoa * ti, vien: d0.vien * ti, vanh: d0.vanh * ti, khe_lo: d0.khe_lo * ti,
      mu: Object.assign({}, d0.mu, { ti_le: d0.mu.ti_le * ti }) });
    font = font || d.font || 'Pacifico';
    var p = String(mau || '').split(/\s*\+\s*chữ\s*/i), cDe = mauHex(p[0] || 'Đỏ'), cChu = mauHex(p[1] || 'Trắng');
    var DO = '#C62D2D', TRANG = '#F4F2EC', chuDo = cChu === DO;
    var m0 = tam(8, 8).getContext('2d');
    m0.font = css(font, 100); var hH = m0.measureText('H').actualBoundingBoxAscent || 70;
    var co = 100 * d.cao_hoa / hH; // cỡ chữ (mm) để chữ hoa cao d.cao_hoa
    m0.font = css(font, co);
    var mt = m0.measureText(ten);
    var c0 = (ten.replace(/^\s+/, '')[0] || 'A').normalize('NFD')[0], m1 = m0.measureText(c0);
    var neo = [-m1.actualBoundingBoxLeft, -m1.actualBoundingBoxAscent], k = d.mu.ti_le;
    var than = new Path2D(d.mu.than), vien = new Path2D(d.mu.vien), mb = d.mu.khung; // khung mũ [x0,y0,x1,y1] quanh neo
    var chuBox = [-mt.actualBoundingBoxLeft, -mt.actualBoundingBoxAscent, mt.actualBoundingBoxRight, mt.actualBoundingBoxDescent];
    var muBox = [neo[0] + mb[0] * k, neo[1] + mb[1] * k, neo[0] + mb[2] * k, neo[1] + mb[3] * k];
    function veChu(x, them) { x.font = css(font, co); x.textBaseline = 'alphabetic'; x.lineJoin = 'round'; if (them) { x.lineWidth = 2 * them; x.strokeText(ten, 0, 0); } x.fillText(ten, 0, 0); }
    function veMu(x, them) {
      x.save(); x.translate(neo[0], neo[1]); x.scale(k, k);
      if (them) { x.lineJoin = 'round'; x.lineWidth = 2 * them / k; x.stroke(than); x.stroke(vien); }
      x.fill(than); x.fill(vien); x.restore();
    }
    // 1) dò lỗ móc trên ảnh chữ / mũ đã nở (8 px/mm)
    var R = 8, r = d.lo_r, khe = r + d.khe_lo, kheMu = r + 0.8;
    var bx0 = Math.min(chuBox[0], muBox[0]) - khe - 1, by0 = Math.min(chuBox[1], muBox[1]) - khe - 2;
    var bx1 = Math.max(chuBox[2], muBox[2]) + khe + 1, by1 = Math.max(chuBox[3], muBox[3]) + khe + 1;
    var dc = tam(Math.ceil((bx1 - bx0) * R), Math.ceil((by1 - by0) * R)), dx = dc.getContext('2d');
    dx.setTransform(R, 0, 0, R, -bx0 * R, -by0 * R); dx.fillStyle = dx.strokeStyle = '#000';
    veChu(dx, khe); veMu(dx, kheMu);
    var px = dx.getImageData(0, 0, dc.width, dc.height).data, lo = null;
    for (var x = m1.actualBoundingBoxRight - 3 * ti; x <= m1.actualBoundingBoxRight + 0.9 * d.cao_hoa; x += 0.25) {
      var ix = Math.round((x - bx0) * R), iy = 0;
      if (ix < 0 || ix >= dc.width) continue;
      while (iy < dc.height && px[(iy * dc.width + ix) * 4 + 3] < 128) iy++;
      var y = by0 + (iy - 1) / R;
      if (!lo || y > lo[1] + 1e-6) lo = [x, y];
    }
    lo = lo || [chuBox[2] + d.vien + r, 0];
    var tai = r + d.vanh;
    // 2) khung vẽ (mm) + đặt chiều cao canvas theo tên
    var v = d.vien, X0 = Math.min(chuBox[0] - v, muBox[0], lo[0] - tai), Y0 = Math.min(chuBox[1] - v, muBox[1], lo[1] - tai);
    var X1 = Math.max(chuBox[2] + v, muBox[2], lo[0] + tai), Y1 = Math.max(chuBox[3] + v, muBox[3], lo[1] + tai);
    var W = cv.width, le = 0.09 * W, s = (W - 2 * le) / (X1 - X0);
    cv.height = Math.round(Math.min(Math.max((Y1 - Y0) * s + 2 * le, 0.42 * W), 1.1 * W));
    var H = cv.height; s = Math.min(s, (H - 2 * le) / (Y1 - Y0));
    var ox = (W - (X1 - X0) * s) / 2 - X0 * s, oy = (H - (Y1 - Y0) * s) / 2 - Y0 * s;
    var ctx = cv.getContext('2d'); ctx.setTransform(1, 0, 0, 1, 0, 0);
    if (tuy.trong) ctx.clearRect(0, 0, W, H); else nen(ctx, W, H, laSang(cDe));
    // 3) mặt nạ đế
    var md = tam(W, H), mx = md.getContext('2d');
    mx.setTransform(s, 0, 0, s, ox, oy); mx.fillStyle = mx.strokeStyle = '#000';
    veChu(mx, v); veMu(mx, 0); mx.beginPath(); mx.arc(lo[0], lo[1], tai, 0, 7); mx.fill();
    mx.globalCompositeOperation = 'destination-out'; mx.beginPath(); mx.arc(lo[0], lo[1], r, 0, 7); mx.fill();
    var day = 0.5 * s, sauDe = toi(cDe, cDe === TRANG ? 0.16 : 0.32);
    if (!tuy.trong) {
      ctx.save(); ctx.shadowColor = 'rgba(45,30,10,.32)'; ctx.shadowBlur = 2.2 * s; ctx.shadowOffsetX = 0.9 * s; ctx.shadowOffsetY = 1.3 * s;
      ctx.drawImage(to(md, sauDe), 0, 0); ctx.restore();
    }
    ctx.drawImage(to(md, sauDe), day, day * 1.3);
    ctx.drawImage(to(md, cDe), 0, 0);
    // 4) lớp 2: chữ (+ vành mũ khi chữ trắng), rồi thân mũ đỏ
    var ml = tam(W, H), lx = ml.getContext('2d');
    lx.setTransform(s, 0, 0, s, ox, oy); lx.fillStyle = '#000'; veChu(lx, 0);
    if (!chuDo) { lx.save(); lx.translate(neo[0], neo[1]); lx.scale(k, k); lx.fill(vien); lx.restore(); }
    var sauChu = toi(cChu, cChu === TRANG ? 0.2 : 0.32), d2 = 0.32 * s;
    ctx.drawImage(to(ml, sauChu), d2, d2 * 1.3); ctx.drawImage(to(ml, cChu), 0, 0);
    var mm = tam(W, H), mmx = mm.getContext('2d');
    mmx.setTransform(s, 0, 0, s, ox, oy); mmx.translate(neo[0], neo[1]); mmx.scale(k, k); mmx.fillStyle = '#000'; mmx.fill(than);
    ctx.drawImage(to(mm, toi(DO, 0.32)), d2 * 1.6, d2 * 2.1); ctx.drawImage(to(mm, DO), 0, 0);
    // thành lỗ móc
    ctx.save(); ctx.setTransform(s, 0, 0, s, ox, oy); ctx.strokeStyle = toi(cDe, 0.45); ctx.lineWidth = 0.35;
    ctx.beginPath(); ctx.arc(lo[0], lo[1], r, 0, 7); ctx.stroke(); ctx.restore();
    return { hop: [ox + X0 * s, oy + Y0 * s, ox + X1 * s, oy + Y1 * s], mm: [X1 - X0, Y1 - Y0] };
  }
  /**
   * Vẽ lên canvas: d = khung (w, h, khung, o_ten), ten = chữ cần in, mau = tên màu nhựa, font = font chữ tên.
   * tuy = {trong: nền trong suốt + bỏ bóng (ảnh kích thước), ti: hệ số cỡ móc khoá}. Trả { hop (px), mm: [rộng, cao] }.
   */
  function ve(cv, d, ten, mau, font, tuy) {
    tuy = tuy || {};
    if (d && d.ve === 'moc_khoa') return veMocKhoa(cv, d, String(ten || '').trim() || 'Tên', mau, font, tuy);
    var W = cv.width, H = cv.height, ctx = cv.getContext('2d');
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.clearRect(0, 0, W, H);
    var c = mauHex(mau), sau = toi(c, c === '#F4F2EC' ? 0.16 : 0.32);
    var n = parseInt(c.slice(1), 16), sang = (0.299 * (n >> 16) + 0.587 * ((n >> 8) & 255) + 0.114 * (n & 255)) / 255 > 0.7;
    var nen = ctx.createLinearGradient(0, 0, W, H); // nhựa sáng -> nền xanh thông đậm cho nổi
    nen.addColorStop(0, sang ? '#3A5240' : '#FAF6EF'); nen.addColorStop(1, sang ? '#24362A' : '#EDE4D5');
    if (!tuy.trong) { ctx.fillStyle = nen; ctx.fillRect(0, 0, W, H); }
    var pad = Math.min(W, H) * 0.08, s = Math.min((W - 2 * pad) / d.w, (H - 2 * pad) / d.h);
    var day = 0.55; // độ lệch vẽ thành bên (mm) cho cảm giác dày 3 mm
    var tx = (W - d.w * s) / 2, ty = (H - d.h * s) / 2;
    ctx.translate(tx, ty); ctx.scale(s, s);
    var khung = new Path2D(d.khung);
    if (!tuy.trong) {
      ctx.save(); ctx.shadowColor = 'rgba(45,30,10,.30)'; ctx.shadowBlur = 16; ctx.shadowOffsetX = 6; ctx.shadowOffsetY = 9;
      ctx.fillStyle = sau; ctx.fill(khung, 'evenodd'); ctx.restore();
    }
    ctx.save(); ctx.translate(day, day * 1.3); ctx.fillStyle = sau; ctx.fill(khung, 'evenodd'); ctx.restore();
    ctx.fillStyle = c; ctx.fill(khung, 'evenodd');
    ten = String(ten || '').trim();
    if (ten && d.o_ten) {
      var o = d.o_ten; font = font || d.font || 'Pacifico';
      ctx.save(); ctx.translate(o.cx, o.cy); ctx.rotate((o.goc || 0) * Math.PI / 180);
      ctx.font = css(font, 10); ctx.textAlign = 'center'; ctx.textBaseline = 'alphabetic';
      var m = ctx.measureText(ten), cao = (m.actualBoundingBoxAscent + m.actualBoundingBoxDescent) || 12;
      var co = 10 * Math.min(o.w / Math.max(m.width, 0.01), (o.h * 1.08) / cao);
      ctx.font = css(font, co); m = ctx.measureText(ten);
      var y = (m.actualBoundingBoxAscent - m.actualBoundingBoxDescent) / 2;
      ctx.fillStyle = sau; ctx.fillText(ten, day, y + day * 1.3);
      ctx.fillStyle = c; ctx.fillText(ten, 0, y);
      ctx.restore();
    }
    return { hop: [tx, ty, tx + d.w * s, ty + d.h * s], mm: [d.w, d.h] };
  }
  /** Số đo ghi lên ảnh: inch trước (kiểu Mỹ, dấu chấm), cm/mm trong ngoặc. */
  function chuSo(mm, uoc) {
    var i = mm / 25.4, si = (i >= 1 ? i.toFixed(1) : i.toFixed(2)).replace(/\.0$/, '');
    var phu = mm >= 10 ? (mm / 10).toFixed(1).replace(/\.0$/, '') + ' cm' : (Math.round(mm * 10) / 10) + ' mm';
    return (uoc ? '~' : '') + si + ' in (' + phu + ')';
  }
  function muiTen(ctx, x0, y0, x1, y1, F) { // đường kích thước có mũi tên hai đầu
    var a = Math.atan2(y1 - y0, x1 - x0), L = Math.hypot(x1 - x0, y1 - y0), m = 11 * F, rong = 4.5 * F;
    var ngoai = L < 2.6 * m; // đoạn ngắn (bề dày): mũi tên đặt ngoài, chĩa vào
    ctx.beginPath(); ctx.moveTo(x0, y0); ctx.lineTo(x1, y1); ctx.stroke();
    [[x0, y0, a + Math.PI], [x1, y1, a]].forEach(function (p) {
      var g = ngoai ? p[2] + Math.PI : p[2], bx = p[0] - Math.cos(g) * m, by = p[1] - Math.sin(g) * m;
      if (ngoai) { ctx.beginPath(); ctx.moveTo(p[0], p[1]); ctx.lineTo(p[0] - Math.cos(g) * 2.4 * m, p[1] - Math.sin(g) * 2.4 * m); ctx.stroke(); }
      ctx.beginPath(); ctx.moveTo(p[0], p[1]);
      ctx.lineTo(bx - Math.sin(g) * rong, by + Math.cos(g) * rong); ctx.lineTo(bx + Math.sin(g) * rong, by - Math.cos(g) * rong); ctx.closePath(); ctx.fill();
    });
  }
  /**
   * Ảnh kích thước: hình nhìn trước (rộng × cao) + nhìn bên (dày × cao) CÙNG tỉ lệ, đường kích thước ghi inch (cm).
   * o.truoc = {anh, hop?: [x0,y0,x1,y1] vùng vật trong ảnh (mặc định cả ảnh)}; o.ben = như trên, hoặc null = vật phẳng
   * (vẽ tấm dày o.kt.day màu o.mau); o.kt = {rong, cao, day} mm; o.nen = màu nền (mặc định trắng);
   * o.uoc = số rộng/cao là ước (đo theo tên trên ảnh) -> ghi dấu ~. Canvas tự đặt chiều cao.
   */
  function veKichThuoc(cv, o) {
    var W = cv.width, F = W / 900, kt = o.kt;
    var TREN = 46 * F, DUOI = 112 * F, TRAI = 150 * F, PHAI = 46 * F, KHE = 96 * F;
    var benMm = o.ben ? kt.day : Math.max(kt.day, 0.01);
    var s = (W - TRAI - PHAI - KHE) / (kt.rong + benMm);
    s = Math.min(s, (1.1 * W - TREN - DUOI) / kt.cao);
    var wT = kt.rong * s, wB = Math.max(benMm * s, o.ben ? 1 : 7 * F), hh = kt.cao * s;
    cv.height = Math.round(TREN + hh + DUOI);
    var ctx = cv.getContext('2d'); ctx.setTransform(1, 0, 0, 1, 0, 0);
    ctx.fillStyle = o.nen || '#FFFFFF'; ctx.fillRect(0, 0, W, cv.height);
    var x0 = TRAI + Math.max(0, (W - TRAI - PHAI - KHE - wT - wB) / 2), y0 = TREN, xb = x0 + wT + KHE;
    function dan(v, x, y, w, h) {
      var hp = v.hop || [0, 0, v.anh.naturalWidth || v.anh.width, v.anh.naturalHeight || v.anh.height];
      ctx.drawImage(v.anh, hp[0], hp[1], hp[2] - hp[0], hp[3] - hp[1], x, y, w, h);
    }
    // bóng mờ dưới chân
    [[x0, wT], [xb, wB]].forEach(function (p) {
      var cx = p[0] + p[1] / 2, r = Math.max(p[1], 30 * F) * 0.62, g = ctx.createRadialGradient(cx, 0, 1, cx, 0, r);
      g.addColorStop(0, 'rgba(0,0,0,.16)'); g.addColorStop(1, 'rgba(0,0,0,0)');
      ctx.save(); ctx.translate(0, y0 + hh); ctx.scale(1, 0.12); ctx.fillStyle = g; ctx.fillRect(cx - r, -r, 2 * r, 2 * r); ctx.restore();
    });
    dan(o.truoc, x0, y0, wT, hh);
    if (o.ben) dan(o.ben, xb, y0, wB, hh);
    else {
      var c = mauHex(o.mau || 'Trắng');
      ctx.fillStyle = c; ctx.fillRect(xb, y0, wB, hh);
      ctx.strokeStyle = toi(c, 0.35); ctx.lineWidth = 1.2 * F; ctx.strokeRect(xb, y0, wB, hh);
    }
    ctx.strokeStyle = ctx.fillStyle = '#3A3F44'; ctx.lineWidth = 2 * F;
    ctx.font = '700 ' + Math.round(25 * F) + 'px "Inter", "Segoe UI", Arial, sans-serif';
    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
    function gach(xa, ya, xc, yc) { ctx.save(); ctx.strokeStyle = '#A9AFB5'; ctx.lineWidth = 1.2 * F; ctx.setLineDash([5 * F, 4 * F]); ctx.beginPath(); ctx.moveTo(xa, ya); ctx.lineTo(xc, yc); ctx.stroke(); ctx.restore(); }
    // cao: bên trái hình trước
    var xc = x0 - 46 * F;
    gach(x0 - 6 * F, y0, xc - 10 * F, y0); gach(x0 - 6 * F, y0 + hh, xc - 10 * F, y0 + hh);
    muiTen(ctx, xc, y0, xc, y0 + hh, F);
    ctx.save(); ctx.translate(xc - 24 * F, y0 + hh / 2); ctx.rotate(-Math.PI / 2); ctx.fillText(chuSo(kt.cao, o.uoc), 0, 0); ctx.restore();
    // rộng: dưới hình trước; dày: dưới hình bên
    var yd = y0 + hh + 42 * F;
    [[x0, wT, kt.rong, o.uoc], [xb, wB, kt.day, false]].forEach(function (p) {
      gach(p[0], y0 + hh + 6 * F, p[0], yd + 10 * F); gach(p[0] + p[1], y0 + hh + 6 * F, p[0] + p[1], yd + 10 * F);
      muiTen(ctx, p[0], yd, p[0] + p[1], yd, F);
      var tx = Math.min(Math.max(p[0] + p[1] / 2, 120 * F), W - 120 * F);
      ctx.fillText(chuSo(p[2], p[3]), tx, yd + 36 * F);
    });
  }
  var anhDaTai = {};
  function taiAnh(url) {
    if (!anhDaTai[url]) anhDaTai[url] = new Promise(function (ok, loi) { var i = new Image(); i.onload = function () { ok(i); }; i.onerror = loi; i.src = url; });
    return anhDaTai[url];
  }
  /**
   * Ảnh kích thước cho lựa chọn hiện tại. n = {kt: kich_thuoc của cỡ, hinh: {truoc, ben} url hình chiếu (mẫu khối),
   * xt: xem_truoc (mẫu khắc tên), kieu, chu, mau, font}. Mẫu khắc tên vẽ hình trước từ chính ảnh xem trước
   * (móc khoá đo theo tên đang gõ). Trả Promise.
   */
  function veKt(cv, n) {
    var kt = n.kt || {}, h = n.hinh && n.hinh.kieu ? (n.hinh.kieu[n.kieu] || {}) : n.hinh; // mẫu khối nhiều kiểu: hình của kiểu đang chọn
    if (h && h.truoc && kt.rong) {
      return Promise.all([taiAnh(h.truoc), h.ben ? taiAnh(h.ben) : null]).then(function (a) {
        veKichThuoc(cv, { truoc: { anh: a[0] }, ben: a[1] ? { anh: a[1] } : null, kt: kt });
      });
    }
    if (!n.xt) return Promise.resolve();
    var k = khung(n.xt, n.kieu);
    return choFont(n.font).then(function () {
      var off = tam(1400, 1400); datCao(off, k);
      var r = ve(off, k, n.chu || 'Tên của bạn', n.mau, n.font, { trong: true, ti: kt.ti_le || 1 });
      // móc khoá: bề ngang đo trên ảnh × hệ số theo font (xem_truoc.hieu_chinh_rong, đo so với app Thay tên)
      var hc = ((n.xt.hieu_chinh_rong || {})[n.font || n.xt.font]) || 1;
      var dims = kt.rong ? kt : { rong: r.mm[0] * hc, cao: r.mm[1], day: kt.day || 3 };
      var mDe = String(n.mau || '').split(/\s*\+\s*chữ\s*/i)[0];
      veKichThuoc(cv, { truoc: { anh: off, hop: r.hop }, ben: null, kt: dims, mau: mDe, uoc: !kt.rong, nen: laSang(mauHex(mDe || 'Trắng')) ? '#E3E7EB' : '#FFFFFF' });
    });
  }
  /** Tải ảnh PNG đang vẽ. */
  function tai(cv, tenFile) {
    cv.toBlob(function (b) {
      var a = document.createElement('a');
      a.href = URL.createObjectURL(b); a.download = tenFile; document.body.appendChild(a); a.click();
      setTimeout(function () { URL.revokeObjectURL(a.href); a.remove(); }, 1500);
    }, 'image/png');
  }
  /** Khung của một kiểu: mẫu nhiều kiểu lấy xem_truoc.kieu[k], mẫu một kiểu dùng chính xem_truoc. */
  function khung(xt, kieu) { return (xt && xt.kieu && kieu && xt.kieu[kieu]) ? xt.kieu[kieu] : xt; }
  /** Đặt chiều cao canvas theo tỉ lệ khung (mẫu khung cố định); móc khoá tự đặt khi vẽ. */
  function datCao(cv, k) { if (k && k.w && k.h) cv.height = Math.round(cv.width * k.h / k.w); }
  /** Nền chấm màu ở chip: một màu, hoặc phối "Đỏ + chữ trắng" -> chấm hai nửa (đế / chữ). */
  function nenChip(ten) {
    var p = String(ten || '').split(/\s*\+\s*chữ\s*/i);
    return p.length > 1 ? 'linear-gradient(135deg,' + mauHex(p[0]) + ' 0 58%,' + mauHex(p[1]) + ' 58% 100%)' : mauHex(p[0]);
  }
  return { ve: ve, tai: tai, choFont: choFont, mauHex: mauHex, nenChip: nenChip, khung: khung, datCao: datCao, css: css,
    veKt: veKt, veKichThuoc: veKichThuoc, chuSo: chuSo };
})();
</script>
