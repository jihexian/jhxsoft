<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);
return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\common\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'api\modules\v1\models\Member',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
        ],
		//restfull API url格式简化配置
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing'=>false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/article',
                        'v1/nav',
                        'v1/user',
                        'v1/comment',
						'v1/xcx-member', //小程序
						'v1/wx-member',  //公众号
                    	'v1/<controller:\w+>/<id:\d+>/'=>'<controller>/<action>',
                    ],

                ],

            ],

        ],
        'request' => [
            'enableCookieValidation' => false,
			'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ],
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => '\api\modules\v1\Module'
        ],
        'v2' => [
            'class' => '\api\modules\v2\Module'
        ],
    ],
    'params' => $params
];
