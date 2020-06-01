<?php
/**

 */

namespace api\modules\v1\models;


use yii\helpers\ArrayHelper;
use yii;
class FlashSale extends \common\modules\promotion\models\FlashSale
{

	

    public function extraFields()
    {
        return [        	
            'skus',
        	'product',        	
        ];
    }
    
	
}