<?php

return [
    'asset_url' => env('APP_URL'),
    'app_url' => env('APP_URL'),
    'middleware_group' => ['web'],
    'temporary_file_upload' => [
        'disk' => 'public',
        'rules' => null,
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5,
    ],
    'manifest_path' => null,
    'back_button_cache' => false,
    'render_on_redirect' => false,
    'legacy_model_binding' => false,
    'inject_assets' => true,
    'inject_morph_markers' => true,
    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],
]; 