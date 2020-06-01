<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 2017/4/14
 * Time: 下午11:33
 */

namespace api\modules\v1\models;
use yii\helpers\ArrayHelper;

class Category extends \common\models\Category
{
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [

            'data'=>function ($model) {
                return empty($model->data)?null : $model->data;  //标签
            },

        ]);
    }
	/*获取分类信息*/
	public function getOne($id){
        $category=(new \yii\db\Query())->from(self::tableName())->where(['id'=> $id,'status'=>1])->one();
        return $category;
	}

    public function extraFields()
    {
        return [
            'data',
        ];
    }
}