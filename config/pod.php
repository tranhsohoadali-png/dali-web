<?php

/*
 * Cổng seller POD chạy ở tên miền riêng (mặc định pod.tranhdali.vn): trang gốc "/" là cổng seller,
 * "/tho-in" là trang thợ in. Link cũ tranhdali.vn/pod chuyển 301 sang đây.
 * Máy cục bộ: đặt POD_DOMAIN=pod.localhost trong .env (trình duyệt tự trỏ *.localhost về 127.0.0.1).
 */
$domain = env('POD_DOMAIN', 'pod.tranhdali.vn');

return [
    'domain' => $domain,
    'url'    => env('POD_URL', 'https://' . $domain),
];
