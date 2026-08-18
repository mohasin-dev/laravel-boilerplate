<?php

test('uses explicit application localization defaults', function () {
    expect(config('app.timezone'))
        ->toBe('UTC')
        ->and(config('app.locale'))
        ->toBe('en')
        ->and(config('app.fallback_locale'))
        ->toBe('en')
        ->and(config('app.faker_locale'))
        ->toBe('en_US')
        ->and(date_default_timezone_get())
        ->toBe('UTC');
});

test('documents the required environment configuration', function () {
    $environment = file_get_contents(base_path('.env.example'));

    expect($environment)->not->toBeFalse();

    foreach ([
        'APP_NAME',
        'APP_ENV',
        'APP_KEY',
        'APP_DEBUG',
        'APP_URL',
        'APP_TIMEZONE',
        'APP_LOCALE',
        'APP_FALLBACK_LOCALE',
        'DB_CONNECTION',
        'CACHE_STORE',
        'QUEUE_CONNECTION',
        'MAIL_MAILER',
        'FILESYSTEM_DISK',
        'SESSION_DRIVER',
        'SESSION_SECURE_COOKIE',
        'SESSION_HTTP_ONLY',
        'SESSION_SAME_SITE',
    ] as $key) {
        expect($environment)->toMatch("/^{$key}=.*$/m");
    }
});
