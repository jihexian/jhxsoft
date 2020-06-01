<?php
/**
 * 
 */

namespace backend\models;
use yii\helpers\ArrayHelper;
use yii;
class Skus extends \common\models\Skus
{
    public function rules(){
    	$rules = parent::rules();
    	unset($rules['sku_id']);
    	return $rules;
    }
    
    public function afterFind(){
        
        
    }
    
}