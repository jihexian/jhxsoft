<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 2017/3/8
 * Time: 下午11:19
 */

namespace api\modules\v1\controllers;


use api\common\controllers\Controller;
use api\modules\v1\models\Comment;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;

class CommentController extends Controller
{

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
				[
						'class' => QueryParamAuth::className(),
						'tokenParam' => 'token',
						'optional' => [
								'index',								
						]
				]
		]);
	}

    /**
     * @api {get} /v1/comments 评论列表
     * @apiVersion 1.0.0
     * @apiName index
     * @apiGroup Comment
     *
     * @apiParam {Integer} entity_id 实体ID.
     * @apiParam {String} entity  实体
     *
     */
    public function actionIndex($entity='common\models\Article', $entity_id,$num=4)
    {	
    	$entity = 'common\models\Article';       
        $query = Comment::find()->where(['entity' => $entity, 'entity_id' => $entity_id, 'yj_comment.status' => 1, 'parent_id' => 0]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
				'pageSize' =>$num,
			],
            'sort' => [
                'defaultOrder' => [
                    'is_top' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ]
        ]);
    }
    
	public function actionCreate()
    {	 
        $model = new Comment();
        $data = \Yii::$app->request->post();
        $data['entity'] = 'common\models\Article';       
        $model->load($data,'');
        if ($model->save()) {
            return ['status' => 1, 'message' => '评论成功'];
        } else {
            return ['status' => 0, 'message' => '评论失败','error'=>$model->getErrors()];
        }
    }
}