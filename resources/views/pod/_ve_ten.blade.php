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
    'nâu': '#7A4E2D', 'bạc': '#B9BEC4', 'xám': '#8C9196', 'trắng ngọc trai': '#EFEAE0', 'trắng ngọc trai (silk)': '#EFEAE0',
    'áo trắng đai đỏ': '#C8102E', 'áo trắng đai đen': '#1A1A1A', 'áo trắng đai tím': '#5B2C9F'
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
  var DAM = { 'Dancing Script': '700 ', 'Baloo 2': '800 ' }; // font cần nét đậm cho đủ dày khi in
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
  /* ---------- Móc khóa đuôi đai (d.ve = 'dai'): 2 dòng chữ khảm trên 2 đuôi đai, ghi trong đơn dạng "SOPHIA / 2026".
     Số đo + công thức xếp chữ = mẫu app Thay tên mau_moc_khoa_karate.py (số lấy chung ở d.so). --------- */
  function chuanDai(s) { // viết hoa, bỏ dấu: José -> JOSE, Đức -> DUC
    return String(s || '').replace(/đ/g, 'd').replace(/Đ/g, 'D').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase().replace(/\s+/g, ' ').trim();
  }
  function tachDai(chu) {
    var p = String(chu || '').split('/');
    return { ten: chuanDai(p[0]), nam: chuanDai(p.slice(1).join(' ')) };
  }
  function ghepDai(ten, nam) { ten = chuanDai(ten); nam = chuanDai(nam); return nam ? ten + ' / ' + nam : ten; }
  function doDong(d, txt) { // -> {sx: nén dọc dòng, L: dài dòng mm}
    var n = d.so, tong = 0, chu = 0, cach = 0;
    for (var i = 0; i < txt.length; i++) {
      if (txt[i] === ' ') { cach++; continue; }
      chu++; tong += d.glyph[txt[i]] ? d.glyph[txt[i]].w : 0;
    }
    var coDinh = n.khe * (chu - 1) + 2 * n.no_x * chu + cach * n.khoang;
    var sx = tong ? Math.min(1, (n.l_max - coDinh) / tong) : 1;
    return { sx: sx, L: tong * sx + coDinh };
  }
  /** Câu báo lỗi nếu chữ không in được trên đuôi đai, null nếu được. Máy chủ kiểm lại y hệt (PodMau::chuanDai). */
  function kiemDai(d, chu) {
    var p = tachDai(chu), ds = [[p.ten, 'Tên'], [p.nam, 'Chữ đuôi trái']];
    if (!/[A-Z0-9]/.test(p.ten)) return 'Nhập tên cần in.';
    for (var i = 0; i < 2; i++) {
      var t = ds[i][0], nhan = ds[i][1];
      if (!t) continue;
      if (/[^A-Z0-9 \-]/.test(t)) return nhan + ' chỉ in được chữ A–Z, số 0–9, dấu cách và dấu gạch ngang.';
      if (t.length > (d.toi_da || 10)) return nhan + ' tối đa ' + (d.toi_da || 10) + ' ký tự.';
      if (doDong(d, t).sx < d.so.nen_min) return nhan + ' dài quá so với đuôi đai, bớt chữ hoặc viết tắt.';
    }
    return null;
  }
  var nenDai = {};
  function veDai(cv, d, chu, mau, tuy) {
    var url = (d.nen_mau && d.nen_mau[mau]) || d.nen, im = nenDai[url]; // mỗi màu đai một ảnh nền, không có thì dùng ảnh gốc
    if (!im) { im = nenDai[url] = new Image(); im.src = url; }
    var dang = chu + '|' + mau;
    cv._chuDai = dang;
    if (!im.complete || !im.naturalWidth) {
      im.addEventListener('load', function () { if (cv._chuDai === dang) veDai(cv, d, chu, mau, tuy); }, { once: true });
      return { hop: [0, 0, cv.width, cv.height], mm: [d.w, d.h] };
    }
    var ctx = cv.getContext('2d'), W = cv.width, H = cv.height, k = W / d.w, s = d.s * k, n = d.so;
    ctx.setTransform(1, 0, 0, 1, 0, 0); ctx.clearRect(0, 0, W, H); ctx.drawImage(im, 0, 0, W, H);
    var p = chu === 'Tên của bạn' ? tachDai(d.mau_chu) : tachDai(chu);
    ctx.fillStyle = '#F4F2EC';
    [[p.ten, d.duoi.phai], [p.nam, d.duoi.trai]].forEach(function (dong) {
      var txt = dong[0], t = dong[1];
      if (!txt) return;
      var dd = doDong(d, txt), lui = n.lech_u + Math.max(0, dd.L - n.l_chuan) / 2;
      var tx = t.o[0] + t.u[0] * lui, tz = t.o[1] + t.u[1] * lui;
      // (a dọc dòng, b lên đầu chữ) mm -> px ảnh chiếu thẳng
      ctx.setTransform(s * t.a[0], -s * t.a[1], s * t.b[0], -s * t.b[1], (tx - d.x0) * s, (d.z1 - tz) * s);
      var x = -dd.L / 2, cao = n.h / 2;
      for (var i = 0; i < txt.length; i++) {
        if (txt[i] === ' ') { x += n.khoang - n.khe; continue; }
        var g = d.glyph[txt[i]];
        if (!g) continue;
        g.p = g.p || new Path2D(g.d);
        for (var ox = -1; ox <= 1; ox++) for (var oy = -2; oy <= 2; oy++) { // nở nét lệch như bản in: ngang đuôi nhiều, dọc dòng ít
          ctx.save(); ctx.translate(x + n.no_x + ox * n.no_x, -cao + oy * n.no_y / 2); ctx.scale(dd.sx, 1); ctx.fill(g.p, 'evenodd'); ctx.restore();
        }
        x += g.w * dd.sx + 2 * n.no_x + n.khe;
      }
    });
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    return { hop: [0, 0, W, H], mm: [d.w, d.h] };
  }
  /* ---------- Dấu bánh quy (d.ve = 'dau_banh'): ảnh nền d.nen = con dấu + khuôn + chiếc bánh CHƯA có tên; tên chạy cung dưới
     mặt dấu, xếp từng chữ y như mau/dau_banh/dau_banh.scad của app Thay tên: bảng bề rộng d.chars/d.adv (em), hằng số d.so,
     d.banh = {cx, cy: tâm mặt dấu, s: đơn vị khung trên mỗi mm} trong khung d.w × d.h. Máy chủ kiểm lại y hệt (PodMau::chuanDauBanh). */
  function chuanDauBanh(s) { return String(s || '').normalize('NFC').replace(/\s+/g, ' ').trim().toUpperCase(); }
  function advDauBanh(d, c) { var i = d.chars.indexOf(c); return i < 0 ? 0.62 : d.adv[i]; }
  function coDauBanh(d, t) { // -> {co: cỡ chữ OpenSCAD app sẽ dựng, cung: độ dài cung (độ) đo ở giữa chiều cao chữ}
    var so = d.so, tong = 0;
    Array.from(t).forEach(function (c) { tong += advDauBanh(d, c); });
    var k = so.he_so_dan * Math.max(tong, 0.01) / 0.72, th = so.cung_max * Math.PI / 180, c = so.cap / (2 * 0.72);
    var co = Math.max(so.co_min, Math.min(so.co, th * so.r_ten / (k + th * c) - 0.001));
    return { co: co, cung: k * co / (so.r_ten - c * co) * 180 / Math.PI };
  }
  /** Câu báo lỗi nếu tên không in được lên mặt dấu, null nếu được. */
  function kiemDauBanh(d, chu) {
    var t = chuanDauBanh(chu), ds = Array.from(t);
    if (!/[\p{L}\p{N}]/u.test(t)) return 'Nhập tên cần in.';
    var la = ds.filter(function (c, i) { return d.chars.indexOf(c) < 0 && ds.indexOf(c) === i; });
    if (la.length) return 'Mặt dấu chưa in được ký tự ' + la.join(' ') + ". Chỉ nhận chữ cái tiếng Việt / tiếng Anh, số 0–9, dấu cách và & ' . , - !";
    if (ds.length > (d.toi_da || 24)) return 'Tên tối đa ' + (d.toi_da || 24) + ' ký tự.';
    if (coDauBanh(d, t).cung > d.so.cung_max + 0.01) return 'Tên dài quá so với cung dưới mặt dấu, bớt chữ hoặc viết tắt.';
    return null;
  }
  var nenDB = {};
  function veDauBanh(cv, d, chu) {
    var im = nenDB[d.nen], fnt = d.font || 'Baloo 2';
    if (!im) { im = nenDB[d.nen] = new Image(); im.src = d.nen; }
    var t = chuanDauBanh(chu === 'Tên của bạn' ? d.mau_chu : chu);
    cv._chuDB = t;
    var lai = function () { if (cv._chuDB === t) veDauBanh(cv, d, chu); };
    var W = cv.width, H = cv.height;
    if (!im.complete || !im.naturalWidth) { im.addEventListener('load', lai, { once: true }); return { hop: [0, 0, W, H], mm: [d.w, d.h] }; }
    if (document.fonts && !document.fonts.check(css(fnt, 20), 'ẮẪ')) choFont(fnt).then(lai); // vẽ tạm, font về thì vẽ lại
    var ctx = cv.getContext('2d'), k = W / d.w, so = d.so, cx = d.banh.cx * k, cy = d.banh.cy * k, s = d.banh.s * k; // s = px/mm
    ctx.setTransform(1, 0, 0, 1, 0, 0); ctx.clearRect(0, 0, W, H); ctx.drawImage(im, 0, 0, W, H);
    if (!t) return { hop: [0, 0, W, H], mm: [d.w, d.h] };
    var em = coDauBanh(d, t).co / 0.72, rTen = so.r_ten, rGiua = rTen - so.cap * em / 2, ds = Array.from(t);
    var adv = ds.map(function (c) { return advDauBanh(d, c) * em * so.he_so_dan; }), the = adv.reduce(function (a, x) { return a + x; }, 0) / rGiua;
    ctx.font = css(fnt, em * s);
    var cao = 0; ds.forEach(function (c) { cao = Math.max(cao, ctx.measureText(c).actualBoundingBoxAscent || 0); });
    var rGach = Math.min(so.r_gach_max, rTen - cao / s - so.khe_gach - so.net / 2); // dấu chồng thò vào trong -> hạ gạch như app
    function veLop(mau, lech) { // chữ lõm trên bánh: đáy tối rồi vách sáng lệch xuống phải (như ảnh xem trước của app)
      ctx.fillStyle = ctx.strokeStyle = mau;
      if (rGach >= so.r_gach_min) {
        ctx.setTransform(1, 0, 0, 1, 0, 0); ctx.lineWidth = so.net * s; ctx.lineCap = 'round';
        [[so.goc_gach[0], so.goc_gach[1]], [so.goc_gach[2], so.goc_gach[3]]].forEach(function (g) { // góc toán học (ngược chiều kim)
          ctx.beginPath(); ctx.arc(cx + lech, cy + lech, rGach * s, -g[0] * Math.PI / 180, -g[1] * Math.PI / 180, true); ctx.stroke();
        });
      }
      var a0 = 0;
      ds.forEach(function (c, i) { // chân chữ ở bán kính r_ten (ngoài), đầu chữ quay vào tâm, đọc trái -> phải
        var al = (a0 + adv[i] / 2) / rGiua - the / 2, ca = Math.cos(al), sa = Math.sin(al); a0 += adv[i];
        ctx.setTransform(ca, -sa, sa, ca, cx + s * rTen * sa + lech, cy + s * rTen * ca + lech);
        ctx.textAlign = 'center'; ctx.textBaseline = 'alphabetic'; ctx.fillText(c, 0, 0);
      });
      ctx.setTransform(1, 0, 0, 1, 0, 0);
    }
    veLop('#8a5a2b', 0); veLop('#b07a40', 0.22 * s);
    return { hop: [0, 0, W, H], mm: [d.w, d.h] };
  }
  /** Kiểm chữ theo kiểu mẫu (đuôi đai / dấu bánh): câu báo lỗi hoặc null. Mẫu khung thường chỉ chặn số ký tự ở ô nhập. */
  function kiem(d, chu) {
    if (d && d.ve === 'dai') return kiemDai(d, chu);
    if (d && d.ve === 'dau_banh') return kiemDauBanh(d, chu);
    return null;
  }
  /**
   * Vẽ lên canvas: d = khung (w, h, khung, o_ten), ten = chữ cần in, mau = tên màu nhựa, font = font chữ tên.
   * tuy = {trong: nền trong suốt + bỏ bóng (ảnh kích thước), ti: hệ số cỡ móc khoá}. Trả { hop (px), mm: [rộng, cao] }.
   */
  function ve(cv, d, ten, mau, font, tuy) {
    tuy = tuy || {};
    if (d && d.ve === 'moc_khoa') return veMocKhoa(cv, d, String(ten || '').trim() || 'Tên', mau, font, tuy);
    if (d && d.ve === 'dai') return veDai(cv, d, String(ten || '').trim(), mau, tuy);
    if (d && d.ve === 'dau_banh') return veDauBanh(cv, d, String(ten || '').trim());
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
  /** Khung của một kiểu: mẫu nhiều kiểu lấy xem_truoc.kieu[k], mẫu một kiểu dùng chính xem_truoc. Dấu bánh: kiểu chỉ mang
      phần riêng (ảnh nền, tên mẫu) — gộp vào dữ liệu chung (bảng bề rộng chữ, hằng số cung, tâm mặt bánh). */
  function khung(xt, kieu) {
    if (!(xt && xt.kieu && kieu && xt.kieu[kieu])) return xt;
    return xt.ve === 'dau_banh' ? Object.assign({}, xt, { kieu: null }, xt.kieu[kieu]) : xt.kieu[kieu];
  }
  /** Đặt chiều cao canvas theo tỉ lệ khung (mẫu khung cố định); móc khoá tự đặt khi vẽ. */
  function datCao(cv, k) { if (k && k.w && k.h) cv.height = Math.round(cv.width * k.h / k.w); }
  /** Nền chấm màu ở chip: một màu, hoặc phối "Đỏ + chữ trắng" -> chấm hai nửa (đế / chữ). */
  function nenChip(ten) {
    var p = String(ten || '').split(/\s*\+\s*chữ\s*/i);
    return p.length > 1 ? 'linear-gradient(135deg,' + mauHex(p[0]) + ' 0 58%,' + mauHex(p[1]) + ' 58% 100%)' : mauHex(p[0]);
  }
  return { ve: ve, tai: tai, choFont: choFont, mauHex: mauHex, nenChip: nenChip, khung: khung, datCao: datCao, css: css,
    veKt: veKt, veKichThuoc: veKichThuoc, chuSo: chuSo, chuanDai: chuanDai, ghepDai: ghepDai, kiemDai: kiemDai,
    kiem: kiem, chuanDauBanh: chuanDauBanh, coDauBanh: coDauBanh };
})();
</script>
