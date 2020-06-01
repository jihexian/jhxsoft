<?php

namespace plugins\weixin;

use yii\base\Event;

class Plugin extends \plugins\Plugin
{
    public $info = [
        'author' => 'wsyone',
        'version' => 'v1.0',
        'id' => 'weixin',
        'name' => '微信支付',
        'description' => '微信公众号支付',
        'type'=>'payment',
        'code'=>'Weixin',
        'scene'=>2,
    ];
  
}