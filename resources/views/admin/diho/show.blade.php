<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đơn đi hộ {{ $don->ma }} | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.back{font-size:12px;color:var(--tx2);text-decoration:none;font-weight:700}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.wrap{max-width:940px;margin:0 auto;display:grid;grid-template-columns:1.4fr 1fr;gap:18px}
@media(max-width:820px){.wrap{grid-template-columns:1fr}}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;color:var(--gd)}
.sec{background:#fff;border-radius:16px;border:1.5px solid var(--bd);box-shadow:0 3px 18px rgba(58,122,10,.07);padding:18px 20px;margin-bottom:18px}
.sec h2{font-size:14px;font-weight:900;color:var(--char);margin-bottom:12px}
.row{display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-bottom:1px solid var(--gll)}
.row span{color:var(--tx3)}.row b{font-weight:700}
.line{padding:10px 0;border-bottom:1px solid var(--gll)}
.line .t{font-weight:700;font-size:13px}.line .s{font-size:11.5px;color:var(--tx3)}
.line .s b{color:var(--tx)}
.tot{display:flex;justify-content:space-between;padding:5px 0;font-size:13px}
.tot.big{font-size:16px;font-weight:900;color:var(--g);border-top:1.5px solid var(--bd);margin-top:6px;padding-top:10px}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:800;background:#FEF3C7;color:#B45309}
select,input,.btn{font-family:'Be Vietnam Pro',sans-serif}
select,input[type=text]{border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;background:var(--gll);width:100%}
.btn{padding:10px 16px;border:none;border-radius:9px;font-size:13px;font-weight:800;cursor:pointer}
.btn-g{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff}
.btn-dl{display:inline-flex;align-items:center;gap:6px;padding:11px 16px;background:#EEF2FF;color:#3730A3;border:1px solid #C7D2FE;border-radius:9px;font-size:13px;font-weight:800;text-decoration:none}
.btn-d{background:#FFF0F0;color:#EF4444;border:1px solid #FECACA}
.g2{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-top:10px}
.nhan-img{max-width:100%;border-radius:10px;border:1.5px solid var(--bd);margin-top:10px}
.fld{margin-top:8px}.fld label{display:block;font-size:11px;color:var(--tx3);font-weight:700;margin-bottom:3px}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › Đi hộ › <b>{{ $don->ma }}</b></div><div class="tb-title">Đơn đi hộ {{ $don->ma }}</div></div>
    <a href="{{ route('admin.diho.index') }}" class="back">← Tất cả đơn đi hộ</a>
  </div>
  <div class="cnt">
    @if(session('ok'))<div class="alert-ok">✅ {{ session('ok') }}</div>@endif
    <div class="wrap">
      <div>
        <div class="sec">
          <h2>🧾 Sản phẩm cần in &amp; đi</h2>
          @foreach(($don->chi_tiet ?: []) as $li => $l)
          <div class="line">
            <div style="display:flex;justify-content:space-between">
              <div class="t">{{ $l['ten'] ?? '' }} <span style="color:var(--tx3)">×{{ $l['qty'] ?? 1 }}</span></div>
              <div class="t">{{ number_format((int)($l['thanh_tien'] ?? 0),0,',','.') }}đ</div>
            </div>
            @if(!empty($l['bien_the']))<div class="s">Phân loại: <b>{{ $l['bien_the'] }}</b></div>@endif
            @if(!empty($l['cap_hoc']))<div class="s">🎓 Cấp học: <b>{{ $l['cap_hoc'] }}</b></div>@endif
            @if(!empty($l['ten_in']))<div class="s">✍️ In tên riêng: <b>{{ $l['ten_in'] }}</b></div>@endif
            @if(!empty($l['ghi_chu']))<div class="s">📝 Ghi chú: {{ $l['ghi_chu'] }}</div>@endif
            <div class="s">Đơn giá sỉ: {{ number_format((int)($l['don_gia_si'] ?? 0),0,',','.') }}đ{{ !empty($l['phu_phi_ten']) ? ' · phụ phí in tên +'.number_format((int)$l['phu_phi_ten'],0,',','.').'đ' : '' }}</div>
            @if(!empty($l['anh_ghi_chu']))
            <div class="s" style="margin-top:6px">🖼️ Ảnh ghi chú:
              <a href="{{ route('admin.diho.anhmon', ['don'=>$don,'idx'=>$li]) }}" style="color:#3730A3;font-weight:700">tải</a>
            </div>
            @if(\Illuminate\Support\Str::startsWith((string)($l['anh_ghi_chu_mime'] ?? ''), 'image/'))
              <img src="{{ route('admin.diho.anhmon', ['don'=>$don,'idx'=>$li]) }}" class="nhan-img" alt="Ảnh ghi chú dòng {{ $li+1 }}">
            @endif
            @endif
          </div>
          @endforeach
          <div style="margin-top:12px">
            <div class="tot"><span>Tổng số lượng</span><span>{{ (int)$don->so_luong }}</span></div>
            <div class="tot big"><span>Tổng giá sỉ (tham khảo)</span><span>{{ number_format((int)$don->tong_si,0,',','.') }}đ</span></div>
            <div style="font-size:11px;color:var(--tx3);margin-top:4px">Con số này chỉ để hai bên đối soát — module không thu tiền online.</div>
          </div>
          @if($don->ghi_chu)<div style="margin-top:12px;font-size:12.5px;background:var(--gll);border-radius:9px;padding:9px 12px">📝 Ghi chú chung: {{ $don->ghi_chu }}</div>@endif
        </div>

        <div class="sec">
          <h2>🏷️ Nhãn / hoá đơn vận chuyển</h2>
          @if($don->nhan_vc_path)
            <a href="{{ route('admin.diho.nhan', $don) }}" class="btn-dl">⬇ Tải nhãn ({{ $don->nhan_vc_ten }})</a>
            @if(\Illuminate\Support\Str::startsWith((string)$don->nhan_vc_mime, 'image/'))
              <img src="{{ route('admin.diho.nhan', $don) }}" class="nhan-img" alt="Nhãn vận chuyển">
            @else
              <div style="font-size:12px;color:var(--tx3);margin-top:8px">File PDF — bấm nút trên để tải &amp; in.</div>
            @endif
          @else
            <div style="font-size:12px;color:var(--tx3)">Đơn này chưa có file nhãn.</div>
          @endif
        </div>
      </div>

      <div>
        <div class="sec">
          <h2>🤝 Đại lý</h2>
          <div class="row"><span>Tên</span><b>{{ $don->dai_ly_ten }}</b></div>
          <div class="row" style="border:0"><span>SĐT</span><b>{{ $don->dai_ly_sdt ?: '—' }}</b></div>
        </div>

        <div class="sec">
          <h2>💰 Thanh toán (đối soát)</h2>
          <div class="row"><span>Tổng tiền sỉ</span><b style="color:#3E7A0A">{{ number_format((int)$don->tong_si,0,',','.') }}đ</b></div>
          <div class="row"><span>Trạng thái thu tiền</span>
            @if($don->da_thanh_toan)<span class="badge" style="background:#E8F9D0;color:#3E7A0A">✓ Đã thu{{ $don->thanh_toan_luc ? ' · '.$don->thanh_toan_luc->format('d/m/Y') : '' }}</span>
            @else<span class="badge" style="background:#FEE2E2;color:#B91C1C">Chưa thu</span>@endif
          </div>
          <form method="POST" action="{{ route('admin.diho.tt', $don) }}" style="margin-top:10px">
            @csrf
            <button class="btn {{ $don->da_thanh_toan ? 'btn-o' : 'btn-g' }}" type="submit" style="width:100%">
              {{ $don->da_thanh_toan ? '↩ Bỏ đánh dấu đã thu' : '✓ Đánh dấu ĐÃ thu tiền' }}
            </button>
          </form>
        </div>

        <div class="sec">
          <h2>⚙️ Xử lý</h2>
          <div class="row"><span>Trạng thái</span><span class="badge">{{ $tt[$don->tt] ?? $don->tt }}</span></div>
          <div class="row"><span>ĐL xác nhận VC nhận hàng</span>
            @if($don->dai_ly_xac_nhan)<b style="color:#3E7A0A">✓ Đã xác nhận{{ $don->xac_nhan_luc ? ' · '.$don->xac_nhan_luc->format('d/m/Y') : '' }}</b>
            @else<span style="color:var(--tx3)">Chưa</span>@endif
          </div>
          @if($don->ma_vc)<div class="row"><span>Mã vận đơn</span><b>{{ $don->ma_vc }}</b></div>@endif
          @if($don->vc)<div class="row"><span>Đơn vị VC</span><b>{{ $don->vc }}</b></div>@endif
          @if($don->gui_luc)<div class="row"><span>Gửi lúc</span><b>{{ $don->gui_luc->format('d/m/Y H:i') }}</b></div>@endif
          <form method="POST" action="{{ route('admin.diho.status', $don) }}" style="margin-top:12px">
            @csrf @method('PUT')
            <div class="fld"><label>Trạng thái</label>
              <select name="tt">
                @foreach($tt as $k=>$v)<option value="{{ $k }}" {{ $don->tt==$k?'selected':'' }}>{{ $v }}</option>@endforeach
              </select>
            </div>
            <div class="fld"><label>Mã vận đơn (nếu có)</label><input type="text" name="ma_vc" value="{{ $don->ma_vc }}" placeholder="VD: SPXVN..."></div>
            <div class="fld"><label>Đơn vị vận chuyển</label><input type="text" name="vc" value="{{ $don->vc }}" placeholder="SPX / GHTK / VNPost..."></div>
            <button class="btn btn-g" type="submit" style="margin-top:12px;width:100%">Cập nhật</button>
          </form>
          <form method="POST" action="{{ route('admin.diho.destroy', $don) }}" onsubmit="return confirm('Xoá đơn {{ $don->ma }}? File nhãn cũng bị xoá.')" style="margin-top:10px">
            @csrf @method('DELETE')
            <button class="btn btn-d" type="submit" style="width:100%">🗑️ Xoá đơn</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</body>
</html>
