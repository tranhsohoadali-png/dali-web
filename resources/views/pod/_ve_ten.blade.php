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
    'nâu': '#7A4E2D', 'bạc': '#B9BEC4', 'xám': '#8C9196'
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
   */
  function veMocKhoa(cv, d, ten, mau, font) {
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
    for (var x = m1.actualBoundingBoxRight - 3; x <= m1.actualBoundingBoxRight + 0.9 * d.cao_hoa; x += 0.25) {
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
    nen(ctx, W, H, laSang(cDe));
    // 3) mặt nạ đế
    var md = tam(W, H), mx = md.getContext('2d');
    mx.setTransform(s, 0, 0, s, ox, oy); mx.fillStyle = mx.strokeStyle = '#000';
    veChu(mx, v); veMu(mx, 0); mx.beginPath(); mx.arc(lo[0], lo[1], tai, 0, 7); mx.fill();
    mx.globalCompositeOperation = 'destination-out'; mx.beginPath(); mx.arc(lo[0], lo[1], r, 0, 7); mx.fill();
    var day = 0.5 * s, sauDe = toi(cDe, cDe === TRANG ? 0.16 : 0.32);
    ctx.save(); ctx.shadowColor = 'rgba(45,30,10,.32)'; ctx.shadowBlur = 2.2 * s; ctx.shadowOffsetX = 0.9 * s; ctx.shadowOffsetY = 1.3 * s;
    ctx.drawImage(to(md, sauDe), 0, 0); ctx.restore();
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
  }
  /** Vẽ lên canvas: d = khung (w, h, khung, o_ten), ten = chữ cần in, mau = tên màu nhựa, font = font chữ tên. */
  function ve(cv, d, ten, mau, font) {
    if (d && d.ve === 'moc_khoa') return veMocKhoa(cv, d, String(ten || '').trim() || 'Tên', mau, font);
    var W = cv.width, H = cv.height, ctx = cv.getContext('2d');
    ctx.setTransform(1, 0, 0, 1, 0, 0);
    var c = mauHex(mau), sau = toi(c, c === '#F4F2EC' ? 0.16 : 0.32);
    var n = parseInt(c.slice(1), 16), sang = (0.299 * (n >> 16) + 0.587 * ((n >> 8) & 255) + 0.114 * (n & 255)) / 255 > 0.7;
    var nen = ctx.createLinearGradient(0, 0, W, H); // nhựa sáng -> nền xanh thông đậm cho nổi
    nen.addColorStop(0, sang ? '#3A5240' : '#FAF6EF'); nen.addColorStop(1, sang ? '#24362A' : '#EDE4D5');
    ctx.fillStyle = nen; ctx.fillRect(0, 0, W, H);
    var pad = Math.min(W, H) * 0.08, s = Math.min((W - 2 * pad) / d.w, (H - 2 * pad) / d.h);
    var day = 0.55; // độ lệch vẽ thành bên (mm) cho cảm giác dày 3 mm
    ctx.translate((W - d.w * s) / 2, (H - d.h * s) / 2); ctx.scale(s, s);
    var khung = new Path2D(d.khung);
    ctx.save(); ctx.shadowColor = 'rgba(45,30,10,.30)'; ctx.shadowBlur = 16; ctx.shadowOffsetX = 6; ctx.shadowOffsetY = 9;
    ctx.fillStyle = sau; ctx.fill(khung, 'evenodd'); ctx.restore();
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
  return { ve: ve, tai: tai, choFont: choFont, mauHex: mauHex, nenChip: nenChip, khung: khung, datCao: datCao, css: css };
})();
</script>
