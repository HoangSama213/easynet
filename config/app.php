<?php

return [
    'name' => 'EasyNet Data',
    'base_url' => getenv('APP_URL') ?: 'http://localhost/easynet',
    'timezone' => 'Asia/Ho_Chi_Minh',
    'session' => 'easynet_session',
    'groups' => [
        'he-sinh-thai' => 'Hệ sinh thái',
        'ncc' => 'NCC',
        'hang-muc' => 'Hạng mục',
        'san-pham-ncc' => 'Sản phẩm NCC',
        'thuong-hieu-phan-phoi' => 'Thương hiệu phân phối',
        'san-pham-chi-tiet' => 'Sản phẩm chi tiết',
    ],
    'roles' => [
        'admin' => 'Quản trị viên',
        'editor' => 'Biên tập viên',
        'viewer' => 'Người xem',
    ],
];
