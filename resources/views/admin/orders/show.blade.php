<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $order->code }} | DALI Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--g:#6BBF1F;--gd:#3E7A0A;--gl:#E8F9D0;--gll:#F4FDE8;--gn:#C6F135;--pk:#FF8FB1;--bd:#C8E89A;--bd2:#A8D870;--bg:#F2FDE8;--tx:#1A4D00;--tx2:#4A8A1A;--tx3:#8FC860;--char:#1C3A0A}
*{box-sizing:border-box;margin:0;padding:0;font-family:'Be Vietnam Pro','Segoe UI',sans-serif}
body{background:var(--bg);color:var(--tx)}
.layout{display:flex;min-height:100vh}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.tb{background:#fff;border-bottom:2px solid var(--gl);height:64px;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.tb-bc{font-size:10px;color:var(--tx3)}.tb-bc a{color:var(--g);text-decoration:none;font-weight:600}
.tb-title{font-size:18px;font-weight:900;background:linear-gradient(90deg,#2D7A08,var(--g));-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-top:2px}
.sak{background:linear-gradient(90deg,#fff8fa,#f6ffe8,#fff);border-bottom:1px solid #F0EBF8;padding:6px 24px;display:flex;align-items:center;gap:5px;flex-shrink:0;font-size:14px}
.sak-t{font-size:10px;color:#B8D8A0;letter-spacing:2px;font-weight:700;margin-left:8px}
.cnt{flex:1;overflow-y:auto;padding:22px 24px}
.alert-ok{background:var(--gll);border-left:3px solid var(--g);border-radius:9px;padding:11px 15px;margin-bottom:14px;font-size:13px;font-weight:600;color:var(--gd)}
.grid2{display:grid;grid-template-columns:1fr 340px;gap:16px;align-items:start}
.card{background:#fff;border-radius:14px;border:0.5px solid var(--bd);overflow:hidden;margin-bottom:14px;box-shadow:0 3px 18px rgba(58,122,10,.07)}
.rainbow{height:4px;background:linear-gradient(90deg,#3A9A12,var(--g),var(--gn),#FF8FB1,#A78BFA)}
.card-h{padding:13px 20px;border-bottom:1px solid var(--gl);background:var(--gll);display:flex;align-items:center;justify-content:space-between}
.card-t{font-size:14px;font-weight:900;color:var(--char)}
.info-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:0.5px solid var(--gl);font-size:13px}
.info-row:last-child{border-bottom:none}
.info-label{color:var(--tx3);font-weight:500}
.info-val{font-weight:700;color:var(--char);text-align:right;max-width:240px}
.total-row{display:flex;justify-content:space-between;padding:8px 0;font-size:13px}
.total-row.final{border-top:1.5px dashed var(--bd);padding-top:12px;margin-top:4px;font-size:16px;font-weight:900}
.total-row.final .v{color:var(--g);font-size:20px}
.btn-back{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;background:#fff;border:1.5px solid var(--bd);color:var(--tx3);font-size:13px;font-weight:600;border-radius:9px;cursor:pointer;text-decoration:none}
.btn-back:hover{border-color:var(--g);color:var(--g)}
.status-select{width:100%;background:var(--gll);border:1.5px solid var(--bd);border-radius:10px;padding:11px 14px;font-size:13px;color:var(--tx);outline:none;margin-bottom:10px;font-family:inherit}
.status-select:focus{border-color:var(--g)}
.btn-status{width:100%;padding:12px;background:linear-gradient(135deg,#3A9A12,var(--g));color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:800;cursor:pointer}
.btn-status:hover{transform:translateY(-1px)}
.item-row{display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:0.5px solid var(--gl)}
.item-img{width:48px;height:48px;object-fit:cover;border-radius:8px;background:var(--gl);border:1px solid var(--bd);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
.status-timeline{padding:14px 20px}
.st-item{display:flex;align-items:center;gap:12px;padding:8px 0}
.st-dot{width:12px;height:12px;border-radius:50%;flex-shrink:0;border:2px solid}
.st-done{background:var(--g);border-color:var(--g)}
.st-curr{background:#fff;border-color:var(--g);box-shadow:0 0 0 3px rgba(107,191,31,.2)}
.st-pend{background:#fff;border-color:var(--bd)}
.st-line{width:2px;height:20px;background:var(--bd);margin-left:5px}
.st-line.done{background:var(--g)}
</style>
</head>
<body>
<div class="layout">
  @include('admin.partials.sidebar')
  <div class="main">
    <div class="tb">
      <div><div class="tb-bc"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a> › <b>{{ $order->code }}</b></div><div class="tb-title">Chi tiết đơn hàng</div></div>
      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        <a href="{{ route('admin.orders.export-csv', $order) }}" class="btn-back" style="border-color:#A8D870;color:#3E7A0A;font-weight:700"> 📊 Xuất Excel</a>
        <a href="{{ route('admin.orders.print', $order) }}" target="_blank" rel="noopener" class="btn-back" style="border-color:#A8D870;color:#3E7A0A;font-weight:700">📄 Xuất PDF / In</a>
        <a href="{{ route('admin.orders.index') }}" class="btn-back">← Quay lại</a>
      </div>
    </div>
    <div class="sak"><span style="font-size:14px">🌸</span><span style="font-size:14px">✿</span><span class="sak-t">DALI · TÔ ĐIỂM CUỘC SỐNG</span></div>
    <div class="cnt">
      @if(session('success'))<div class="alert-ok">✅ {{ session('success') }}</div>@endif

      <div class="grid2">
        <div>
          <!-- Thông tin khách hàng -->
          <div class="card">
            <div class="rainbow"></div>
            <div class="card-h"><div class="card-t">👤 Thông tin khách hàng</div><span style="font-size:12px;font-weight:800;color:var(--g)">{{ $order->code }}</span></div>
            <div style="padding:6px 20px 10px">
              <div class="info-row"><span class="info-label">Họ tên</span><span class="info-val">{{ $order->customer_name }}</span></div>
              <div class="info-row"><span class="info-label">Số điện thoại</span><span class="info-val"><a href="tel:{{ $order->customer_phone }}" style="color:var(--g);text-decoration:none">{{ $order->customer_phone }}</a></span></div>
              <div class="info-row"><span class="info-label">Địa chỉ</span><span class="info-val">{{ $order->customer_address }}, {{ $order->customer_city }}</span></div>
              @php
                // Đơn THIẾT KẾ: tách 3 link ảnh ra khỏi ghi chú để hiện thành ảnh.
                $tkImgs = [];
                $noteClean = $order->note;
                if (\Illuminate\Support\Str::startsWith((string) $order->note, 'ĐƠN THIẾT KẾ')) {
                    foreach (['Ảnh gốc' => 'goc', 'Ảnh AI' => 'ai', 'Bản đồ màu' => 'map'] as $lbl => $k) {
                        if (preg_match('/' . preg_quote($lbl, '/') . ':\s*(https?:\/\/\S+)/u', $order->note, $m)) {
                            $tkImgs[$k] = rtrim($m[1], " |.");
                        }
                    }
                    // Ghi chú gọn: bỏ các đoạn link dài, chỉ giữ phần gói.
                    $noteClean = trim(preg_replace('/\s*\|?\s*(Ảnh gốc|Ảnh AI|Bản đồ màu):\s*\S+/u', '', $order->note));
                    $noteClean = rtrim($noteClean, " |.");
                }
              @endphp
              @if($noteClean)<div class="info-row"><span class="info-label">Ghi chú</span><span class="info-val">{{ $noteClean }}</span></div>@endif
              <div class="info-row"><span class="info-label">Ngày đặt</span><span class="info-val">{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
            </div>
          </div>

          @if(!empty($tkImgs))
          <!-- Ảnh thiết kế của khách (đơn /thiet-ke) -->
          <div class="card">
            <div class="rainbow"></div>
            <div class="card-h"><div class="card-t">🖼️ Ảnh thiết kế của khách</div><span style="font-size:11px;color:#94A3B8">Bấm ảnh để mở · tải về trong 24h</span></div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;padding:6px 20px 16px">
              @foreach(['goc' => ['📷 Ảnh thật khách gửi','#E2E8F0'], 'ai' => ['✨ Bản tăng cường AI','#4CAF50'], 'map' => ['🎨 Bản đồ màu (để in)','#F59E0B']] as $k => $meta)
                <div style="text-align:center">
                  @if(!empty($tkImgs[$k]))
                    <a href="{{ $tkImgs[$k] }}" target="_blank" style="display:block">
                      <img src="{{ $tkImgs[$k] }}" loading="lazy" style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:10px;border:2px solid {{ $meta[1] }};background:#F8FAF5">
                    </a>
                  @else
                    <div style="width:100%;aspect-ratio:1;border-radius:10px;border:2px dashed #FCD34D;background:#FEF9E7;display:flex;align-items:center;justify-content:center;font-size:11px;color:#B45309;font-weight:700;padding:6px;text-align:center">⚠️ Không có{{ $k==='ai' ? ' (hết credit AI)' : '' }}</div>
                  @endif
                  <div style="font-size:11px;font-weight:700;color:var(--char);margin-top:5px">{{ $meta[0] }}</div>
                </div>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Sản phẩm đặt -->
          <div class="card">
            <div class="rainbow"></div>
            <div class="card-h"><div class="card-t">🎨 Sản phẩm đặt mua</div></div>
            @foreach($order->items as $item)
            <div class="item-row">
              <div class="item-img">🎨</div>
              <div style="flex:1">
                <div style="font-size:13px;font-weight:700;color:var(--char)">{{ $item->product_name }}</div>
                @if($item->product_size)<div style="font-size:11px;color:var(--tx3)">{{ $item->product_size }}</div>@endif
              </div>
              <div style="text-align:right">
                <div style="font-size:13px;color:var(--tx3)">{{ number_format($item->price,0,',','.') }}đ × {{ $item->quantity }}</div>
                <div style="font-size:15px;font-weight:800;color:var(--g)">{{ number_format($item->subtotal,0,',','.') }}đ</div>
              </div>
            </div>
            @endforeach
            <div style="padding:14px 20px">
              <div class="total-row"><span style="color:var(--tx3)">Tạm tính</span><span>{{ number_format($order->subtotal,0,',','.') }}đ</span></div>
              @if($order->discount > 0)<div class="total-row" style="color:var(--gd)"><span>Giảm 5% CK</span><span>-{{ number_format($order->discount,0,',','.') }}đ</span></div>@endif
              <div class="total-row"><span style="color:var(--tx3)">Phí ship</span><span>{{ $order->ship_fee > 0 ? number_format($order->ship_fee,0,',','.') . 'đ' : 'Miễn phí' }}</span></div>
              <div class="total-row final"><span>Tổng thanh toán</span><span class="v">{{ number_format($order->total,0,',','.') }}đ</span></div>
              @if(($order->deposit ?? 0) > 0)
              <div class="total-row" style="color:#6D28D9;font-weight:800;border-top:1px dashed var(--bd);margin-top:6px;padding-top:10px">
                <span>💰 Cọc đại lý</span>
                <span>{{ number_format($order->deposit,0,',','.') }}đ
                  @if($order->deposit_paid)<span style="color:#16A34A;font-size:11px;font-weight:700">· đã nhận</span>@else<span style="color:#EF4444;font-size:11px;font-weight:700">· chưa nhận</span>@endif
                </span>
              </div>
              @if(!$order->deposit_paid)
              <form method="POST" action="{{ route('admin.orders.deposit-paid', $order) }}" style="margin-top:8px">@csrf
                <button type="submit" style="width:100%;background:#6D28D9;color:#fff;border:none;border-radius:9px;padding:9px;font-size:12px;font-weight:700;cursor:pointer">✓ Đánh dấu đã nhận cọc</button>
              </form>
              @endif
              @endif
            </div>
          </div>
        </div>

        <!-- Sidebar: cập nhật trạng thái -->
        <div>
          <div class="card">
            <div class="rainbow"></div>
            <div class="card-h"><div class="card-t">⚡ Cập nhật trạng thái</div></div>
            <div style="padding:16px 18px">
              <form method="POST" action="{{ route('admin.orders.status',$order) }}">
                @csrf
                <div style="margin-bottom:10px">
                  <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:7px">Trạng thái đơn hàng</label>
                  <select name="status" class="status-select">
                    <option value="new"       {{ $order->status=='new'       ?'selected':'' }}>🆕 Đơn mới</option>
                    <option value="confirmed" {{ $order->status=='confirmed' ?'selected':'' }}>✅ Đã xác nhận</option>
                    <option value="packing"   {{ $order->status=='packing'   ?'selected':'' }}>📦 Đang đóng gói</option>
                    <option value="shipping"  {{ $order->status=='shipping'  ?'selected':'' }}>🚚 Đang giao hàng</option>
                    <option value="delivered" {{ $order->status=='delivered' ?'selected':'' }}>✔️ Đã giao</option>
                    <option value="cancelled" {{ $order->status=='cancelled' ?'selected':'' }}>❌ Đã huỷ</option>
                  </select>
                </div>
                <div style="margin-bottom:10px">
                  <label style="font-size:12px;font-weight:700;color:var(--tx);display:block;margin-bottom:7px">Mã vận đơn Viettel Post <span style="color:var(--tx3);font-weight:500">(tự nhập sau khi gửi)</span></label>
                  <input type="text" name="vtp_code" value="{{ $order->vtp_order_number }}" placeholder="VD: 0123456789" class="status-select" style="margin-bottom:0">
                  <div style="font-size:11px;color:var(--tx3);margin-top:5px">Khách sẽ thấy mã này + nút “Theo dõi trên Viettel Post” ở trang tra cứu đơn.</div>
                </div>
                <button type="submit" class="btn-status">Cập nhật trạng thái</button>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="rainbow"></div>
            <div class="card-h"><div class="card-t">🚚 Vận chuyển Viettel Post</div></div>
            <div style="padding:14px 18px">
              @if($order->vtp_order_number)
                <div class="info-row"><span class="info-label">Mã vận đơn</span><span style="font-weight:800;font-size:14px;color:var(--g);user-select:all">{{ $order->vtp_order_number }}</span></div>
                <a href="https://viettelpost.com.vn/tra-cuu-hanh-trinh-don/" target="_blank" class="btn-status" style="display:block;text-align:center;text-decoration:none;background:#3A9A12;margin-top:12px">🔎 Mở trang tra cứu Viettel Post</a>
                <p style="font-size:11px;color:var(--tx3);margin-top:8px">Khách cũng thấy mã này + nút “Theo dõi trên Viettel Post” ở trang tra cứu đơn của họ.</p>
              @else
                <p style="font-size:12px;color:var(--tx3)">Chưa có mã vận đơn. Sau khi gửi Viettel Post, <b>nhập mã ở ô “Cập nhật trạng thái”</b> phía trên — khách sẽ tự tra cứu được.</p>
              @endif
            </div>
          </div>

          <div class="card">
            <div class="rainbow"></div>
            <div class="card-h"><div class="card-t">💳 Thanh toán</div></div>
            <div style="padding:14px 18px">
              <div class="info-row"><span class="info-label">Hình thức</span><span style="font-weight:700;font-size:13px">{{ $order->payment_label }}</span></div>
              <div class="info-row" style="border:none"><span class="info-label">Trạng thái</span>
                <span style="font-size:12px;padding:3px 10px;border-radius:20px;font-weight:700;background:{{ $order->payment_status=='paid' ? '#DCFCE7' : '#FEF9C3' }};color:{{ $order->payment_status=='paid' ? '#16A34A' : '#854D0E' }}">{{ $order->payment_status_label }}</span>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="rainbow"></div>
            <div class="card-h"><div class="card-t">📊 Timeline</div></div>
            <div class="status-timeline">
              @php
              $steps=[['new','Đơn mới'],['confirmed','Xác nhận'],['packing','Đóng gói'],['shipping','Đang giao'],['delivered','Đã giao']];
              $statusOrder=['new'=>0,'confirmed'=>1,'packing'=>2,'shipping'=>3,'delivered'=>4,'cancelled'=>5];
              $curr=$statusOrder[$order->status]??0;
              @endphp
              @foreach($steps as $i=>[$s,$l])
              <div class="st-item">
                <div class="st-dot {{ $curr>$i ? 'st-done' : ($curr==$i ? 'st-curr' : 'st-pend') }}"></div>
                <span style="font-size:12px;font-weight:{{ $curr==$i ? '800' : '500' }};color:{{ $curr>=$i ? 'var(--char)' : 'var(--tx3)' }}">{{ $l }}</span>
              </div>
              @if(!$loop->last)<div class="st-line {{ $curr>$i ? 'done' : '' }}"></div>@endif
              @endforeach
              @if($order->status==='cancelled')
              <div class="st-item"><div class="st-dot" style="background:#EF4444;border-color:#EF4444"></div><span style="font-size:12px;font-weight:800;color:#EF4444">Đã huỷ</span></div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>