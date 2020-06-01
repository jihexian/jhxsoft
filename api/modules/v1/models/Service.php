<?php
/**
 *
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2019年4月10日 下午5:24:59
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;


class Service extends \common\models\Service
{
    public function extraFields()
    {
        return [
                'orderSku',
                'shop'=>function($item){
                return [
                        'id'=>$item->shop->id,
                        'name'=>$item->shop->name,
                ];
                }
                ];
    }
    
}