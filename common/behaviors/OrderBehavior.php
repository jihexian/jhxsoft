<?php
/*
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-09-27 16:38
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
use yii\base\Behavior;
use yii\db\ActiveRecord;

class OrderBehavior extends Behavior
{

    public function events()
    {
        return [

            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }
    //訂單新增，添加order_action一条记录
    public function afterSave(){
        $userId = Yii::$app->user->id;

    }

}
