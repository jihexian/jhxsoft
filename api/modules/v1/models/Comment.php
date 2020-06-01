<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 2017/3/8
 * Time: 下午11:21
 */

namespace api\modules\v1\models;


use yii\helpers\ArrayHelper;

class Comment extends \common\models\Comment
{

    public function extraFields()
    {
        return [
            'sons',  
        	'member'      	
        ];
    }
}