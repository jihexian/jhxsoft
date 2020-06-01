<?php


namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use common\models\Favourite;
use common\models\Article;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;

class FavouriteController extends Controller
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
        $articleId = \Yii::$app->request->post('id', 0);   
        $article = Article::find()->where(['id' => $articleId])->normal()->one();
        if (empty($article)){
        	return ['status' => 0, 'message' => '文章不存在'];
        }
        
        $favourite = Favourite::find()->where(['user_id' => \Yii::$app->user->id, 'article_id' => $articleId])->one();       
        if (empty($favourite)) {
            $favourite = new Favourite();
            $favourite->user_id = \Yii::$app->user->id;
            $favourite->article_id = $articleId;
	        if ($favourite->save()){
	        	$article->updateCounters(['favourite' => 1]);
	        	return ['status' => 1, 'message' => '收藏成功'];
	        }else{
	        	return ['status' => 0, 'message' => '收藏失败','error'=>$favourite->getErrors()];
	        }		
        } else {        	
        	$favourite->delete();
        	$article->updateCounters(['favourite' => -1]);        	
        	return ['status' => 2, 'message' => '取消成功'];        	
        }
    }    
   
}
