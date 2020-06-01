<?php
/**
 * Author: vamper  
 * Time: 2018-10-25
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\models\Region;
use yii\helpers\ArrayHelper;

class RegionLogic{
    public static function getRegions($id=null,$parent_id=null,$level=null){
        if (isset($id)) {

            $result = Region::find()->where(['id'=>$id])->asArray()->all();
        }else if (isset($parent_id)) {
            $result = Region::find()->where(['parent_id'=>$parent_id])->asArray()->all();
        }else{
            $result = [];
        }
        return ArrayHelper::map($result, "id", "name");
    }
	
}
