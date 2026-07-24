<?php

return [
    'security' => [
        'force_https' => (bool) env('APP_FORCE_HTTPS', false),
        'headers_enabled' => (bool) env('SECURITY_HEADERS_ENABLED', true),
        'csp_enabled' => (bool) env('SECURITY_CSP_ENABLED', false),
        'content_security_policy' => env(
            'SECURITY_CSP',
            "default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; img-src 'self' data: blob: https:; object-src 'none'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net; font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net; connect-src 'self' https:;"
        ),
    ],

    'rate_limits' => [
        'contact_per_minute' => (int) env('RATE_LIMIT_CONTACT_PER_MINUTE', 5),
        'admissions_per_minute' => (int) env('RATE_LIMIT_ADMISSIONS_PER_MINUTE', 40),
        'student_login_per_minute' => (int) env('RATE_LIMIT_STUDENT_LOGIN_PER_MINUTE', 5),
        'teacher_login_per_minute' => (int) env('RATE_LIMIT_TEACHER_LOGIN_PER_MINUTE', 5),
        'fee_challan_per_minute' => (int) env('RATE_LIMIT_FEE_CHALLAN_PER_MINUTE', 10),
    ],

    'notifications' => [
        'contact_recipient' => env('CONTACT_NOTIFICATION_EMAIL', env('MAIL_FROM_ADDRESS')),
        'admissions_recipient' => env('ADMISSIONS_NOTIFICATION_EMAIL', env('MAIL_FROM_ADDRESS')),
        'student_support_email' => env('STUDENT_SUPPORT_EMAIL', env('MAIL_FROM_ADDRESS')),
        'send_student_welcome_email' => (bool) env('SEND_STUDENT_WELCOME_EMAIL', true),
    ],

    'logs' => [
        'activity_retention_days' => (int) env('ACTIVITY_LOG_RETENTION_DAYS', 60),
    ],
];
