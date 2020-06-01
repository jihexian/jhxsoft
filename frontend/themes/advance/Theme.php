<?php



namespace frontend\themes\advance;

use Yii;

class Theme extends \frontend\themes\Theme
{
    public $info = [
        'author' => 'wsyone',
        'id' => 'advance',
        'name' => '几何线系统高级版',
        'version' => 'v1.0',
        'description' => '手机主题',
        'keywords' => '手机经典'
    ];

    public function bootstrap()
    {
        Yii::$container->set('yii\bootstrap\BootstrapAsset', [
            'sourcePath' => '@frontend/themes/advance/static',
            'css' => [
                YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
            ]
        ]);
    }
}