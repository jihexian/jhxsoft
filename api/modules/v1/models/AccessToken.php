<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/2/25
 * Time: 下午2:36
 */

namespace api\modules\v1\models;


use yii\helpers\ArrayHelper;
use yii;
use api\modules\v1\models\Category;
class Article1 extends \common\models\Article
{
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            'cover' => function ($model) {
                $origin= ArrayHelper::getValue($model, 'cover.url', '');
				$thumb=ArrayHelper::getValue($model, 'cover.thumbImg', '');
				return Yii::$app->params['domain'].$thumb;  //返回缩略图
            },
			'originImage'=> function ($model) {return ArrayHelper::getValue($model, 'cover.url', '');}
        ]);
    }

    public function extraFields()
    {
        return [
            'data'
        ];
    }
	public function getThumb($filename){
	  $model=new Category();
	  $data=$model->getOne($cid);
	  $pathArr=explode(".",$filename);
	  $thumb=$pathArr[0]."_".$data['width']."_".$data['height'].".".$pathArr[1];
	  return $thumb;
	}
	
}