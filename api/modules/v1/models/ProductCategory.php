<?php
/**
 * 店铺分类
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2019年9月17日上午10:09:32
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;


use yii;
use yii\helpers\ArrayHelper;
use common\helpers\Util;
class ProductCategory extends \common\models\ProductCategory
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