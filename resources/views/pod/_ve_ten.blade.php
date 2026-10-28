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
  /** Vẽ lên canvas: d = khung (w, h, khung, o_ten), ten = chữ cần in, mau = tên màu nhựa, font = font chữ tên. */
  function ve(cv, d, ten, mau, font) {
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
  return { ve: ve, tai: tai, choFont: choFont, mauHex: mauHex, khung: khung, css: css };
})();
</script>
