<?php
/**
 * 
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2019年9月17日上午10:11:07
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;
use yii;
use yii\helpers\ArrayHelper;
class ProductType extends \common\models\ProductType
{
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            
            'sons'=>function ($model) {
            return empty($model->sons)?null : $model->sons;  //标签
            },
            
            ]);
    }
    
    
    public function extraFields()
    {
        return [
            'sons',
        ];
    }
}