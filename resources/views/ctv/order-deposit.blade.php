@extends('ctv.layout')
@section('title','Đặt cọc đơn ' . $order->code)
@section('content')

@php
  $bankId  = $settings['bank_id']    ?? 'VCB';
  $bankAcc = $settings['bank_acc']   ?? '';
  $bankName= $settings['bank_name']  ?? '';
  $bankLbl = $settings['bank_label'] ?? 'Ngân hàng';
  $note    = $order->code . ' COC';
  $qr = $bankAcc
      ? 'https://img.vietqr.io/image/'.$bankId.'-'.$bankAcc.'-qr_only.png?amount='.$order->deposit.'&addInfo='.urlencode($note).'&accountName='.urlencode($bankName)
      : '';
@endphp

<div style="font-size:17px;font-weight:900;color:var(--gd);margin-bottom:4px">💰 Đặt cọc đơn hàng</div>
<div style="font-size:13px;color:var(--tx3);margin-bottom:14px">
  Đơn <b style="color:var(--gd)">{{ $order->code }}</b> · Tổng đơn: {{ number_format($order->total,0,',','.') }}đ
</div>

<div style="background:#fff;border:1.5px solid var(--bd);border-radius:18px;padding:18px;text-align:center">
  <div style="font-size:12px;color:var(--tx3);font-weight:700">Số tiền cần đặt cọc</div>
  <div style="font-size:30px;font-weight:900;color:var(--g);margin:4px 0">{{ number_format($order->deposit,0,',','.') }}đ</div>

  @if($qr)
  <img src="{{ $qr }}" alt="QR đặt cọc" style="width:230px;max-width:78%;border-radius:12px;border:1.5px solid var(--bd);margin:6px 0">
  @else
  <div style="background:#FFF7ED;border:1px dashed #F59E0B;border-radius:10px;padding:10px;font-size:12px;color:#B45309;margin:8px 0">Chưa cấu hình ngân hàng — vào admin → Cài đặt để thêm.</div>
  @endif

  <div style="background:var(--gll);border-radius:12px;padding:12px 14px;text-align:left;font-size:13px;margin-top:8px">
    <div style="display:flex;justify-content:space-between;padding:3px 0"><span style="color:var(--tx3)">Ngân hàng</span><b>{{ $bankLbl }}</b></div>
    <div style="display:flex;justify-content:space-between;padding:3px 0"><span style="color:var(--tx3)">Số TK</span><b>{{ $bankAcc ?: '—' }}</b></div>
    <div style="display:flex;justify-content:space-between;padding:3px 0"><span style="color:var(--tx3)">Chủ TK</span><b>{{ $bankName ?: '—' }}</b></div>
    <div style="display:flex;justify-content:space-between;padding:3px 0"><span style="color:var(--tx3)">Số tiền</span><b style="color:var(--g)">{{ number_format($order->deposit,0,',','.') }}đ</b></div>
    <div style="display:flex;justify-content:space-between;padding:3px 0"><span style="color:var(--tx3)">Nội dung CK</span><b>{{ $note }}</b></div>
  </div>

  <div style="font-size:12px;color:var(--tx3);margin-top:10px;line-height:1.6">
    Chuyển khoản đúng nội dung <b>{{ $note }}</b>. Sau khi nhận cọc, shop xác nhận và xử lý đơn của bạn.
  </div>
</div>

<a href="{{ route('ctv.dashboard') }}" style="display:block;text-align:center;background:linear-gradient(135deg,var(--gd),var(--g));color:#fff;text-decoration:none;border-radius:12px;padding:13px;font-weight:800;margin-top:14px">
  ✓ Tôi đã chuyển cọc — Về bảng điều khiển
</a>

@endsection
