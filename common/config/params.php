<?php
return [

    'domain'=>env('SITE_URL'),  //api图片服务器地址
    'defaultImg' => array(
        'default' => env('SITE_URL').'/storage/upload/default.jpg',
    ),
    'user.passwordResetTokenExpire' => 3600,
    'webuploader_driver' => env('WEBUPLOADER_DRIVER', 'local'),
    'webuploader_qiniu_config' => [
        'domain' => env('WEBUPLOADER_QINIU_DOMAIN'),
        'bucket' => env('WEBUPLOADER_QINIU_BUCKET'),
        'accessKey' => env('WEBUPLOADER_QINIU_ACCESS'),
        'secretKey' => env('WEBUPLOADER_QINIU_SECRET'),
    ],	
	'auth_code'=>env('FRONTEND_COOKIE_VALIDATION_KEY'),
];

