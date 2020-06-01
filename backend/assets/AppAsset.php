<?php
namespace backend\assets;

use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 * @since 0.1
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@backend/static';
    public $css = [
        'css/AdminLTE.min.css',
        'css/site.css',
    	'css/daterangepicker.css',
        
    ];
    public $js = [
        'js/drag.js',
        'js/site.js',
    	'js/Chart.js',   
    	'js/moment.min.js',
    	'js/daterangepicker.zh-CN.js',
    	'js/jquery-ui-1.10.4.min.js',
    	'js/daterangepicker.js',
        'js/echarts.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'common\assets\ModalAsset',
        'common\assets\FontAwesomeAsset',
        'common\assets\FancyboxAsset',
        'backend\assets\SwitcherAsset'
    ];

    /**
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public $skin = '_all-skins';

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }

        parent::init();
    }
}