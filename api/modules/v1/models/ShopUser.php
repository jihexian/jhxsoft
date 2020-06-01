<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/2/25
 * Time: 下午2:36
 */

namespace api\modules\v1\models;
use yii;
use yii\helpers\ArrayHelper;
class ShopUser extends \common\models\ShopUser
{

    public function extraFields()
    {
        return [
            'member'
        ];
    }
    public function fields(){
        return ArrayHelper::merge(parent::fields(),[
            'code'
        ]);
    }
    


	
}