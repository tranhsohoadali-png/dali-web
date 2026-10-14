<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đối soát đại lý | DALI Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.topbar{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.back{font-size:12px;color:var(--tx2);text-decoration:none;font-weight:700}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.top-row{display:flex;align-items:flex-end;gap:12px;margin-bottom:18px;flex-wrap:wrap}
.fld label{display:block;font-size:10px;font-weight:800;letter-spacing:.5px;color:var(--tx3);text-transform:uppercase;margin-bottom:3px}
.filter-input{background:#fff;border:1.5px solid var(--bd);border-radius:9px;padding:9px 12px;font-size:13px;color:var(--tx);outline:none;font-family:'Be Vietnam Pro',sans-serif}
.filter-input:focus{border-color:var(--g)}
.btn-filter{padding:9px 18px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:9px;font-size:13px;font-weight:700;cursor:pointer}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;color:var(--gd)}
.cards{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px}
@media(max-width:720px){.cards{grid-template-columns:repeat(2,1fr)}}
.kpi{background:#fff;border:1.5px solid var(--bd);border-radius:14px;padding:14px 16px;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.kpi .l{font-size:11px;color:var(--tx3);font-weight:700}.kpi .v{font-size:22px;font-weight:900;color:var(--char);margin-top:3px}
.kpi.hot .v{color:#B45309}
.card{background:#fff;border-radius:16px;border:1.5px solid var(--bd);overflow:hidden;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.card-top{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:11px 14px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:12px 14px;border-bottom:1px solid var(--gl);font-size:13px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.money{font-weight:900;color:var(--g)}.owe{font-weight:900;color:#B45309}
.btn-edit{display:inline-flex;align-items:center;padding:5px 11px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:7px;font-size:11px;font-weight:700;text-decoration:none}
.btn-edit:hover{background:var(--g);color:#fff}
.btn-paid{padding:5px 11px;background:#EAF9D6;color:#2D6A08;border:1px solid var(--bd2);border-radius:7px;font-size:11px;font-weight:800;cursor:pointer}
.btn-paid:hover{background:var(--g);color:#fff}
.btn-part{padding:5px 11px;background:#FEF3C7;color:#92400E;border:1px solid #FCD34D;border-radius:7px;font-size:11px;font-weight:800;cursor:pointer;list-style:none}
.btn-part::-webkit-details-marker{display:none}
details.thu{display:inline-block;vertical-align:top}
details.thu[open] .btn-part{background:#F59E0B;color:#fff}
.thu-box{margin-top:8px;background:#FFFBEB;border:1.5px solid #FCD34D;border-radius:11px;padding:11px 12px;width:290px;white-space:normal;text-align:left}
.thu-box label{display:block;font-size:10.5px;font-weight:800;color:#92400E;margin:6px 0 3px;text-transform:uppercase;letter-spacing:.3px}
.thu-box input{width:100%;border:1.5px solid #FCD34D;border-radius:8px;padding:8px 10px;font-size:14px;font-family:inherit;outline:none;background:#fff}
.thu-box input.tien{font-size:17px;font-weight:800;color:#92400E}
.thu-box .go{margin-top:9px;width:100%;padding:9px;background:#F59E0B;color:#fff;border:none;border-radius:8px;font-weight:800;font-size:13px;cursor:pointer}
.thu-box .note{font-size:11px;color:#92400E;margin-top:6px;line-height:1.45}
.so-thu{margin-top:9px;border-top:1px dashed #FCD34D;padding-top:7px;font-size:11.5px;color:#78350F}
.so-thu div{display:flex;justify-content:space-between;gap:8px;padding:2px 0}
.so-thu .huy-btn{background:none;border:none;color:#B91C1C;font-size:11px;font-weight:800;cursor:pointer;padding:0}
.tra-truoc{font-size:11px;color:#92400E;font-weight:700;margin-top:2px}
.alert-err{background:#FEF2F2;border-left:3px solid #EF4444;border-radius:9px;padding:12px 16px;margin-bottom:18px;font-size:13px;font-weight:600;color:#B91C1C}
</style>
</head>
<body>
<div style="display:flex;min-height:100vh">
@include('admin.partials.sidebar')
<div style="flex:1;display:flex;flex-direction:column;overflow:hidden">
  <div class="topbar">
    <div><div class="tb-bc">Admin › Xưởng in 3D › <b>Đối soát đại lý</b></div><div class="tb-title">Đối soát đại lý</div></div>
    <a href="{{ route('admin.diho.index') }}" class="back">← Đơn đi hộ</a>
  </div>
  <div class="cnt">
    @if(session('ok'))<div class="alert-ok">✅ {{ session('ok') }}</div>@endif
    @if($errors->any())<div class="alert-err">⚠️ {{ $errors->first() }}</div>@endif

    <form method="GET" action="{{ route('admin.diho.doisoat') }}">
      <div class="top-row">
        <div class="fld"><label>Từ ngày</label><input type="date" name="tu" class="filter-input" value="{{ request('tu') }}"></div>
        <div class="fld"><label>Đến ngày</label><input type="date" name="den" class="filter-input" value="{{ request('den') }}"></div>
        <button type="submit" class="btn-filter">Lọc</button>
        @if(request()->hasAny(['tu','den']))<a href="{{ route('admin.diho.doisoat') }}" style="font-size:12px;color:var(--pk);text-decoration:none;font-weight:700">✕ Xoá lọc</a>@endif
        <div style="flex:1"></div>
        <div style="font-size:11.5px;color:var(--tx3);max-width:320px">Tiền = tổng giá sỉ các đơn đi hộ (không tính đơn huỷ). "Chưa thu" là số cần đối soát với đại lý.</div>
      </div>
    </form>

    <div class="cards">
      <div class="kpi"><div class="l">Tổng đơn đi hộ</div><div class="v">{{ number_format($tong['don'],0,',','.') }}</div></div>
      <div class="kpi"><div class="l">Tổng số lượng</div><div class="v">{{ number_format($tong['sl'],0,',','.') }}</div></div>
      <div class="kpi"><div class="l">Tổng tiền sỉ</div><div class="v">{{ number_format($tong['tien'],0,',','.') }}đ</div></div>
      <div class="kpi hot"><div class="l">Chưa thu (còn phải đối soát)</div><div class="v">{{ number_format(max(0,$tong['chua']-$tong['tra_truoc']),0,',','.') }}đ</div>@if($tong['tra_truoc']>0)<div class="tra-truoc">đã trừ {{ number_format($tong['tra_truoc'],0,',','.') }}đ đại lý trả trước</div>@endif</div>
    </div>

    <div class="card">
      <div class="card-top"></div>
      <table>
        <thead><tr><th>Đại lý</th><th>Số đơn</th><th>Tổng SL</th><th>Tổng tiền sỉ</th><th>Chưa thu</th><th></th></tr></thead>
        <tbody>
        @forelse($rows as $r)
        <tr>
          <td><div style="font-weight:700">{{ $r->dai_ly_ten }}</div><div style="font-size:11px;color:var(--tx3)">{{ $r->dai_ly_sdt }}</div></td>
          <td style="font-weight:700">{{ (int)$r->so_don }}</td>
          <td style="font-weight:700">{{ (int)$r->tong_sl }}</td>
          <td class="money">{{ number_format((int)$r->tong_tien,0,',','.') }}đ</td>
          @php $tt = (int)($traTruoc[$r->dai_ly_id] ?? 0); $conNo = max(0, (int)$r->chua_tien - $tt); @endphp
          <td>@if((int)$r->chua_tien>0)<span class="owe">{{ number_format($conNo,0,',','.') }}đ</span><div style="font-size:11px;color:var(--tx3)">{{ (int)$r->chua_don }} đơn{{ $tt>0 ? ' · tổng '.number_format((int)$r->chua_tien,0,',','.').'đ' : '' }}</div>@else<span style="color:var(--g);font-weight:800">✓ Đã thu đủ</span>@endif
            @if($tt>0)<div class="tra-truoc">💰 Đã trả trước {{ number_format($tt,0,',','.') }}đ</div>@endif</td>
          <td style="white-space:nowrap">
            <a href="{{ route('admin.diho.index', ['dai_ly'=>$r->dai_ly_id]) }}" class="btn-edit">Xem đơn</a>
            @if((int)$r->chua_don>0)
            <form method="POST" action="{{ route('admin.diho.doisoat.daily') }}" style="display:inline" onsubmit="return confirm('Đánh dấu ĐÃ thu tiền cho {{ (int)$r->chua_don }} đơn chưa đối soát của {{ $r->dai_ly_ten }}?')">
              @csrf
              <input type="hidden" name="dai_ly_id" value="{{ $r->dai_ly_id }}">
              <input type="hidden" name="tu" value="{{ request('tu') }}"><input type="hidden" name="den" value="{{ request('den') }}">
              <button type="submit" class="btn-paid">✓ Đã thu</button>
            </form>
            <details class="thu">
              <summary class="btn-part">💰 Thu một phần</summary>
              <div class="thu-box">
                <form method="POST" action="{{ route('admin.diho.doisoat.thu') }}">
                  @csrf
                  <input type="hidden" name="dai_ly_id" value="{{ $r->dai_ly_id }}">
                  <label>Số tiền đã nhận</label>
                  <input class="tien" name="so_tien" inputmode="numeric" placeholder="VD: 500.000" required autocomplete="off">
                  <label>Ghi chú (không bắt buộc)</label>
                  <input name="ghi_chu" maxlength="200" placeholder="CK Vietcombank 14/10…">
                  <button type="submit" class="go">Ghi nhận & trừ nợ</button>
                  <div class="note">Trừ vào đơn <b>cũ nhất trước</b>; đơn đủ tiền tự chuyển "Đã thu". Phần lẻ chưa đủ trọn 1 đơn được giữ làm <b>trả trước</b>, tự trừ ở lần thu sau.</div>
                </form>
                @php $so = $soThu[$r->dai_ly_id] ?? collect(); $moiNhat = optional($so->firstWhere('huy', false))->id; @endphp
                @if($so->count())
                <div class="so-thu"><b>Sổ thu gần đây</b>
                  @foreach($so as $p)
                  <div><span style="{{ $p->huy ? 'text-decoration:line-through;opacity:.55' : '' }}">{{ $p->created_at->format('d/m H:i') }} · <b>{{ number_format($p->so_tien,0,',','.') }}đ</b> · {{ count($p->don_ids ?? []) }} đơn{{ $p->ghi_chu ? ' · '.$p->ghi_chu : '' }}</span>
                    @if(!$p->huy && $p->id === $moiNhat)
                    <form method="POST" action="{{ route('admin.diho.doisoat.huythu', $p->id) }}" style="display:inline" onsubmit="return confirm('Huỷ lần thu {{ number_format($p->so_tien,0,',','.') }}đ? Các đơn của lần này sẽ trở lại Chưa thu.')">@csrf<button class="huy-btn" type="submit">Huỷ</button></form>
                    @endif
                  </div>
                  @endforeach
                </div>
                @endif
              </div>
            </details>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:44px;color:var(--tx3)">Chưa có đơn đi hộ nào trong khoảng này.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
</body>
</html>
