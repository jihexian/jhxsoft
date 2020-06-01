<?php
/**
 * 
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2020年2月26日下午12:23:34
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;


class Address extends \common\models\Address
{
    public function fields()
    {
        
        return array_merge(parent::fields(),[
            'provinceName'=>function($item){
            return $item->province->name;
            },
            'cityName'=>function($item){
            return $item->city->name;
            },
            'countyName'=>function($item){
            return $item->county->name;
            }
            ]
            );
    }
    
}