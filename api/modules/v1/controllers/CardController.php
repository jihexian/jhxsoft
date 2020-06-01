<?php

namespace api\modules\v1\controllers;

use api\common\controllers\Controller;
use common\logic\CardLogic;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
class CardController extends Controller
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
   
    
    public function actionRecharge(){
        if (Yii::$app->request->isPost) {
            
            $mid = Yii::$app->user->id;
            $params = Yii::$app->request->post();
            $cardLogic = new CardLogic();
            $result = $cardLogic->recharge($mid, $params);
            
            return $result;
        }
    }
    
}
