<?php

/**
 * Cấu hình kết nối với app marketing (social-suite, https://agent.tranhdali.vn).
 * Token là bí mật chung 2 bên — đặt trong .env, KHÔNG commit.
 */
return [
    // Token bí mật để xác thực mọi request tích hợp (cả 2 chiều).
    'token' => env('INTEGRATION_TOKEN', ''),

    // URL app marketing để đẩy lead realtime sang.
    'agent_url' => rtrim(env('AGENT_APP_URL', 'https://agent.tranhdali.vn'), '/'),

    // Bật/tắt việc đẩy lead realtime (đặt =false để tạm dừng).
    'push_leads' => (bool) env('INTEGRATION_PUSH_LEADS', true),

    // Danh sách IP được phép gọi /api/integration/* (phẩy ngăn cách). Trống = mọi IP (chỉ dựa token).
    'ip_allowlist' => array_values(array_filter(array_map('trim', explode(',', (string) env('INTEGRATION_IP_ALLOWLIST', ''))))),
];
