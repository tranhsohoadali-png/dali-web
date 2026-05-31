<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đơn hàng | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro','Segoe UI',sans-serif}
body{background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc b{color:var(--g)}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0;font-size:14px}
@keyframes drift{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:11px 15px;margin-bottom:14px;font-size:13px;font-weight:600;color:var(--gd)}
.flt{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:center}
.dinput,.dselect{background:#fff;border:1.5px solid var(--bd);border-radius:9px;padding:9px 13px;font-size:13px;color:var(--tx);outline:none;font-family:inherit;transition:all .2s}
.dinput:focus,.dselect:focus{border-color:var(--g)}
.btn-f{padding:9px 16px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:9px;font-size:13px;font-weight:700;cursor:pointer}
.btn-f:hover{background:var(--g);color:#fff;border-color:var(--g)}
.card{background:#fff;border-radius:14px;border:0.5px solid var(--bd);overflow:hidden;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-h{padding:13px 20px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;justify-content:space-between}
.card-t{font-size:14px;font-weight:900;color:var(--char)}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:800;letter-spacing:1px;color:var(--tx3);text-transform:uppercase;padding:10px 14px;background:var(--gll);border-bottom:1px solid var(--bd);text-align:left}
td{padding:11px 14px;border-bottom:0.5px solid var(--gl);font-size:12px;color:var(--tx);vertical-align:middle}
tr:hover td{background:var(--gll)}
.order-code{font-weight:800;color:var(--g)}
.btn-view{padding:4px 10px;background:var(--gl);color:var(--gd);border:1px solid var(--bd2);border-radius:6px;font-size:11px;font-weight:700;text-decoration:none}
.btn-view:hover{background:var(--g);color:#fff;border-color:var(--g)}
.btn-del{padding:4px 9px;background:#FFF0F0;color:#EF4444;border:1px solid #FECACA;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer}
.btn-del:hover{background:#EF4444;color:#fff}
.pagination{display:flex;gap:6px;margin-top:14px;flex-wrap:wrap}
.pagination a,.pagination span{padding:6px 12px;border-radius:7px;font-size:12px;font-weight:600;text-decoration:none;border:1px solid var(--bd);color:var(--tx2);background:#fff}
.pagination a:hover{background:var(--g);color:#fff;border-color:var(--g)}
.pagination .active span{background:var(--g);color:#fff;border-color:var(--g)}
.status-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
.s-tab{padding:6px 14px;border-radius:20px;border:1.5px solid var(--bd);background:#fff;font-size:12px;font-weight:700;color:var(--tx2);cursor:pointer;text-decoration:none;transition:all .2s}
.s-tab:hover,.s-tab.on{background:var(--g);color:#fff;border-color:var(--g)}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb"><div><div class="tb-bc">Admin › <b>Đơn hàng</b></div><div class="tb-title">Quản lý Đơn Hàng</div></div></div>
    <div class="sak"><span style="animation:drift 5s ease-in-out infinite;display:inline-block">🌸</span><span style="animation:drift 5s ease-in-out infinite 1s;display:inline-block">✿</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      @if(session('success'))<div class="alert-ok">✅ {{ session('success') }}</div>@endif

      <!-- Tab lọc nhanh -->
      <div class="status-tabs">
        <a href="{{ route('admin.orders.index') }}" class="s-tab {{ !request('status') ? 'on' : '' }}">Tất cả</a>
        <a href="{{ route('admin.orders.index',['status'=>'new']) }}" class="s-tab {{ request('status')=='new' ? 'on' : '' }}">🆕 Mới</a>
        <a href="{{ route('admin.orders.index',['status'=>'confirmed']) }}" class="s-tab {{ request('status')=='confirmed' ? 'on' : '' }}">✅ Xác nhận</a>
        <a href="{{ route('admin.orders.index',['status'=>'packing']) }}" class="s-tab {{ request('status')=='packing' ? 'on' : '' }}">📦 Đóng gói</a>
        <a href="{{ route('admin.orders.index',['status'=>'shipping']) }}" class="s-tab {{ request('status')=='shipping' ? 'on' : '' }}">🚚 Đang giao</a>
        <a href="{{ route('admin.orders.index',['status'=>'delivered']) }}" class="s-tab {{ request('status')=='delivered' ? 'on' : '' }}">✔️ Đã giao</a>
        <a href="{{ route('admin.orders.index',['status'=>'cancelled']) }}" class="s-tab {{ request('status')=='cancelled' ? 'on' : '' }}">❌ Huỷ</a>
      </div>

      <form method="GET" action="{{ route('admin.orders.index') }}">
        <div class="flt">
          <input type="hidden" name="status" value="{{ request('status') }}">
          <input type="text" name="search" class="dinput" placeholder="🔍 Mã đơn, tên, SĐT..." value="{{ request('search') }}" style="width:220px">
          <input type="date" name="date" class="dinput" value="{{ request('date') }}">
          <button type="submit" class="btn-f">Lọc</button>
          @if(request()->hasAny(['search','date']))<a href="{{ route('admin.orders.index',['status'=>request('status')]) }}" style="font-size:12px;color:var(--pk);font-weight:700;text-decoration:none">✕ Xoá lọc</a>@endif
          <a href="{{ route('admin.orders.export',request()->only(['status','date_from','date_to'])) }}" class="btn-f" style="background:linear-gradient(135deg,#16A34A,#22C55E);text-decoration:none">⬇ Xuất CSV</a>
        </div>
      </form>

      <div class="card">
        <div class="rainbow"></div>
        <div class="card-h"><div class="card-t">{{ $orders->total() }} đơn hàng</div></div>
        <table>
          <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>SĐT</th><th>Tổng tiền</th><th>Thanh toán</th><th>TT Thanh toán</th><th>Trạng thái</th><th>Ngày đặt</th><th>Thao tác</th></tr></thead>
          <tbody>
          @forelse($orders as $o)
          <tr>
            <td><span class="order-code">{{ $o->code }}</span></td>
            <td style="font-weight:600">{{ $o->customer_name }}</td>
            <td>{{ $o->customer_phone }}</td>
            <td style="font-weight:800;color:var(--g)">{{ number_format($o->total,0,',','.') }}đ</td>
            <td><span style="font-size:11px;background:{{ $o->payment_method=='BANK' ? '#DBEAFE' : '#F3F4F6' }};color:{{ $o->payment_method=='BANK' ? '#1D4ED8' : '#6B7280' }};padding:2px 8px;border-radius:20px;font-weight:700">{{ $o->payment_label }}</span></td>
            <td><span style="font-size:11px;background:{{ $o->payment_status=='paid' ? '#DCFCE7' : '#FEF9C3' }};color:{{ $o->payment_status=='paid' ? '#16A34A' : '#854D0E' }};padding:2px 8px;border-radius:20px;font-weight:700">{{ $o->payment_status_label }}</span></td>
            <td><span style="font-size:11px;padding:2px 8px;border-radius:20px;font-weight:700;border:1px solid {{ $o->status_color }}40;color:{{ $o->status_color }};background:{{ $o->status_color }}15">{{ $o->status_label }}</span></td>
            <td style="color:var(--tx3);font-size:11px">{{ $o->created_at->format('d/m/Y H:i') }}</td>
            <td><div style="display:flex;gap:5px">
              <a href="{{ route('admin.orders.show',$o) }}" class="btn-view">Chi tiết</a>
              <form method="POST" action="{{ route('admin.orders.destroy',$o) }}" onsubmit="return confirm('Xoá đơn này?')">@csrf @method('DELETE')<button type="submit" class="btn-del">🗑️</button></form>
            </div></td>
          </tr>
          @empty
          <tr><td colspan="9" style="text-align:center;padding:36px;color:var(--tx3)">Không có đơn hàng nào</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
      @if($orders->hasPages())
      <div class="pagination">{{ $orders->links() }}</div>
      @endif
    </div>
  </div>
</div>
</body>
</html>