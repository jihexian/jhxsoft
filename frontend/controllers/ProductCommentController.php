<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年6月5日下午5:50:34
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\controllers;

use Yii;

use frontend\common\controllers\Controller;
use common\models\OrderSku;
use common\models\Order;
use common\models\ProductComment;
use common\logic\OrderLogic;
use common\models\DistributLog;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\filters\AccessControl;
use common\logic\DistributeLogic;
use common\logic\ShopCommissionLogic;
use common\logic\VillageCommissionLogic;
use common\models\Shop;

/**
 * ProductComment controller.
 */
class ProductCommentController extends Controller
{
    public function behaviors()
    {
        return [
                'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                                [
                                        'allow' => true,
                                        'roles' => ['@'],
                                ],
                        ],
                ],
        ];
    }
    /**
     * 添加评论
     */
    public function actionAdd()
    {
        $uid=Yii::$app->user->id;
        if(Yii::$app->request->post()){
          
            $data = Yii::$app->request->post();
            unset($data['shop_id']);

            $logic=new OrderLogic();
            $flag=$logic->Comment($data['id'], $uid, $data);
            return Json::encode($flag);

            
        }else {
            
            $id=Yii::$app->request->get('id');
            
            $model=OrderSku::find()->alias('os')
            ->joinWith('order o')
            ->andwhere(['os.id'=>$id,'os.is_comment'=>0])
            ->andwhere(['o.m_id'=>$uid,'o.status'=>4])
            ->asArray()
            ->one();

            if (empty($model)){
                Yii::$app->session->setFlash('flag', 'error');
                return $this->redirect(['list']);
            }
            //         print_r($model);
            return $this->render('add',[
                    'model'=>$model,
            ]);
        }
    }
    /**
     * 列表
     * @return string
     */
    public function actionList()
    {   
        $uid=Yii::$app->user->id;
        $order_id=Yii::$app->request->get('order_id');
        $order_sku=OrderSku::find()->alias('os')->joinWith('order o')
        ->andwhere(['o.status'=>4,'o.m_id'=>$uid,'o.id'=>$order_id])
        ->andWhere(['os.is_comment'=>0])
        ->asArray()->orderBy('id desc')->all();
        if(empty($order_sku)){
            Yii::$app->getSession()->setFlash('error', '该订单不存在');
            $this->goback();
        }
        return $this->render('list',[
        'model'=>$order_sku     
        ]);
    }
    
}
