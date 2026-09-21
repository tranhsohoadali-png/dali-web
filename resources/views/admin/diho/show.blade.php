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
select,input[type=text],input[type=number]{border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;background:var(--gll);width:100%;font-family:'Be Vietnam Pro',sans-serif}
.btn{padding:10px 16px;border:none;border-radius:9px;font-size:13px;font-weight:800;cursor:pointer}
.btn-g{background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff}
.btn-o{background:#FEF3C7;color:#B45309;border:1px solid #FCD34D}
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
    @if(!empty($don->mon_soan))
      <a href="{{ route('admin.diho.soan', $don) }}" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;text-decoration:none;font-weight:800;font-size:14px;padding:11px 18px;border-radius:11px;margin-bottom:16px;box-shadow:0 4px 14px -6px rgba(58,122,10,.6)">📋 Mở bảng soạn TKB ({{ collect($don->mon_soan)->where('xong',true)->count() }}/{{ count($don->mon_soan) }} môn xong) →</a>
    @endif
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
              <a href="{{ route('admin.diho.anhmon', ['don'=>$don,'idx'=>$li]) }}" target="_blank" rel="noopener" style="color:#3730A3;font-weight:700">xem</a>
              <button type="button" class="ai-mon" data-url="{{ route('admin.diho.docmon', ['don'=>$don,'idx'=>$li]) }}" style="margin-left:8px;font-size:11px;font-weight:800;color:#5B21B6;background:#F3E8FF;border:1px solid #D8B4FE;border-radius:20px;padding:3px 10px;cursor:pointer">🤖 Đọc số môn (AI)</button>
            </div>
            <div class="ai-mon-kq" style="display:none;font-size:12.5px;background:#F3E8FF;border:1px solid #D8B4FE;border-radius:9px;padding:9px 12px;margin-top:6px;color:#4C1D95"></div>
            @if(\Illuminate\Support\Str::startsWith((string)($l['anh_ghi_chu_mime'] ?? ''), 'image/'))
              <img src="{{ route('admin.diho.anhmon', ['don'=>$don,'idx'=>$li]) }}" class="nhan-img" alt="Ảnh ghi chú dòng {{ $li+1 }}">
            @endif
            @endif
          </div>
          @endforeach
          @php
            $tienSp = collect($don->chi_tiet ?: [])->sum(fn($l)=>(int)($l['don_gia_si'] ?? 0) * (int)($l['qty'] ?? 0));
            $phuTen = collect($don->chi_tiet ?: [])->sum(fn($l)=>(int)($l['phu_phi_ten'] ?? 0));
          @endphp
          <div style="margin-top:12px">
            <div class="tot"><span>Tổng số lượng</span><span>{{ (int)$don->so_luong }}</span></div>
            <div class="tot"><span>Tiền sản phẩm (giá sỉ)</span><span>{{ number_format($tienSp,0,',','.') }}đ</span></div>
            @if($phuTen>0)<div class="tot"><span>✍️ Phụ phí in tên riêng</span><span>{{ number_format($phuTen,0,',','.') }}đ</span></div>@endif
            @if((int)$don->thu_them>0)<div class="tot"><span>➕ Chi phí thu thêm{!! $don->thu_them_gc ? ' <i style="color:var(--tx3);font-weight:400">('.e($don->thu_them_gc).')</i>' : '' !!}</span><span>{{ number_format((int)$don->thu_them,0,',','.') }}đ</span></div>@endif
            <div class="tot big"><span>Tổng cộng (tham khảo)</span><span>{{ number_format((int)$don->tong_si,0,',','.') }}đ</span></div>
            <div style="font-size:11px;color:var(--tx3);margin-top:4px">Con số này chỉ để hai bên đối soát — module không thu tiền online.</div>
          </div>

          <form method="POST" action="{{ route('admin.diho.tinhlaigia', $don) }}" style="margin-top:12px" onsubmit="return confirm('Tính lại giá đơn này theo GIÁ SỈ HIỆN TẠI của sản phẩm? Giá cũ trong đơn sẽ bị thay.')">
            @csrf
            <button class="btn btn-o" type="submit" style="width:100%">🔄 Cập nhật lại giá theo giá sản phẩm hiện tại</button>
          </form>

          <form method="POST" action="{{ route('admin.diho.thuthem', $don) }}" style="margin-top:14px;padding-top:12px;border-top:1.5px dashed var(--bd)">
            @csrf
            <div style="font-size:12.5px;font-weight:800;color:var(--char);margin-bottom:8px">➕ Chi phí thu thêm (nếu có)</div>
            <div class="g2" style="align-items:flex-end">
              <div style="flex:1;min-width:130px"><label style="display:block;font-size:11px;color:var(--tx3);font-weight:700;margin-bottom:3px">Số tiền (đ)</label><input type="number" name="thu_them" min="0" value="{{ (int)$don->thu_them }}" style="width:100%"></div>
              <div style="flex:2;min-width:180px"><label style="display:block;font-size:11px;color:var(--tx3);font-weight:700;margin-bottom:3px">Ghi chú (thu tiền gì)</label><input type="text" name="thu_them_gc" maxlength="200" value="{{ $don->thu_them_gc }}" placeholder="VD: phí thiết kế riêng, ship xa…" style="width:100%"></div>
              <button class="btn btn-g" type="submit">Lưu</button>
            </div>
          </form>
          @if($don->ghi_chu)<div style="margin-top:12px;font-size:12.5px;background:var(--gll);border-radius:9px;padding:9px 12px">📝 Ghi chú chung: {{ $don->ghi_chu }}</div>@endif
        </div>

        <div class="sec">
          <h2>🏷️ Nhãn / hoá đơn vận chuyển</h2>
          @if($don->nhan_vc_path)
            <a href="{{ route('admin.diho.nhan', $don) }}" target="_blank" rel="noopener" class="btn-dl">👁 Xem / In nhãn ({{ $don->nhan_vc_ten }})</a>
            @if(\Illuminate\Support\Str::startsWith((string)$don->nhan_vc_mime, 'image/'))
              <img src="{{ route('admin.diho.nhan', $don) }}" class="nhan-img" alt="Nhãn vận chuyển">
            @else
              <div style="font-size:12px;color:var(--tx3);margin-top:8px">File PDF — bấm nút trên để mở &amp; in ngay.</div>
              <iframe src="{{ route('admin.diho.nhan', $don) }}" class="nhan-img" style="width:100%;height:480px;border:1.5px solid var(--bd)" title="Nhãn vận chuyển"></iframe>
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
          <div class="row"><span>Tổng phải thu</span><b style="color:#3E7A0A">{{ number_format((int)$don->tong_si,0,',','.') }}đ</b></div>
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
<script>
var DIHO_CSRF='{{ csrf_token() }}';
var DIHO_LUUSOAN='{{ route('admin.diho.luusoan', $don) }}';
var DIHO_SOAN='{{ route('admin.diho.soan', $don) }}';
document.addEventListener('click',function(e){
  var b=e.target.closest('.ai-mon'); if(!b) return;
  var box=b.closest('.line').querySelector('.ai-mon-kq');
  var ob=b.textContent; b.disabled=true; b.textContent='🤖 Đang đọc…';
  fetch(b.dataset.url,{method:'POST',headers:{'X-CSRF-TOKEN':DIHO_CSRF,'Accept':'application/json'}})
    .then(function(r){return r.json();})
    .then(function(d){
      box.style.display='block';
      if(d.ok){
        box.innerHTML='<b>AI đọc được '+((d.mon&&d.mon.length)||0)+' môn · tổng '+(d.tong||0)+' thẻ:</b><br>'+((d.text||'(trống)').replace(/</g,'&lt;'))+
          '<br><button type="button" class="soan-go" style="margin-top:8px;font-size:12px;font-weight:800;color:#fff;background:#3E7A0A;border:none;border-radius:8px;padding:7px 12px;cursor:pointer">📋 Chuyển sang soạn TKB</button>';
        var g=box.querySelector('.soan-go'); if(g) g.__mon=d.mon||[];
      }
      else { box.innerHTML='⚠️ '+((d.error||'AI chưa đọc được.').replace(/</g,'&lt;')); }
    })
    .catch(function(){ box.style.display='block'; box.textContent='⚠️ Lỗi mạng.'; })
    .finally(function(){ b.disabled=false; b.textContent=ob; });
});
// Chuyển đơn sang bảng soạn TKB (lưu danh sách môn rồi mở trang soạn)
document.addEventListener('click',function(e){
  var g=e.target.closest('.soan-go'); if(!g) return;
  var mon=g.__mon||[]; if(!mon.length){ return; }
  g.disabled=true; g.textContent='Đang chuyển…';
  fetch(DIHO_LUUSOAN,{method:'POST',headers:{'X-CSRF-TOKEN':DIHO_CSRF,'Content-Type':'application/json'},body:JSON.stringify({mon:mon})})
    .then(function(){ window.location=DIHO_SOAN; })
    .catch(function(){ g.disabled=false; g.textContent='📋 Chuyển sang soạn TKB'; });
});
</script>
</body>
</html>
