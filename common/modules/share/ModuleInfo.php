<?php
/**
 *
 */

namespace common\modules\share;


use common\components\PackageInfo;

class ModuleInfo extends \common\modules\ModuleInfo
{
	//public $isCore = 1;
    public $info = [
        'author' => 'vamper',
        'bootstrap' => 'backend|api',
        'version' => 'v1.0',
        'id' => 'share',
        'name' => '分享模块',
        'description' => '分享模块'
    ];
}