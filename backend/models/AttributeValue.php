<?php
/**
 * 
 */

namespace backend\models;
use yii\helpers\ArrayHelper;
use yii;
class AttributeValue extends \common\models\AttributeValue
{
    public function rules(){
    	$rules = parent::rules();
    	unset($rules['value_str']);
    	return $rules;
    }
	
}