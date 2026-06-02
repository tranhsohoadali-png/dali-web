<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Yêu cầu rút tiền | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:var(--gd)}
.alert-err{background:#FFF0F0;border-left:3px solid #EF4444;border-radius:9px;padding:12px 16px;margin-bottom:16px;font-size:13px;font-weight:600;color:#B91C1C}
.card{background:#fff;border-radius:16px;border:0.5px solid var(--bd);overflow:hidden;margin-bottom:20px}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-head{padding:14px 22px;border-bottom:1px solid var(--gl);background:var(--gll)}
.card-title{font-size:14px;font-weight:900;color:var(--char)}
.stat-boxes{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px}
.stat-box{background:#fff;border-radius:14px;border:1.5px solid var(--bd);padding:16px;text-align:center}
.stat-num{font-size:20px;font-weight:900;color:var(--g);margin-bottom:3px}
.stat-label{font-size:11px;color:var(--tx3)}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:10px 14px;background:var(--gll);border-bottom:1.5px solid var(--bd);text-align:left}
td{padding:11px 14px;border-bottom:1px solid var(--gl);font-size:12px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.badge{display:inline-block;padding:2px 9px;border-radius:20px;font-size:10px;font-weight:700}
.bank-box{font-family:monospace;font-size:11px;background:var(--gl);padding:4px 8px;border-radius:6px;color:var(--gd);display:inline-block}
.btn-ok{padding:6px 13px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap}
.btn-no{padding:6px 13px;background:#FFF0F0;color:#EF4444;border:1px solid #FECACA;border-radius:7px;font-size:11px;font-weight:700;cursor:pointer}
</style></head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div><div class="tb-bc">Admin › <b>Rút tiền CTV</b></div><div class="tb-title">Yêu cầu rút tiền</div></div>
    </div>
    <div class="sak"><span>🌸</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      @if(session('success'))<div class="alert-ok">✅ {{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert-err">⚠️ {{ session('error') }}</div>@endif

      <div class="stat-boxes">
        <div class="stat-box"><div class="stat-num">{{ $pendingCount }}</div><div class="stat-label">Yêu cầu chờ duyệt</div></div>
        <div class="stat-box"><div class="stat-num" style="font-size:15px;color:var(--pk)">{{ number_format($pendingTotal,0,',','.') }}đ</div><div class="stat-label">Tổng tiền chờ chuyển</div></div>
        <div class="stat-box"><div class="stat-num">{{ $withdrawals->total() }}</div><div class="stat-label">Tổng lượt rút</div></div>
      </div>

      <div class="card">
        <div class="rainbow"></div>
        <div class="card-head"><div class="card-title">Danh sách yêu cầu rút tiền</div></div>
        <table>
          <thead><tr>
            <th>#</th><th>CTV</th><th>Số tiền</th><th>Thông tin chuyển khoản</th>
            <th>Ghi chú</th><th>Thời gian</th><th>Trạng thái</th><th>Thao tác</th>
          </tr></thead>
          <tbody>
          @forelse($withdrawals as $w)
          <tr>
            <td style="color:var(--tx3)">{{ $w->id }}</td>
            <td>
              <div style="font-weight:700">{{ $w->affiliate->name ?? '—' }}</div>
              <div style="font-size:10px;color:var(--tx3)">{{ $w->affiliate->phone ?? '' }} · {{ $w->affiliate->code ?? '' }}</div>
            </td>
            <td style="font-weight:900;color:var(--gd);font-size:14px">{{ number_format($w->amount,0,',','.') }}đ</td>
            <td>
              <span class="bank-box">{{ $w->bank_name }} · {{ $w->bank_acc }}</span>
              <div style="font-size:10px;color:var(--tx3);margin-top:3px">{{ $w->bank_owner }}</div>
            </td>
            <td style="font-size:11px;color:var(--tx2);max-width:160px">{{ $w->note }}</td>
            <td style="font-size:11px;color:var(--tx3)">{{ $w->created_at->format('d/m/Y H:i') }}</td>
            <td>
              @php $c = $w->status=='approved'?['#DCFCE7','#166534']:($w->status=='rejected'?['#FEE2E2','#991B1B']:['#FEF3C7','#92400E']); @endphp
              <span class="badge" style="background:{{ $c[0] }};color:{{ $c[1] }}">{{ $w->status_label }}</span>
              @if($w->processed_at)<div style="font-size:9px;color:var(--tx3);margin-top:3px">{{ $w->processed_at->format('d/m H:i') }}</div>@endif
            </td>
            <td>
              @if($w->status=='pending')
              <div style="display:flex;gap:5px">
                <form method="POST" action="{{ route('admin.withdrawals.approve', $w) }}" onsubmit="return confirm('Xác nhận ĐÃ chuyển khoản {{ number_format($w->amount,0,',','.') }}đ cho {{ addslashes($w->affiliate->name ?? 'CTV') }}? Thao tác này sẽ trừ vào số dư của CTV.')">
                  @csrf <button type="submit" class="btn-ok">✓ Đã CK</button>
                </form>
                <form method="POST" action="{{ route('admin.withdrawals.reject', $w) }}" onsubmit="return confirm('Từ chối yêu cầu này?')">
                  @csrf <button type="submit" class="btn-no">✕</button>
                </form>
              </div>
              @else
              <span style="color:var(--tx3);font-size:11px">—</span>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:36px;color:var(--tx3)">Chưa có yêu cầu rút tiền nào.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
      <div style="margin-bottom:24px">{{ $withdrawals->links() }}</div>

      <div style="background:#fff;border-radius:14px;border:1.5px solid var(--bd);padding:18px;font-size:13px;color:var(--tx2)">
        <div style="font-weight:800;color:var(--char);margin-bottom:8px">📖 Quy trình duyệt rút tiền</div>
        <div>1. CTV gửi yêu cầu rút từ cổng CTV → 2. Bạn chuyển khoản thủ công theo thông tin ngân hàng hiển thị → 3. Bấm <b>"✓ Đã CK"</b> để xác nhận (hệ thống tự trừ số dư CTV).</div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
