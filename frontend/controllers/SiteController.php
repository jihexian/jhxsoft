<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use frontend\common\controllers\Controller;
use common\models\Carousel;
use common\models\ProductSearch;
use common\models\CarouselItem;
use common\models\ProductType;
use common\models\Region;
use frontend\models\LoginForm;
use common\models\Product;
use frontend\models\RegisterForm;
use common\models\Shop;
use common\models\Village;
use common\logic\DistributeLogic;
use common\models\Nav as NavModel;
use yii\helpers\Json;

/**
 * Site controller.
 */
class SiteController extends Controller
{
	
	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
				'error' => [
						'class' => 'yii\web\ErrorAction',
				],
		        'sms'=>[
		            'class' => 'common\\actions\\SmsAction'
		        ]  
		    
		];
	}
    /**
         * 首页.
     *
     * @return mixed
     */

    public function actionIndex()
    {    

        //轮播图
        $carousel = Carousel::find()->where(array('key'=>'index'))->one();
        $model=new CarouselItem();
        $carousels=$model->getCarousels($carousel->id,4,1);

        //导航
        $types = NavModel::getItems('mobile_header');
        
        //商品搜索
        $searchModel = new ProductSearch();
  

        $news=Product::find()->alias('p')
        ->joinWith(['shop as s'])->where(['p.status'=>1,'p.is_new'=>1,'p.is_del'=>0,'s.status'=>1])->limit(6)->orderBy('sort asc,up_time desc')->all();
        $hot =Product::find()->alias('p')
        ->joinWith(['shop as s'])->where(['p.status'=>1,'p.is_top'=>1,'p.is_del'=>0,'s.status'=>1])->limit(6)->orderBy('sort asc,up_time desc')->all();
        

        //广告区
        $carousel = Carousel::find()->where(array('key'=>'guanggaoqu'))->one();      
        $ads=$model->getCarousels($carousel->id,4,1);


        
        //扶贫商家商品
        $product=Product::find()->alias('p')
        ->joinWith(['shop as s'])->where(['p.status'=>1,'s.is_village'=>1])
        ->limit(3)
        ->orderBy('hot desc,product_id desc')
        ->all(); 
        return $this->render('index',[
                'carousels'=>$carousels,
                'types'=>$types,
                'hots'=>$hot,
                'news'=>$news,
                'ads'=>$ads,
                'product'=>$product,
              
        ]);

    }
    
    public function actionRegister(){
        $model = new RegisterForm();
    	if(\Yii::$app->request->isPost){
    	    if ($model->load(Yii::$app->request->post())) {
    	        if (Yii::$app->request->isAjax) {
    	            Yii::$app->response->format = Response::FORMAT_JSON;    	            
    	            return ActiveForm::validate($model);
    	        }
    	        if ($user = $model->register()) {
    	            $pid=yii::$app->request->get('pid',yii::$app->session->get('pid'));
    	            if(!empty($pid)){
        	            $distribute=new DistributeLogic();
        	            $distribute->FristLeader($pid, $user['id']);
    	            }
    	            if (Yii::$app->getUser()->login($user)) {
    	                return $this->goHome();
    	            }
    	        }
    	    }
    	}
    	return $this->render('register', ['model' => $model]);
    }
	
    public function actionLogin(){
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new LoginForm();
        if(\Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
                if ($model->login()) {
                    return $this->goBack();
                }
            }
        }        
        return $this->render('login', [
            'model' => $model,
        ]);
        
    }
    

    public function actionLogout()
    {
        Yii::$app->user->logout();
        
        return $this->goHome();
    }
    

}
