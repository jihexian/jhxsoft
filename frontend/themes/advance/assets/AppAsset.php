<?php

namespace frontend\themes\advance\assets;

use yii\web\AssetBundle;


class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/themes/advance/static';
    public $css = [
        'icon/iconfont.css',
        'css/weui.min.css',
        'css/jquery-weui.css',    
        'css/flex.css',
        'css/reset.css',
        'css/style.css', 
        'css/common.css', 
    ];
    public $js = [
        'js/rem.js',
        'js/jquery.lazyload.min.js',
        'js/jquery-weui.min.js',
        'js/swiper.min.js',
        'js/city-picker.min.js',
        'js/jquery.md5.js',
    	'js/js.js',
        'js/jweixin-1.4.0.js',
        'js/app.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',//使用基础的jquery.js 和yii.js
      // 'yii\bootstrap\BootstrapAsset',
       //rft 'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\FontAwesomeAsset',
        'common\assets\ModalAsset',
    ];
}
