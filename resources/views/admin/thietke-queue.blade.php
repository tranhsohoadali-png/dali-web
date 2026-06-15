<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Đơn chờ thiết kế | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--bd:#C8E89A;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Be Vietnam Pro',sans-serif;background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}.main{flex:1;display:flex;flex-direction:column;min-width:0}
.tb{background:#fff;border-bottom:2px solid var(--gl);min-height:64px;padding:10px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc a{color:var(--g);text-decoration:none;font-weight:600}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.btn{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1.5px solid var(--bd);color:var(--gd);font-size:12px;font-weight:800;padding:8px 13px;border-radius:9px;cursor:pointer;text-decoration:none}
.btn:hover{background:var(--gll)}
.btn-green{background:var(--g);border-color:var(--g);color:#fff}.btn-green:hover{background:var(--gd)}
.search{display:flex;gap:6px}.search input{border:1.5px solid var(--bd);border-radius:9px;padding:8px 12px;font-size:13px;font-family:inherit;outline:none;width:190px}
.hint{font-size:11px;color:var(--tx3);font-weight:700}
.flash{max-width:1100px;background:#DCFCE7;border:1px solid #86EFAC;color:#15803D;font-weight:700;font-size:13px;padding:12px 16px;border-radius:12px;margin-bottom:16px}
.intro{max-width:1100px;background:#FEF9E7;border:1px solid #FCD34D;color:#92580B;font-size:12.5px;line-height:1.6;padding:12px 16px;border-radius:12px;margin-bottom:16px}
.qgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(330px,1fr));gap:16px;max-width:1100px}
.qcard{background:#fff;border:1px solid var(--bd);border-radius:16px;overflow:hidden;display:flex;flex-direction:column}
.qphoto{position:relative;aspect-ratio:4/3;background:var(--gl)}
.qphoto img{width:100%;height:100%;object-fit:cover;display:block;cursor:zoom-in}
.qnoimg{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--tx3);font-size:13px;font-weight:700}
.qbody{padding:13px 15px;display:flex;flex-direction:column;gap:6px}
.qcode{font-size:15px;font-weight:900;color:var(--char);display:flex;align-items:center;gap:8px;flex-wrap:wrap}
.qbadge{font-size:10px;font-weight:800;background:#FEF3C7;border:1px solid #FCD34D;color:#B45309;padding:2px 8px;border-radius:20px}
.qname{font-size:13px;font-weight:700;color:#0F172A}.qname a{color:#0F172A;text-decoration:none}
.qpkg{font-size:12px;color:var(--tx2);font-weight:700}
.qmoney{font-size:12px;color:var(--tx2)}
.qok{color:#15803D;font-weight:800}.qwarn{color:#B45309;font-weight:800}
.qtime{font-size:11px;color:#94A3B8;font-weight:600}
.qactions{display:flex;flex-wrap:wrap;gap:6px;margin-top:6px}
.pagi{padding:18px 2px;max-width:1100px}.pagi a,.pagi span{margin-right:6px;font-size:13px}
.empty{max-width:1100px;padding:50px;text-align:center;color:var(--tx3);font-weight:700;background:#fff;border:1px solid var(--bd);border-radius:16px}
@media(max-width:768px){.cnt{padding:12px}.qgrid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div>
        <div class="tb-bc"><a href="{{ route('admin.dashboard') }}">Admin</a> › <b>Đơn chờ thiết kế</b></div>
        <div class="tb-title">Đơn đặt cọc — chờ shop thiết kế &amp; gửi Zalo</div>
      </div>
      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
        <span class="hint">✅ Đã gửi khách: {{ $doneCount }}</span>
        <form class="search" method="get">
          <input name="search" value="{{ request('search') }}" placeholder="Tìm mã / SĐT / tên…">
          <button class="btn" type="submit">🔍 Tìm</button>
        </form>
      </div>
    </div>
    <div class="cnt">
      @if(session('success'))<div class="flash">✓ {{ session('success') }}</div>@endif
      <div class="intro">⏳ Đây là các đơn khách <b>đã đặt cọc</b> nhưng <b>chưa có bản thiết kế AI</b> (do AI quá tải lúc khách đặt). Hãy tải ảnh gốc, tự chạy lại phần mềm màu lúc vắng (hoặc thiết kế tay), rồi <b>gửi bản xem trước cho khách qua Zalo</b>. Xong bấm <b>“Đã gửi khách”</b> để đơn rời danh sách này.</div>

      @if($orders->count())
      <div class="qgrid">
        @foreach($orders as $o)
          @php
            preg_match('/Ảnh gốc:\s*(\S+)/u', $o->note ?? '', $mg); $goc = $mg[1] ?? '';
            preg_match('/Gói:\s*(.+?)\s*\.\s*Ảnh gốc/u', $o->note ?? '', $mp); $pkg = trim($mp[1] ?? '');
            $phoneDigits = preg_replace('/\D/', '', $o->customer_phone);
          @endphp
          <div class="qcard">
            <div class="qphoto">
              @if($goc)
                <a href="{{ $goc }}" target="_blank"><img src="{{ $goc }}" alt="Ảnh khách gửi" loading="lazy"></a>
              @else
                <div class="qnoimg">⚠️ Không tìm thấy ảnh gốc</div>
              @endif
            </div>
            <div class="qbody">
              <div class="qcode">{{ $o->code }} <span class="qbadge">⏳ Chờ thiết kế</span></div>
              <div class="qname">{{ $o->customer_name }} · <a href="tel:{{ $o->customer_phone }}">{{ $o->customer_phone }}</a></div>
              @if($pkg)<div class="qpkg">🖼️ {{ $pkg }}</div>@endif
              <div class="qmoney">
                Giá <b>{{ number_format($o->total,0,',','.') }}đ</b> · Cọc <b>{{ number_format($o->deposit,0,',','.') }}đ</b>
                @if($o->deposit_paid)<span class="qok"> ✓ đã nhận cọc</span>@else<span class="qwarn"> ● chưa nhận cọc</span>@endif
              </div>
              <div class="qtime">🕒 {{ $o->created_at->format('d/m/Y H:i') }}</div>
              <div class="qactions">
                <a class="btn" style="background:#0068FF;border-color:#0068FF;color:#fff" href="https://zalo.me/{{ $phoneDigits }}" target="_blank">💬 Zalo</a>
                <a class="btn" href="{{ route('admin.orders.show', $o) }}">📄 Chi tiết</a>
                @if(!$o->deposit_paid)
                <form method="post" action="{{ route('admin.orders.deposit-paid', $o) }}" style="display:inline">@csrf<button class="btn" type="submit">✓ Đã nhận cọc</button></form>
                @endif
                <form method="post" action="{{ route('admin.orders.design-delivered', $o) }}" style="display:inline" onsubmit="return confirm('Xác nhận: đã gửi bản thiết kế cho khách {{ $o->customer_name }} qua Zalo?')">@csrf<button class="btn btn-green" type="submit">✅ Đã gửi khách</button></form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="pagi">{{ $orders->links() }}</div>
      @else
      <div class="empty">🎉 Không có đơn nào đang chờ thiết kế.</div>
      @endif
    </div>
  </div>
</div>
</body>
</html>
