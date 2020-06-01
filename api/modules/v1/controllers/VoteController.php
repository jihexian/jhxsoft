<?php
/**
 * User: vamper
 * 
 */

namespace api\modules\v1\controllers;

use yii;
use api\common\controllers\Controller;
use api\modules\v1\models\Comment;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use common\models\VoteInfo;
use api\modules\v1\models\Vote;

class VoteController extends Controller
{

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
				[
						'class' => QueryParamAuth::className(),
						'tokenParam' => 'token',
						'optional' => [
															
						]
				]
		]);
	}

    
	public function actionAdd()
    {
        $userId = Yii::$app->user->id;
        $id = \Yii::$app->request->post('id');
        $entity = \Yii::$app->request->post('entity', 'common\models\Article');
        $action = 'up';
        $actions = ['up', 'down'];
        array_splice($actions, array_search($action, $actions), 1);
        $oppositeAction = current($actions);
        $vote = Vote::find()->where(['entity_id' => $id, 'entity' => $entity, 'user_id' => $userId])->one();
        if (empty($vote)) {
            $vote = new Vote();
            $params = [
                'entity' => $entity,
                'action' => $action,
                'entity_id' => $id,
                'user_id' => $userId,
            ];
            $vote->attributes = $params;
            $vote->save();
            VoteInfo::updateAllCounters([$action => 1], ['entity' => $entity, 'entity_id' => $id]);
            return ['status'=>1,'message'=>'点赞成功','up' => (int)VoteInfo::find()->where(['entity' => $entity, 'entity_id' => $id])->select('up')->scalar()];
        }else {
        	$vote->delete();
    		VoteInfo::updateAllCounters([$action => -1], ['entity' => $entity, 'entity_id' => $id]);
    		return ['status'=>2,'message'=>'取消成功','up' => (int)VoteInfo::find()->where(['entity' => $entity, 'entity_id' => $id])->select('up')->scalar()];
        }
    }
    public function actionLists($num=4){
    	$userId = Yii::$app->user->id;
    	$entity = \Yii::$app->request->post('entity', 'common\models\Article');
    	$query = Vote::find()->where(['entity' => $entity, 'user_id' => $userId]);
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    				'defaultOrder' => [
    					'created_at' => SORT_DESC
    				]
    			],
    			'pagination' => [
    					'pageSize' =>$num,
    			],
    	]);
    	return $dataProvider;   	
    	
    }
    
    
}