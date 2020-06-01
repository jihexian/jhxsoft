<?php
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use common\logic\CommentLogic;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;


class ProductCommentController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                    'test',
                ]
            ]
        ]);
    }


    /**
     * 添加评论
     */
    public function actionAdd()
    {

        $uid = Yii::$app->user->id;
        $data = Yii::$app->request->post();
        $data['uid']=$uid;
        $commentLogic = new CommentLogic();
        
        return $commentLogic->addComment($data);
       
    }

}