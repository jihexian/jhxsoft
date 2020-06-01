<?php
/**
 * 
 */

namespace backend\models;
use yii\helpers\ArrayHelper;
use yii;
class Attribute extends \common\models\Attribute
{
    public function rules(){
    	$rules = parent::rules();
    	unset($rules['attribute_name']);
    	return $rules;
    }
	
}