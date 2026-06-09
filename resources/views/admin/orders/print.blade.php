<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đơn {{ $order->code }} — Mã tranh & khách hàng</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0;font-family:'Segoe UI',Arial,sans-serif}
  body{color:#1a1a1a;padding:12px 14px;font-size:9px;line-height:1.35}
  .head{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #3A9A12;padding-bottom:7px;margin-bottom:9px}
  .brand{font-size:16px;font-weight:900;color:#2D7A08;letter-spacing:1px}
  .brand span{color:#6BBF1F}
  .brand small{display:block;font-size:7px;letter-spacing:2px;color:#8FC860;font-weight:600}
  .ohead{text-align:right}
  .ohead .code{font-size:12px;font-weight:900;color:#2D7A08}
  .ohead .date{font-size:8.5px;color:#666;margin-top:1px}
  .cust{background:#F4FDE8;border:1px solid #C8E89A;border-radius:6px;padding:7px 10px;margin-bottom:9px}
  .cust .r{display:flex;margin-bottom:2px}
  .cust .k{width:92px;color:#4A8A1A;font-weight:600}
  .cust .v{font-weight:700;flex:1}
  table{width:100%;border-collapse:collapse;margin-bottom:8px}
  th{background:#2D7A08;color:#fff;font-size:9px;text-align:left;padding:4px 7px}
  th.r,td.r{text-align:right}
  th.c,td.c{text-align:center}
  td{padding:3px 7px;border-bottom:1px solid #ddd;font-size:9px}
  tr:nth-child(even) td{background:#FaFFf2}
  .code{font-weight:800;color:#2D7A08}
  tfoot td{font-weight:800;background:#E8F9D0;border-top:1.5px solid #2D7A08}
  .note{font-size:8px;color:#888;margin-top:6px}
  .bar{position:fixed;top:0;left:0;right:0;background:#1C5200;color:#fff;padding:8px 16px;display:flex;gap:10px;justify-content:center;align-items:center}
  .bar button{background:#C6F135;color:#1C3A0A;border:none;border-radius:7px;padding:7px 16px;font-size:12px;font-weight:800;cursor:pointer}
  .bar a{color:#fff;font-size:11px;text-decoration:underline}
  .spacer{height:42px}
  @media print{ .bar,.spacer{display:none} body{padding:3px} @page{margin:8mm} }
</style>
</head>
<body>
  <div class="bar">
    <button onclick="window.print()">🖨️ In / Lưu PDF</button>
    <a href="{{ route('admin.orders.show', $order) }}">← Quay lại đơn hàng</a>
  </div>
  <div class="spacer"></div>

  <div class="head">
    <div class="brand">DAL<span>I</span><small>TÔ ĐIỂM CUỘC SỐNG</small></div>
    <div class="ohead">
      <div class="code">Đơn {{ $order->code }}</div>
      <div class="date">Ngày đặt: {{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
    </div>
  </div>

  <div class="cust">
    <div class="r"><div class="k">Khách hàng</div><div class="v">{{ $order->customer_name }}</div></div>
    <div class="r"><div class="k">Số điện thoại</div><div class="v">{{ $order->customer_phone }}</div></div>
    <div class="r"><div class="k">Địa chỉ</div><div class="v">{{ trim(($order->customer_address ?? '').', '.($order->customer_city ?? ''), ', ') }}</div></div>
    @if($order->note)<div class="r"><div class="k">Ghi chú</div><div class="v">{{ $order->note }}</div></div>@endif
  </div>

  <table>
    <thead>
      <tr>
        <th class="c" style="width:38px">STT</th>
        <th style="width:90px">Mã tranh</th>
        <th>Tên tranh</th>
        <th style="width:90px">Kích thước</th>
        <th class="c" style="width:48px">SL</th>
        <th class="r" style="width:90px">Đơn giá</th>
        <th class="r" style="width:100px">Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $i => $it)
      <tr>
        <td class="c">{{ $i + 1 }}</td>
        <td class="code">{{ $it['code'] ?: '—' }}</td>
        <td>{{ $it['name'] }}</td>
        <td>{{ $it['size'] }}</td>
        <td class="c">{{ $it['qty'] }}</td>
        <td class="r">{{ number_format($it['price'], 0, ',', '.') }}đ</td>
        <td class="r">{{ number_format($it['subtotal'], 0, ',', '.') }}đ</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4" class="r">Tổng cộng</td>
        <td class="c">{{ $items->sum('qty') }}</td>
        <td></td>
        <td class="r">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
      </tr>
    </tfoot>
  </table>

  <div class="note">Tổng {{ $items->count() }} mã tranh · In từ hệ thống DALI lúc {{ now()->format('d/m/Y H:i') }}.</div>

  <script>window.addEventListener('load',function(){setTimeout(function(){try{window.print();}catch(e){}},400);});</script>
</body>
</html>
