<?php
namespace plugins\statistics;
use yii\base\BootstrapInterface;
use yii\web\View;
use yii\base\Event;

class Plugin extends \plugins\Plugin implements BootstrapInterface
{
    public $info = [
        'author' => 'wsyone',
        'version' => 'v1.0',
        'id' => 'statistics',
        'name' => '第三方统计',
        'description' => '网站流量统计'
    ];

    public function bootstrap($app)
    {
        Event::on(View::className(), 'endBody', [$this, 'run']);
    }

    public function run()
    {
        $config = $this->getConfig();


        echo $config['statistics_content'];
    }
}