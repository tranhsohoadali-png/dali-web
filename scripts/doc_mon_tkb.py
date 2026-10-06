#!/usr/bin/env python3
"""
Đọc ảnh THỜI KHOÁ BIỂU bằng MÁY (không AI) -> đếm số tiết mỗi môn.

  python3 doc_mon_tkb.py <anh.jpg|png|webp>   -> in JSON {"ok":..., "mon":[{"mon","so_luong"}], ...}

Cách làm:
  1. Dò lưới bảng (đường kẻ ngang/dọc đậm) bằng OpenCV, cắt từng Ô.
     Chỉ đọc chữ TRONG ô -> chú thích/tiêu đề ngoài bảng không bị đếm.
  2. Ô nền tối (VD TNXH nền xanh chữ trắng) -> đảo màu cho dễ đọc.
  3. Xếp mọi ô thành 1 ảnh dọc, OCR MỘT lượt (tesseract -l vie), gán chữ về ô theo toạ độ.
  4. Khớp tên môn qua bảng viết tắt (T.A, L.TV, MT, ĐĐ, ÂN, SHL, TNXH…), bỏ phần tên giáo viên sau "-".
     Ô không khớp nhưng trông như tên môn (VD "Đọc sách", "AI") vẫn giữ nguyên chữ để xưởng xem.
"""
import json, re, subprocess, sys, tempfile, os, unicodedata, difflib

import cv2
DEBUG = bool(os.environ.get('DOCMON_DEBUG'))
import numpy as np

# Tên chuẩn = đúng danh sách MÔN của xưởng (cau_hinh_3d.MON) để bảng soạn TKB tô đúng màu
ALIAS = {
    'Tiếng Việt': ['tiengviet', 'tv', 'tiengvet', 'tiengvlet'],
    'Toán': ['toan'],
    'Tiếng Anh': ['tienganh', 'ta', 'anhvan', 'english', 'anh'],
    'Ôn Toán': ['ontoan', 'ltoan', 'luyentoan', 'ltoan'],
    'Ôn TV': ['ontv', 'ontiengviet', 'ltv', 'luyentiengviet', 'luyentv', 'ontviet'],
    'HĐTN': ['hdtn', 'hoatdongtrainghiem', 'hdtrainghiem', 'trainghiem'],
    'HĐTN 1': ['hdtn1'], 'HĐTN 2': ['hdtn2'], 'HĐTN 3': ['hdtn3'],
    'GDĐP': ['gddp', 'giaoducdiaphuong'],
    'Âm Nhạc': ['amnhac', 'an', 'nhac'],
    'TN & XH': ['tnxh', 'tnvaxh', 'tunhienxahoi', 'tunhienvaxahoi'],
    'GDTC': ['gdtc', 'theduc', 'giaoducthechat', 'td'],
    'Đạo Đức': ['daoduc', 'dd'],
    'Mĩ Thuật': ['mithuat', 'mythuat', 'mt'],
    'KN Sống': ['kns', 'knsong', 'kynangsong', 'kinangsong'],
    'LS & ĐL': ['lsdl', 'lichsudiali', 'lichsudialy', 'lichsuvadiali', 'lichsuvadialy', 'lsvadl'],
    'Tin Học': ['tinhoc', 'tin'],
    'Khoa Học': ['khoahoc'],
    'Công Nghệ': ['congnghe'],
    'Chào Cờ': ['chaoco'],
    'SH Lớp': ['shl', 'shlop', 'sinhhoatlop', 'sinhhoat'],
    # ngoài danh sách thẻ chuẩn nhưng hay gặp
    'STEM': ['stem'],
    'Ôn tập': ['ontap'],
    'Đọc sách': ['docsach'],
    'Thư viện': ['thuvien'],
}
KEY2MON = {k: m for m, ks in ALIAS.items() for k in ks}
# Ô tiêu đề / không phải môn
STOP = re.compile(r'^(tieng|viet|hoc|khoa|thu[2-8]|thu(hai|ba|tu|nam|sau|bay)|chunhat|sang|chieu|buoi|tiet\d*|thoigian|rachoi|nghi|giailao|'
                  r'lop.*|thoikhoabieu.*|tkb.*|sheet\d*|[a-h]|\d+|tuan.*|ngay.*|gvcn.*|monhoc|mon)$')


def gon(s):
    """Bỏ dấu, thường hoá, chỉ giữ a-z0-9 (T.A -> ta, Đ Đ -> dd, Lịch sử - Địa lí -> lichsudiali)."""
    s = unicodedata.normalize('NFD', s.replace('Đ', 'D').replace('đ', 'd'))
    s = ''.join(c for c in s if unicodedata.category(c) != 'Mn').lower()
    return re.sub(r'[^a-z0-9]', '', s)


def khop(text):
    """Chữ trong 1 ô -> (tên môn, đã_biết?) hoặc None nếu là tiêu đề/rác."""
    t = re.sub(r'\s+', ' ', text).strip(' -–—|:;.,_')
    if not t:
        return None
    cands = [t]
    if re.search(r'\s[-–—]\s|[-–—]', t):            # "TNXH - MIÊN" -> "TNXH" (tên GV phía sau)
        cands.append(re.split(r'\s*[-–—]\s*', t)[0])
    for c in cands:
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
    for c in cands:                                   # sai chính tả OCR nhẹ: so mờ, chặt
        g = gon(c)
        if len(g) < 5:
            continue
        best, br = None, 0.0
        for k, m in KEY2MON.items():
            if len(k) < 5 or abs(len(k) - len(g)) > max(1, len(g) // 5):
                continue
            r = difflib.SequenceMatcher(None, g, k).ratio()
            if r > br:
                best, br = m, r
        if best and br >= 0.86:
            return best, True
    g = gon(cands[-1])
    letters = len(re.findall(r'[A-Za-zÀ-ỹĐđ]', cands[-1]))
    if STOP.match(g) or STOP.match(gon(t)) or letters < 2 or len(g) > 30 or re.search(r'\d', g)             or any(g in w for w in ('sang', 'chieu', 'buoi', 'thoigian', 'tiet', 'thoikhoabieu')):
        return None
    if not re.fullmatch(r"[A-Za-zÀ-ỹĐđ&+ .'-]+", cands[0]):   # rác OCR có ký tự lạ
        return None
    return cands[0], False


def tim_luoi(gray):
    """Trả về (ys, xs) vị trí đường kẻ ngang/dọc của BẢNG lớn nhất."""
    H, W = gray.shape
    bw = cv2.threshold(gray, 150, 255, cv2.THRESH_BINARY_INV)[1]     # chỉ nét đậm (bỏ lưới xám nhạt của Excel)
    hk = cv2.getStructuringElement(cv2.MORPH_RECT, (max(40, W // 25), 1))
    vk = cv2.getStructuringElement(cv2.MORPH_RECT, (1, max(30, H // 30)))
    hm = cv2.morphologyEx(bw, cv2.MORPH_OPEN, hk)
    vm = cv2.morphologyEx(bw, cv2.MORPH_OPEN, vk)
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


def nan_thang(gray):
    """Ảnh chụp nghiêng: đo góc các đường kẻ ngang dài (Hough) rồi xoay cho thẳng (nới khung để không cắt góc)."""
    H, W = gray.shape
    bw = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY_INV, 31, 12)
    ls = cv2.HoughLinesP(bw, 1, np.pi / 1800, threshold=150, minLineLength=W // 5, maxLineGap=10)
    if ls is None:
        return gray, 0.0
    ang = [float(np.degrees(np.arctan2(y2 - y1, x2 - x1))) for x1, y1, x2, y2 in ls[:, 0]]
    ang = [a for a in ang if abs(a) < 15]
    if len(ang) < 3:
        return gray, 0.0
    a = float(np.median(ang))
    if abs(a) < 0.25:
        return gray, a
    M = cv2.getRotationMatrix2D((W / 2.0, H / 2.0), a, 1.0)
    cs, sn = abs(M[0, 0]), abs(M[0, 1])
    nW, nH = int(H * sn + W * cs), int(H * cs + W * sn)
    M[0, 2] += nW / 2.0 - W / 2.0
    M[1, 2] += nH / 2.0 - H / 2.0
    return cv2.warpAffine(gray, M, (nW, nH), flags=cv2.INTER_CUBIC, borderValue=255), a


def tim_o(gray):
    """Ô = vùng kín bao bởi ĐƯỜNG KẺ (không phải bởi chữ). Chịu được nghiêng nhẹ/méo phối cảnh, ô gộp giữ nguyên."""
    H, W = gray.shape
    # Tường = CẠNH DÀI thẳng (Canny): bắt cả nét kẻ lẫn ranh giới ô tô màu, còn nền xanh đặc
    # bên trong ô không thành tường (ngưỡng sáng/tối thì nền xanh đậm thành một mảng tường).
    e = cv2.dilate(cv2.Canny(gray, 40, 120), np.ones((3, 3), np.uint8))
    hl = cv2.morphologyEx(e, cv2.MORPH_OPEN, cv2.getStructuringElement(cv2.MORPH_RECT, (max(30, W // 50), 1)))
    vl = cv2.morphologyEx(e, cv2.MORPH_OPEN, cv2.getStructuringElement(cv2.MORPH_RECT, (1, max(40, H // 35))))
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
        crop = gray[y:y + h, x:x + w].copy()
        inside = crop[m > 0]
        if inside.size == 0:
            continue
        crop[m == 0] = int(np.median(inside))             # xoá nét kẻ/ô bên cạnh lọt vào khung
        c = chuan_o(crop)
        if c is not None:
            out.append(((int(y) // 10, int(x) // 10), c))
    out.sort(key=lambda t: t[0])
    return out


def cat_o_theo_luoi(gray):
    """Cách cũ (dự phòng): cắt theo vị trí đường kẻ ngang/dọc thẳng hàng."""
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
                out.append(((r, c), cc))
    return out


def ocr_o(cells):
    """OCR mọi ô trong MỘT lượt tesseract -> đếm môn. Trả kèm _diem = số ô khớp môn CHUẨN (để chọn cách cắt tốt hơn)."""
    pad = 24
    W = max(cr.shape[1] for _, cr in cells) + 2 * pad
    bands, y = [], pad
    for _, cr in cells:
        bands.append((y, y + cr.shape[0]))
        y += cr.shape[0] + pad * 2
    canvas = np.full((y + pad, W), 255, np.uint8)
    for (b0, _), (_, cr) in zip(bands, cells):
        canvas[b0:b0 + cr.shape[0], pad:pad + cr.shape[1]] = cr
    with tempfile.TemporaryDirectory() as td:
        p = os.path.join(td, 'c.png')
        cv2.imwrite(p, canvas)
        tsv = subprocess.run(['tesseract', p, 'stdout', '-l', 'vie', '--psm', '6', 'tsv'],
                             capture_output=True, text=True, timeout=90).stdout
    words = {}
    for line in tsv.splitlines()[1:]:
        f = line.split('\t')
        if len(f) < 12 or not f[11].strip() or f[10] in ('-1',) or float(f[10]) < 20:
            continue
        cy = int(f[7]) + int(f[9]) / 2.0
        for i, (b0, b1) in enumerate(bands):
            if b0 - pad <= cy <= b1 + pad:
                words.setdefault(i, []).append((int(f[2]), int(f[3]), int(f[4]), int(f[5]), f[11], float(f[10])))
                break

    dem, ten_hien, khong_ro, diem = {}, {}, [], 0
    for i in range(len(cells)):
        ws = sorted(words.get(i, []))
        text = ' '.join(w[4] for w in ws)
        conf = (sum(w[5] for w in ws) / len(ws)) if ws else 0.0
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
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    if gray.shape[1] < 450:                               # chữ chỉ vài điểm ảnh -> đọc ra SAI nhiều hơn đúng
        return {'ok': False, 'error': 'Ảnh quá nhỏ/mờ (%dpx ngang) — vui lòng gửi ảnh rõ hơn (chụp thẳng, đủ sáng).' % gray.shape[1]}
    if gray.shape[1] < 1600:                              # phóng ảnh nhỏ cho OCR dễ đọc
        f = 1600.0 / gray.shape[1]
        gray = cv2.resize(gray, None, fx=f, fy=f, interpolation=cv2.INTER_CUBIC)
    gray, goc = nan_thang(gray)

    # Hai cách cắt ô: theo VÙNG KÍN (chịu nghiêng, ô gộp) và theo LƯỚI THẲNG (sắc nét với ảnh chụp màn hình).
    # Chạy cả hai, giữ bản nhận ra nhiều ô khớp môn CHUẨN hơn.
    ket_qua = []
    for ten, ham in (('vung', tim_o), ('luoi', cat_o_theo_luoi)):
        if os.environ.get('DOCMON_CACH') and os.environ['DOCMON_CACH'] != ten:
            continue
        cells = ham(gray)
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
