<?php

/**
 * Cấu hình liên kết CTV chéo với site anh em tomau.tranhdali.vn.
 * Khách đến từ link CTV tomau (?tref=<mã>) mua tranh bên tranhdali.vn -> báo
 * đơn về cổng tomau để trả hoa hồng cho CTV đó. token là bí mật CHUNG 2 bên
 * (đặt trong .env, KHÔNG commit).
 */
return [
    // URL app tomau để đẩy webhook báo đơn sang.
    'url' => rtrim(env('TOMAU_APP_URL', 'https://tomau.tranhdali.vn'), '/'),

    // Bí mật chung — phải GIỐNG HỆT PARTNER_SHARED_SECRET trong .env của tomau.
    'token' => env('TOMAU_PARTNER_TOKEN', ''),

    // % hoa hồng cho đơn tranhdali đến từ CTV tomau (mặc định 10%).
    'rate' => (float) env('TOMAU_PARTNER_RATE', 10),

    // Bật/tắt gửi webhook (đặt =false để tạm dừng mà không sửa code).
    'enabled' => (bool) env('TOMAU_PARTNER_ENABLED', true),
];
