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
class Vote extends \common\models\Vote
{

	
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
//             'cover' => function ($model) {
// 				$thumb=ArrayHelper::getValue($model, 'cover.thumbImg', '');
// 				return empty($thumb)? Yii::$app->params['defaultImg']['default']:Yii::$app->params['domain'].'/storage/upload/'.$thumb;  //返回缩略图
//             }
				
        ]);
    }

    public function extraFields()
    {
        return [        	
            'article',        	
        ];
    }
    public function getArticle()
    {
    	return $this->hasOne(Article::className(), ['id' => 'entity_id']);
    }
	
}