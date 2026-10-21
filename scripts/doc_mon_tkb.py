#!/usr/bin/env python3
"""
Đọc ảnh THỜI KHOÁ BIỂU bằng MÁY (không AI) -> đếm số thẻ mỗi môn. Bộ đọc DỰ PHÒNG khi AI lỗi (DocMonTkb.php).

  python3 doc_mon_tkb.py <anh.jpg|png|webp>   -> in JSON {"ok":..., "mon":[{"mon","so_luong"}], ...}
  DOCMON_THE='["TOÁN", …]'  danh sách tên thẻ Thư viện file in (PHP truyền) -> thẻ mới khớp đúng tên.

Cách làm:
  1. Dò ĐƯỜNG KẺ (nét mảnh thẳng dài, kể cả kẻ chấm chấm) bằng OpenCV, cắt từng Ô.
     Chỉ đọc chữ TRONG ô -> chú thích/tiêu đề ngoài bảng không bị đếm.
  2. Ô nền tối (VD TNXH nền xanh chữ trắng) -> đảo màu cho dễ đọc.
  3. Xếp mọi ô thành 1 ảnh dọc, OCR MỘT lượt (tesseract -l vie), gán chữ về ô theo toạ độ.
     Ô không ra môn -> đọc lại lượt 2 trên ảnh đã xoá gạch đỏ chính tả, phóng to.
  4. Khớp TÊN THẺ qua bảng viết tắt (T.A, L.TV, MT, ĐĐ, ÂN, SHL, TNXH…), bỏ tên GV "(…)"/sau "-", phân môn sau ":".
     Ô không khớp nhưng trông như tên môn (VD "Đọc Thư viện") vẫn giữ nguyên chữ (khong_ro) để xưởng xem.
Đo 2026-10-21: 9 ảnh đơn thật 323/324 ô đúng (đáp án 3 người đọc khớp nhau); 45 ảnh không phải TKB: 0 nhầm.
"""
import json, re, subprocess, sys, tempfile, os, unicodedata, difflib

import cv2
DEBUG = bool(os.environ.get('DOCMON_DEBUG'))
import numpy as np

# Tên chuẩn = ĐÚNG TÊN THẺ trong Thư viện file in (mau.tranhdali.vn) -> xưởng tìm thẳng file thẻ để in.
# Khoá = dạng "gọn" (bỏ dấu, chỉ a-z0-9). Thẻ mới trong thư viện được PHP truyền qua DOCMON_THE (khớp đúng tên).
ALIAS = {
    'TIẾNG VIỆT': ['tiengviet', 'tv', 'tviet', 'tiengvet', 'tiengvlet'],
    'TOÁN': ['toan'],
    'ENGLISH': ['tienganh', 'ta', 'anhvan', 'english', 'anh'],
    'ÔN TOÁN': ['ontoan'],
    'ÔN T.VIỆT': ['ontv', 'ontviet', 'ontiengviet'],
    'L.TOÁN': ['ltoan', 'luyentoan'],
    'L.T.VIỆT': ['ltv', 'ltviet', 'luyentv', 'luyentviet', 'luyentiengviet'],
    'R.TOÁN': ['rtoan', 'rentoan'],
    'R.T.VIỆT': ['rtv', 'rtviet', 'rentv', 'rentiengviet'],
    'HĐTN': ['hdtn', 'hoatdongtrainghiem', 'hdtrainghiem', 'trainghiem', 'hdtn1', 'hdtn2', 'hdtn3'],
    'GDĐP': ['gddp', 'giaoducdiaphuong'],
    'ÂM NHẠC': ['amnhac', 'an', 'nhac'],
    'TN & XH': ['tnxh', 'tnvaxh', 'tunhienxahoi', 'tunhienvaxahoi'],
    'GDTC': ['gdtc', 'theduc', 'td', 'gdthechat', 'giaoducthechat'],
    'ĐẠO ĐỨC': ['daoduc', 'dd'],
    'MĨ THUẬT': ['mithuat', 'mythuat', 'mt'],
    'KN SỐNG': ['kns', 'knsong', 'kynangsong', 'kinangsong'],
    'KĨ NĂNG': ['kinang', 'kynang'],
    'LS & ĐL': ['lsdl', 'lichsudiali', 'lichsudialy', 'lichsuvadiali', 'lichsuvadialy', 'lsvadl'],
    'LỊCH SỬ': ['lichsu'],
    'ĐỊA LÝ': ['diali', 'dialy'],
    'TIN HỌC': ['tinhoc', 'tin'],
    'KHOA HỌC': ['khoahoc'],
    'KHTN': ['khtn', 'khoahoctunhien'],
    'CÔNG NGHỆ': ['congnghe'],
    'CHÀO CỜ': ['chaoco'],
    'SHDC': ['shdc', 'sinhhoatduoico'],
    'HĐCC': ['hdcc', 'hoatdongchaoco'],
    'SH LỚP': ['shl', 'shlop', 'sinhhoatlop', 'sinhhoat'],
    'STEM': ['stem'],
    'ÔN TẬP': ['ontap'],
    'ĐỌC SÁCH': ['docsach', 'docsachtv'],
    'BTKT': ['btkt', 'botrokienthuc'],
    'TALK': ['talk', 'tienganhlienket'],
    'GDCD': ['gdcd', 'giaoduccongdan'],
    'NGỮ VĂN': ['nguvan', 'van'],
    'VẬT LÍ': ['vatli', 'vatly'],
    'HÓA HỌC': ['hoahoc'],
    'SINH HỌC': ['sinhhoc'],
    'TỰ HỌC': ['tuhoc'],
    'TRUY BÀI': ['truybai'],
    'RÈN LUYỆN': ['renluyen'],
    'CLB': ['clb', 'caulacbo'],
    'THQT': ['thqt'],
    'TOÁN HÌNH': ['toanhinh', 'hinhhoc'],
    'TOÁN ĐẠI': ['toandai', 'daiso'],
    'TOÁN TA': ['toanta', 'toantienganh'],
    'KHTA': ['khta', 'khoahoctienganh'],
    'TABN': ['tabn', 'tienganhbanngu'],
    'TA D&C': ['tadc'],
    'AEROBIC': ['aerobic'],
    'CỜ': ['co', 'covua'],
    'CDS': ['cds', 'congdanso'],
    'TTNT': ['ttnt', 'trituenhantao'],
    'AI': ['ai'],
    'TRÔNG GIỮ': ['tronggiu'],
}
KEY2MON = {k: m for m, ks in ALIAS.items() for k in ks}
# Thẻ có trong thư viện mà bảng trên chưa có -> khớp đúng tên thẻ
try:
    for _t in json.loads(os.environ.get('DOCMON_THE', '[]') or '[]'):
        _g = re.sub(r'[^a-z0-9]', '', ''.join(c for c in unicodedata.normalize('NFD', str(_t).replace('Đ', 'D').replace('đ', 'd'))
                                               if unicodedata.category(c) != 'Mn').lower())
        if len(_g) >= 2 and _g not in KEY2MON:
            KEY2MON[_g] = str(_t)
except Exception:
    pass
# Ô tiêu đề / không phải tiết học
STOP = re.compile(r'^(tieng|viet|hoc|khoa|thu[2-8]|thu(hai|ba|tu|nam|sau|bay)|chunhat|sang|chieu|buoi.*|tiet\d*|thoigian|rachoi|nghi.*|giailao|'
                  r'antrua|ngutrua|lop.*|thoikhoabieu.*|tkb.*|sheet\d*|[a-h]|\d+|tuan.*|ngay.*|gvcn.*|monhoc|mon)$')
CHUA_LA_TIEU_DE = ('tacdung', 'apdung', 'tungay', 'hocsinh', 'giaovien', 'gvcn', 'sdt', 'namhoc', 'thoikhoabieu')


def gon(s):
    """Bỏ dấu, thường hoá, chỉ giữ a-z0-9 (T.A -> ta, Đ Đ -> dd, Lịch sử - Địa lí -> lichsudiali)."""
    s = unicodedata.normalize('NFD', s.replace('Đ', 'D').replace('đ', 'd'))
    s = ''.join(c for c in s if unicodedata.category(c) != 'Mn').lower()
    return re.sub(r'[^a-z0-9]', '', s)


def mo(g):
    """Sai chính tả OCR nhẹ: so mờ, chặt (chỉ chuỗi >= 5 ký tự, độ dài gần bằng)."""
    if len(g) < 5:
        return None
    best, br = None, 0.0
    for k, m in KEY2MON.items():
        if len(k) < 5 or abs(len(k) - len(g)) > max(1, len(g) // 5):
            continue
        r = difflib.SequenceMatcher(None, g, k).ratio()
        if r > br:
            best, br = m, r
    return best if br >= 0.86 else None


def khop(text):
    """Chữ trong 1 ô -> (tên môn, đã_biết?) hoặc None nếu là tiêu đề/rác."""
    t = re.sub(r'\s+', ' ', text).strip(' -–—|:;.,_')
    if not t or any(w in gon(t) for w in CHUA_LA_TIEU_DE):   # dòng ghi chú "có tác dụng từ ngày…", "GVCN…"
        return None
    t = re.sub(r'\s*[(\[{].*?([)\]}]|$)', ' ', t)          # "Toán (Hiếu)" -> "Toán" (tên GV trong ngoặc)
    t = re.split(r'\s*:\s*', t)[0]                          # "Tiếng Việt: Đọc" -> "Tiếng Việt" (phân môn)
    t = re.sub(r'\s+', ' ', t).strip(' -–—|:;.,_')
    if not t:
        return None
    cands = [t]
    if re.search(r'\s[-–—]\s|[-–—]', t):            # "TNXH - MIÊN" -> "TNXH" (tên GV phía sau)
        cands.append(re.split(r'\s*[-–—]\s*', t)[0])
    g = gon(t)
    if g in KEY2MON:
        return KEY2MON[g], True
    m = mo(g)                                         # so mờ cả chuỗi trước khi tách theo '-'
    if m:                                             # (OCR "Lịch sử - Địa tí" phải ra LS & ĐL, không phải LỊCH SỬ)
        return m, True
    for c in cands[1:]:
        g = gon(c)
        if g in KEY2MON:
            return KEY2MON[g], True
    tu = t.split(' ')
    for n in (3, 2, 1):                               # "TNXH MIÊN", "Đ Đ NGỌC", "T.A D.HOA"
        if len(tu) <= n:
            continue
        g, du = gon(' '.join(tu[:n])), gon(' '.join(tu[n:]))
        if g in KEY2MON and du not in KEY2MON and not any(len(k) >= 4 and k in du for k in KEY2MON):
            return KEY2MON[g], True
    for c in cands[1:]:
        m = mo(gon(c))
        if m:
            return m, True
    g = gon(cands[-1])
    letters = len(re.findall(r'[A-Za-zÀ-ỹĐđ]', cands[-1]))
    if STOP.match(g) or STOP.match(gon(t)) or letters < 2 or len(g) > 30 or re.search(r'\d', g)             or any(g in w for w in ('sang', 'chieu', 'buoi', 'thoigian', 'tiet', 'thoikhoabieu')):
        return None
    if not re.fullmatch(r"[A-Za-zÀ-ỹĐđ&+ .'-]+", cands[0]):   # rác OCR có ký tự lạ
        return None
    return cands[0], False


def duong_ke(gray):
    """Mặt nạ (ngang, dọc) các ĐƯỜNG KẺ bảng = nét tối hơn nền xung quanh, MẢNH và THẲNG DÀI.
    - Ngưỡng thích nghi (so với nền lân cận) nên bắt cả lưới xám nhạt của Excel lẫn ảnh chụp tối/không đều sáng.
    - Mở hình thái theo vệt dài: nét chữ (kể cả chữ đậm Times) và gạch đỏ lượn sóng chính tả không có đoạn thẳng
      dài cỡ 1/14 bề ngang -> không thành tường. (Bản cũ dùng cạnh Canny: viền chữ đậm thành tường cắt mất chữ.)"""
    H, W = gray.shape
    bw = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY_INV, 31, 10)
    k = cv2.getStructuringElement
    LH, LV = max(60, W // 14), max(40, H // 22)
    hm = cv2.morphologyEx(bw, cv2.MORPH_OPEN, k(cv2.MORPH_RECT, (LH, 1)))
    vm = cv2.morphologyEx(bw, cv2.MORPH_OPEN, k(cv2.MORPH_RECT, (1, LV)))
    # Kẻ CHẤM CHẤM (Word hay dùng giữa các tiết): mở theo vệt liền làm mất nó. Chỉ lấy các CHẤM/GẠCH NGẮN rời
    # (thành phần liên thông nhỏ), nối chấm theo chiều nét rồi mới mở. Nét chữ là thành phần lớn nên không bị nối:
    # nếu nối thẳng trên ảnh nhị phân thì chữ "T" thẳng hàng ở 3 hàng liền nhau thành một vạch dọc chẻ đôi ô.
    n, lab, st, _ = cv2.connectedComponentsWithStats(bw, connectivity=8)
    D, g = max(6, W // 200), max(7, W // 230)
    cw, ch = st[:, cv2.CC_STAT_WIDTH], st[:, cv2.CC_STAT_HEIGHT]
    ngang = ((ch <= D) & (cw <= 4 * D)); ngang[0] = False
    doc_ = ((cw <= D) & (ch <= 4 * D)); doc_[0] = False
    hc = cv2.morphologyEx(cv2.morphologyEx(ngang[lab].astype(np.uint8) * 255, cv2.MORPH_CLOSE, k(cv2.MORPH_RECT, (g, 1))),
                          cv2.MORPH_OPEN, k(cv2.MORPH_RECT, (max(40, W // 25), 1)))   # khúc chấm bị hình trang trí cắt ngắn
    vc = cv2.morphologyEx(cv2.morphologyEx(doc_[lab].astype(np.uint8) * 255, cv2.MORPH_CLOSE, k(cv2.MORPH_RECT, (1, g))),
                          cv2.MORPH_OPEN, k(cv2.MORPH_RECT, (1, LV)))
    # Nối các đoạn chấm THẲNG HÀNG qua khe hở (hình trang trí đè ngang làm đứt nét chấm -> hai ô dính nhau),
    # rồi kéo dài hai đầu: chấm cuối dòng thường dính vào chữ/đường kẻ dọc nên nét chấm hụt vài điểm ảnh.
    hc = cv2.dilate(cv2.morphologyEx(hc, cv2.MORPH_CLOSE, k(cv2.MORPH_RECT, (LH, 1))), k(cv2.MORPH_RECT, (4 * g, 1)))
    vc = cv2.dilate(cv2.morphologyEx(vc, cv2.MORPH_CLOSE, k(cv2.MORPH_RECT, (1, LV))), k(cv2.MORPH_RECT, (1, 4 * g)))
    return hm | hc, vm | vc


def tim_luoi(gray):
    """Trả về (ys, xs) vị trí đường kẻ ngang/dọc của BẢNG lớn nhất."""
    H, W = gray.shape
    hm, vm = duong_ke(gray)
    grid = cv2.dilate(cv2.bitwise_or(hm, vm), np.ones((3, 3), np.uint8))
    cnts, _ = cv2.findContours(grid, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    if not cnts:
        return None
    x, y, w, h = cv2.boundingRect(max(cnts, key=lambda c: cv2.boundingRect(c)[2] * cv2.boundingRect(c)[3]))
    if w < W * 0.3 or h < H * 0.15:
        return None

    def vach(mask, axis, nguong):
        s = mask[y:y + h, x:x + w].sum(axis=axis) / 255.0
        idx = np.where(s >= nguong)[0]
        out, grp = [], []
        for i in idx:
            if grp and i - grp[-1] > 3:
                out.append(int(np.mean(grp))); grp = []
            grp.append(i)
        if grp:
            out.append(int(np.mean(grp)))
        return out

    ys = [v + y for v in vach(hm, 1, w * 0.45)]
    xs = [v + x for v in vach(vm, 0, h * 0.45)]
    if y not in ys: ys = [y] + ys
    if y + h - 1 not in ys: ys.append(y + h - 1)
    if x not in xs: xs = [x] + xs
    if x + w - 1 not in xs: xs.append(x + w - 1)
    ys, xs = sorted(set(ys)), sorted(set(xs))
    # gộp vạch quá sát (đường kẻ đôi)
    def gop(v, d):
        r = []
        for a in v:
            if r and a - r[-1] < d: continue
            r.append(a)
        return r
    return gop(ys, 12), gop(xs, 12)


def chuan_o(crop):
    """Chuẩn hoá 1 ô cho OCR: nền tối -> đảo màu; nền màu -> kéo về trắng. Ô trống -> None."""
    if crop.size == 0:
        return None
    if float(np.median(crop)) < 120:                      # nền tối (xanh đậm…) chữ sáng -> đảo màu
        crop = 255 - crop
    if crop.min() > 170:                                  # ô trống
        return None
    # Ô nền màu (xanh lơ, xanh lá… xám ~175): kéo nền về trắng theo mức nền CỦA CHÍNH Ô,
    # nếu không Tesseract nhị phân hoá cả ô thành mảng đen và mất chữ.
    bg = float(np.median(crop))
    if bg < 245:
        crop = np.clip(crop.astype(np.float32) * (255.0 / max(bg, 1.0)), 0, 255).astype(np.uint8)
    return crop


def goc_nghieng(gray):
    """Ảnh chụp nghiêng: đo góc các đường kẻ ngang dài (Hough)."""
    H, W = gray.shape
    bw = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY_INV, 31, 12)
    ls = cv2.HoughLinesP(bw, 1, np.pi / 1800, threshold=150, minLineLength=W // 5, maxLineGap=10)
    if ls is None:
        return 0.0
    # reshape: OpenCV 4.x trả (N,1,4), 5.x trả (N,4)
    ang = [float(np.degrees(np.arctan2(y2 - y1, x2 - x1))) for x1, y1, x2, y2 in np.asarray(ls).reshape(-1, 4)]
    ang = [a for a in ang if abs(a) < 15]
    return float(np.median(ang)) if len(ang) >= 3 else 0.0


def nan_thang(gray, a=None):
    """Xoay cho thẳng theo góc đo được (nới khung để không cắt góc). Trả (ảnh, góc)."""
    if a is None:
        a = goc_nghieng(gray)
    if abs(a) < 0.25:
        return gray, a
    H, W = gray.shape
    M = cv2.getRotationMatrix2D((W / 2.0, H / 2.0), a, 1.0)
    cs, sn = abs(M[0, 0]), abs(M[0, 1])
    nW, nH = int(H * sn + W * cs), int(H * cs + W * sn)
    M[0, 2] += nW / 2.0 - W / 2.0
    M[1, 2] += nH / 2.0 - H / 2.0
    return cv2.warpAffine(gray, M, (nW, nH), flags=cv2.INTER_CUBIC, borderValue=255), a


def tim_o(gray, phu=None):
    """Ô = vùng kín bao bởi ĐƯỜNG KẺ (không phải bởi chữ). Chịu được nghiêng nhẹ/méo phối cảnh, ô gộp giữ nguyên.
    Trả [(khoá, ô, ô_phụ)]: ô_phụ = cùng ô cắt trên ảnh phụ `phu` (None nếu không có)."""
    H, W = gray.shape
    hl, vl = duong_ke(gray)
    tuong = cv2.dilate(cv2.bitwise_or(hl, vl), np.ones((5, 5), np.uint8))   # nối khe hở nhỏ của nét kẻ
    n, lab, st, _ = cv2.connectedComponentsWithStats(cv2.bitwise_not(tuong), connectivity=4)
    out = []
    for i in range(1, n):
        x, y, w, h, a = st[i]
        if w < 35 or h < 16 or a > H * W * 0.25:
            continue
        if w > 0.92 * W and h > 0.92 * H:                 # nền ngoài bảng
            continue
        m = (lab[y:y + h, x:x + w] == i).astype(np.uint8) * 255
        # LẤP LỖ: nét chữ có thể bị tính là "tường" nằm lọt trong ô -> lấy viền NGOÀI của vùng rồi tô kín,
        # chỉ xoá phần ngoài viền (nét kẻ, ô bên cạnh) chứ không xoá chữ.
        cnts, _ = cv2.findContours(m, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        m = np.zeros_like(m)
        cv2.drawContours(m, cnts, -1, 255, -1)
        dien_tich = int((m > 0).sum())
        if dien_tich < max(900, H * W * 0.0003) or dien_tich / float(w * h) < 0.75:   # không phải ô chữ nhật
            continue
        m = cv2.erode(m, np.ones((3, 3), np.uint8))
        cs = []
        for anh in (gray, phu):
            if anh is None:
                cs.append(None); continue
            crop = anh[y:y + h, x:x + w].copy()
            inside = crop[m > 0]
            if inside.size == 0:
                cs.append(None); continue
            crop[m == 0] = int(np.median(inside))         # xoá nét kẻ/ô bên cạnh lọt vào khung
            cs.append(chuan_o(crop))
        if cs[0] is not None:
            out.append(((int(y) // 10, int(x) // 10), cs[0], cs[1]))
    out.sort(key=lambda t: t[0])
    return out


def cat_o_theo_luoi(gray, phu=None):
    """Cách cũ (dự phòng): cắt theo vị trí đường kẻ ngang/dọc thẳng hàng. Trả [(khoá, ô, ô_phụ)] như tim_o."""
    luoi = tim_luoi(gray)
    if not luoi:
        return []
    ys, xs = luoi
    out = []
    for r in range(len(ys) - 1):
        for c in range(len(xs) - 1):
            y0, y1, x0, x1 = ys[r] + 5, ys[r + 1] - 5, xs[c] + 6, xs[c + 1] - 6
            if y1 - y0 < 18 or x1 - x0 < 30:
                continue
            cc = chuan_o(gray[y0:y1, x0:x1])
            if cc is not None:
                out.append(((r, c), cc, None if phu is None else chuan_o(phu[y0:y1, x0:x1])))
    return out


def phong_o(cr, cao=80):
    """Ô thấp (ảnh nhỏ, chữ ~15 px) -> phóng cho chữ đủ cao để đọc lại lượt hai."""
    if cr.shape[0] >= cao:
        return cr
    f = float(cao) / cr.shape[0]
    return cv2.resize(cr, None, fx=f, fy=f, interpolation=cv2.INTER_CUBIC)


def ocr_luot(crops, psm='6'):
    """OCR mọi ô trong MỘT lượt tesseract (xếp thành 1 ảnh dọc) -> [(chữ chắc, độ tin, mọi chữ)] theo thứ tự ô."""
    pad = 24
    W = max(cr.shape[1] for cr in crops) + 2 * pad
    bands, y = [], pad
    for cr in crops:
        bands.append((y, y + cr.shape[0]))
        y += cr.shape[0] + pad * 2
    canvas = np.full((y + pad, W), 255, np.uint8)
    for (b0, _), cr in zip(bands, crops):
        canvas[b0:b0 + cr.shape[0], pad:pad + cr.shape[1]] = cr
    with tempfile.TemporaryDirectory() as td:
        p = os.path.join(td, 'c.png')
        cv2.imwrite(p, canvas)
        tsv = subprocess.run(['tesseract', p, 'stdout', '-l', 'vie', '--psm', psm, 'tsv'],
                             capture_output=True, text=True, timeout=90).stdout
    words = {}
    for line in tsv.splitlines()[1:]:
        f = line.split('\t')
        if len(f) < 12 or not f[11].strip() or f[10] in ('-1',):
            continue
        cy = int(f[7]) + int(f[9]) / 2.0
        for i, (b0, b1) in enumerate(bands):
            if b0 - pad <= cy <= b1 + pad:
                words.setdefault(i, []).append((int(f[2]), int(f[3]), int(f[4]), int(f[5]), f[11], float(f[10])))
                break
    out = []
    for i in range(len(crops)):
        tat_ca = sorted(words.get(i, []))
        ws = [w for w in tat_ca if w[5] >= 20]                 # chữ đọc chắc
        out.append((' '.join(w[4] for w in ws), (sum(w[5] for w in ws) / len(ws)) if ws else 0.0,
                    ' '.join(w[4] for w in tat_ca)))          # kể cả chữ độ tin thấp (gạch đỏ dưới chữ kéo tụt độ tin)
    return out


def ocr_o(cells):
    """OCR các ô -> đếm môn. Trả kèm _diem = số ô khớp môn CHUẨN (để chọn cách cắt tốt hơn).
    Lượt 1 đọc ảnh gốc của ô. Ô KHÔNG khớp môn nào (ảnh nhỏ; gạch đỏ lượn sóng chính tả nối mọi con chữ thành
    một khối -> Tesseract coi là hình, đọc RỖNG) được đọc lại lượt 2 trên ô phụ (đã xoá gạch đỏ) phóng to;
    chỉ nhận kết quả lượt 2 nếu nó khớp môn chuẩn. Ô đọc tốt ở lượt 1 giữ nguyên (chữ ĐỎ thật vẫn đọc ở lượt 1).
    (Đo trên 9 ảnh thật: tiền xử lý MỌI ô — phóng/nhị phân/giãn mức — đều làm tụt điểm; chỉ đọc lại ô hỏng mới lợi.)"""
    def biet(t):
        return bool((khop(t) or (None, False))[1])

    kq = []
    for t, c, tat_ca in ocr_luot([o[1] for o in cells]):
        kq.append((tat_ca, c) if not biet(t) and biet(tat_ca) else (t, c))   # chữ độ tin thấp chỉ nhận khi ra môn chuẩn
    hong = [i for i, (t, c) in enumerate(kq) if not biet(t)]
    if hong:
        kq2 = ocr_luot([phong_o(cells[i][2] if cells[i][2] is not None else cells[i][1]) for i in hong])
        for i, (t2, c2, tat_ca2) in zip(hong, kq2):
            for t in (t2, tat_ca2):
                if biet(t):
                    kq[i] = (t, c2)
                    break

    dem, ten_hien, khong_ro, diem = {}, {}, [], 0
    for i, (text, conf) in enumerate(kq):
        if DEBUG:
            sys.stderr.write('o %s conf=%d: %r\n' % (cells[i][0], conf, text))
        k = khop(text)
        if not k:
            continue
        mon, biet = k
        if not biet and conf < 75:                    # chữ lạ phải đọc RÕ mới giữ (tránh rác "SnM")
            continue
        if biet:
            diem += 1
        key = mon if biet else gon(mon)
        dem[key] = dem.get(key, 0) + 1
        ten_hien.setdefault(key, mon)
        if not biet and mon not in khong_ro:
            khong_ro.append(mon)
    mon = [{'mon': ten_hien[k], 'so_luong': n} for k, n in sorted(dem.items(), key=lambda kv: -kv[1])]
    return {'mon': mon, 'khong_ro': khong_ro, 'so_o': len(cells), '_diem': diem}


def doc(path):
    img = cv2.imread(path)
    if img is None:
        return {'ok': False, 'error': 'Không mở được ảnh.'}
    if img.shape[1] < 450:                                # chữ chỉ vài điểm ảnh -> đọc ra SAI nhiều hơn đúng
        return {'ok': False, 'error': 'Ảnh quá nhỏ/mờ (%dpx ngang) — vui lòng gửi ảnh rõ hơn (chụp thẳng, đủ sáng).' % img.shape[1]}
    if img.shape[1] < 1600:                               # phóng ảnh nhỏ cho OCR dễ đọc
        f = 1600.0 / img.shape[1]
        img = cv2.resize(img, None, fx=f, fy=f, interpolation=cv2.INTER_CUBIC)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    # Ảnh phụ cho lượt đọc lại: xoá điểm ảnh ĐỎ mà không ĐEN (gạch lượn sóng chính tả: xám 90-190, độ đỏ 30-65;
    # nét chữ đen xám < 50 giữ nguyên dù JPEG làm loang màu đỏ sang chân chữ).
    b_, g_, r_ = cv2.split(img.astype(np.int16))
    phu = gray.copy()
    phu[((r_ - (g_ + b_) // 2) > 25) & (gray > 60)] = 255
    gray, goc = nan_thang(gray)
    phu, _ = nan_thang(phu, goc)

    # Hai cách cắt ô: theo VÙNG KÍN (chịu nghiêng, ô gộp) và theo LƯỚI THẲNG (sắc nét với ảnh chụp màn hình).
    # Chạy cả hai, giữ bản nhận ra nhiều ô khớp môn CHUẨN hơn.
    ket_qua = []
    for ten, ham in (('vung', tim_o), ('luoi', cat_o_theo_luoi)):
        if os.environ.get('DOCMON_CACH') and os.environ['DOCMON_CACH'] != ten:
            continue
        cells = ham(gray, phu)
        if len(cells) >= 4:
            r = ocr_o(cells)
            r['cach'] = ten
            ket_qua.append(r)
    if not ket_qua:
        return {'ok': False, 'error': 'Không thấy bảng kẻ ô trong ảnh (ảnh viết tay hoặc bảng không có đường kẻ?).'}
    tot = max(ket_qua, key=lambda r: (r['_diem'], -len(r['khong_ro'])))
    if tot.pop('_diem', 0) < 3:                           # không đủ môn chuẩn -> không phải TKB (ảnh SP, ảnh lạ…)
        return {'ok': False, 'error': 'Không nhận ra thời khoá biểu trong ảnh — kiểm tra lại ảnh hoặc nhập tay.'}
    tot.update({'ok': bool(tot['mon']), 'goc': round(goc, 2),
                'error': None if tot['mon'] else 'Không nhận ra môn nào trong bảng.'})
    return tot


if __name__ == '__main__':
    try:
        print(json.dumps(doc(sys.argv[1]), ensure_ascii=False))
    except Exception as e:  # không để PHP nhận rác
        print(json.dumps({'ok': False, 'error': 'Lỗi đọc ảnh: ' + str(e)[:200]}, ensure_ascii=False))
