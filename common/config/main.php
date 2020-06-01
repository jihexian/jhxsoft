<?php

return [
    'vendorPath' => '@root/vendor',
    'runtimePath' => '@app/runtime',
    'timezone' => 'PRC',
    'language' => 'zh-CN',
    'bootstrap' => [
        'log',
        'common\\components\\LoadModule',
        'common\\components\\LoadPlugin',
        'queue',
    ],
    'components' => [
        'redis' => [
            'class' => \yii\redis\Connection::class,
            // ...
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@root/cache',
            'dirMode' => 0777 // 防止console生成的目录导致web账户没写权限
        ],
        'queue' => [
            'class' => 'yii\queue\redis\Queue',
            'as log' => \yii\queue\LogBehavior::class,//错误日志 默认为 console/runtime/logs/app.log
            'redis' => 'redis', // 连接组件或它的配置
            'channel' => 'queue', // Queue channel key
            
        ],
        'formatter' => [
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm',
            'timeFormat' => 'HH:mm',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        'log' => [
            'targets' => [
                'db'=>[
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['warning', 'error'],
                    'except'=>['yii\web\HttpException:*', 'yii\i18n\I18N\*'],
                    'prefix'=>function () {
                        $url = !Yii::$app->request->isConsoleRequest ? Yii::$app->request->getUrl() : null;
                        return sprintf('[%s][%s]', Yii::$app->id, $url);
                    },
                    'logVars'=>[],
                    'logTable'=>'{{%system_log}}'
                ],
            ]
        ],
        'notify' => 'common\components\notify\Handler',
        'moduleManager' => [
            'class' => 'common\\components\\ModuleManager'
        ],
        'pluginManager' => [
            'class' => 'common\components\PluginManager',
        ],


]];
