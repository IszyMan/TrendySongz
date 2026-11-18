<?php

return [
    'audio_host' => env('CDN_AUDIO_HOST', '127.0.0.1'),
    'audio_user' => env('CDN_AUDIO_USER', 'cdntsnaijamtrend'),
    'audio_key' => env('CDN_AUDIO_KEY', '/home/tsnaijamtrend/.ssh/trendysongz_cdn_automation'),
    'audio_root' => env('CDN_AUDIO_ROOT', '/home/cdntsnaijamtrend/public_html/audio'),
    'audio_url' => env('CDN_AUDIO_URL', 'https://cdn.trendysongz.com/audio'),
];