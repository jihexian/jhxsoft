<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => env('FRONTEND_COOKIE_VALIDATION_KEY')
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/detail',
                #'<controller:\w+>/<action:\w+>/<payment_code:\w+>'=>'<controller>/<action>',
                'response/notify/<payment_code:\w+>'=>'response/notify',
                'response/return/<payment_code:\w+>'=>'response/return',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                
            ]
        ]
    ],
];
return $config;
