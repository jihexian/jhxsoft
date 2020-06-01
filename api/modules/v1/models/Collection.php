<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 2017/3/8
 * Time: 下午11:21
 */

namespace api\modules\v1\models;


use api\common\models\User;
use yii\helpers\ArrayHelper;

class Collection extends \common\models\Collection
{
   

    public function extraFields()
    {
        return [
            'product'
        ];
    }
}