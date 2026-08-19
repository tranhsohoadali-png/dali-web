<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đơn {{ $don->ma }} | DALI Admin</title>
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
.tot{display:flex;justify-content:space-between;padding:5px 0;font-size:13px}
.tot.big{font-size:16px;font-weight:900;color:var(--g);border-top:1.5px solid var(--bd);margin-top:6px;padding-top:10px}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:800;background:#FEF3C7;color:#B45309}
select,.btn{font-family:'Be Vietnam Pro',sans-serif}
select{border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;background:var(--gll);width:100%}
.btn{padding:10px 16px;border:none;border-radius:9px;font-size:13px;font-weight:800;cursor:pointer}
.btn-g{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff}
.btn-o{background:#FEF3C7;color:#B45309;border:1px solid #FCD34D}
.btn-d{background:#FFF0F0;color:#EF4444;border:1px solid #FECACA}
.g2{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-top:10px}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › Đơn › <b>{{ $don->ma }}</b></div><div class="tb-title">Đơn {{ $don->ma }}</div></div>
    <a href="{{ route('admin.don3d.index') }}" class="back">← Tất cả đơn 3D</a>
  </div>
  <div class="cnt">
    @if(session('ok'))<div class="alert-ok">✅ {{ session('ok') }}</div>@endif
    <div class="wrap">
      <div>
        <div class="sec">
          <h2>🧾 Sản phẩm</h2>
          @foreach(($don->chi_tiet ?: []) as $l)
          <div class="line">
            <div style="display:flex;justify-content:space-between">
              <div class="t">{{ $l['ten'] ?? '' }} <span style="color:var(--tx3)">×{{ $l['qty'] ?? 1 }}</span></div>
              <div class="t">{{ number_format((int)($l['thanh_tien'] ?? 0),0,',','.') }}đ</div>
            </div>
            @if(!empty($l['bien_the']))<div class="s">Phân loại: {{ $l['bien_the'] }}</div>@endif
            @if(!empty($l['khac_ten']))<div class="s">✍️ Khắc tên: <b>{{ $l['khac_ten'] }}</b></div>@endif
            @if(!empty($l['mon_hoc']))<div class="s">Môn: {{ $l['mon_hoc'] }}</div>@endif
            @if(!empty($l['noi_dung']))<div class="s">Nội dung phiếu: {{ $l['noi_dung'] }}</div>@endif
          </div>
          @endforeach
          <div style="margin-top:12px">
            <div class="tot"><span>Tạm tính</span><span>{{ number_format((int)$don->tong - (int)$don->phi_ship + (int)$don->giam,0,',','.') }}đ</span></div>
            @if($don->giam>0)<div class="tot"><span>Giảm giá</span><span>−{{ number_format((int)$don->giam,0,',','.') }}đ</span></div>@endif
            <div class="tot"><span>Phí giao</span><span>{{ $don->phi_ship>0 ? number_format((int)$don->phi_ship,0,',','.').'đ' : 'Miễn phí' }}</span></div>
            <div class="tot big"><span>Tổng cộng</span><span>{{ number_format((int)$don->tong,0,',','.') }}đ</span></div>
            @if($don->tra_ngay>0)
            <div class="tot" style="color:#B45309;font-weight:700"><span>🔵 Phải trả ngay</span><span>{{ number_format((int)$don->tra_ngay,0,',','.') }}đ</span></div>
            <div class="tot" style="color:var(--tx2)"><span>🟢 Còn lại khi nhận</span><span>{{ number_format((int)$don->con_lai,0,',','.') }}đ</span></div>
            @endif
          </div>
        </div>
      </div>

      <div>
        <div class="sec">
          <h2>👤 Người nhận</h2>
          <div class="row"><span>Tên</span><b>{{ $don->ten }}</b></div>
          <div class="row"><span>SĐT</span><b>{{ $don->sdt }}</b></div>
          @if($don->email)<div class="row"><span>Email</span><b>{{ $don->email }}</b></div>@endif
          <div class="row"><span>Tỉnh/TP</span><b>{{ $don->tinh ?: '—' }}</b></div>
          <div class="row" style="border:0"><span>Địa chỉ</span><b style="text-align:right;max-width:60%">{{ $don->dia_chi }}</b></div>
          @if($don->ghi_chu)<div style="margin-top:8px;font-size:12.5px;background:var(--gll);border-radius:9px;padding:9px 12px">📝 {{ $don->ghi_chu }}</div>@endif
        </div>

        <div class="sec">
          <h2>⚙️ Xử lý</h2>
          <div class="row"><span>Thanh toán</span><b>{{ $don->phuong_thuc_tt=='qr'?'Chuyển khoản QR':'COD' }} · {{ $don->trang_thai_tt }}</b></div>
          <div class="row"><span>Trạng thái</span><span class="badge">{{ $tt[$don->tt] ?? $don->tt }}</span></div>
          <form method="POST" action="{{ route('admin.don3d.status', $don) }}" class="g2" style="margin-top:12px">
            @csrf @method('PUT')
            <select name="tt" style="flex:1">
              @foreach($tt as $k=>$v)<option value="{{ $k }}" {{ $don->tt==$k?'selected':'' }}>{{ $v }}</option>@endforeach
            </select>
            <button class="btn btn-g" type="submit">Cập nhật</button>
          </form>
          @if($don->tra_ngay>0 && $don->trang_thai_tt!='da_nhan_coc')
          <form method="POST" action="{{ route('admin.don3d.paid', $don) }}" style="margin-top:8px">
            @csrf
            <button class="btn btn-o" type="submit" style="width:100%">✅ Xác nhận đã nhận cọc {{ number_format((int)$don->tra_ngay,0,',','.') }}đ</button>
          </form>
          @endif
          <form method="POST" action="{{ route('admin.don3d.destroy', $don) }}" style="margin-top:8px" onsubmit="return confirm('Xoá đơn {{ $don->ma }}? Không lấy lại được.')">
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
