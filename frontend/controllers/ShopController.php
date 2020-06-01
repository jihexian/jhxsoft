<?php

namespace frontend\controllers;
use Yii;
use frontend\common\controllers\Controller;
use common\models\Plugin;
use common\models\ShopSearch;
use common\models\Carousel;
use common\models\CarouselItem;
use yii\data\ActiveDataProvider;
use common\models\ProductSearch;
use api\modules\v1\models\ProductCategory;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use common\models\CollectionShop;
use common\models\Shop;
use yii\helpers\Url;
use common\models\OrderArrive;
use common\helpers\Tools;
use common\models\OrderArriveSearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
/**
 * Shop controller.
 */
class ShopController extends Controller
{
    public function behaviors()
    {
        return [
                'access' => [
                        'only' => ['arrive'],
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
     * 商店列表.
     *
     * @return mixed
     */
    public function actionLists()
    {
        $data=array();
        $data['status']=1;
        $data['apply_status']=1;
        //是否是扶贫商铺
        if(Yii::$app->request->get('fp')){
            $fp=Yii::$app->request->get('fp','');
        }else{
            $fp=Yii::$app->request->post('fp','');
        }
    
        if($fp!=''){
            $data['is_village']=$fp;
        }

        $data['name']=Yii::$app->request->get('name','');

        $searchModel = new ShopSearch();
        $dataProvider = $searchModel->search($data);
       $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        $model=$dataProvider->getModels();

        return $this->render('lists',[
             'model'=>$model,    
        ]);
    }
    
    public function actionCategory(){
        $query = ProductCategory::find()
        ->with('sons')
        ->andwhere(['shop_id'=>Yii::$app->request->get('shop_id')])
        ->andwhere(['parent_id'=>0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' =>50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'parent_id' => SORT_ASC,
                    'sort' => SORT_ASC
                ]
            ]
        ]);
        $cats = $dataProvider->getModels();
        return $this->render('category',[
            'cats'=>$cats,
        ]);
    }
    public function actionIndex()
    {
        Url::remember();
        $shop_id=Yii::$app->request->get('shop_id');
        
        //轮播图
        $carousel = Carousel::find()->where(['key'=>'index'])->one();
        $model=new CarouselItem();
        $carousels=$model->getCarousels($carousel->id,4,$shop_id);

        //店铺商品
        $data=yii::$app->request->post();
        $data['status']=1;
        $data['num']=10;
        $data['shop_id']=$shop_id;
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $sort->defaultOrder = array('hot'=>SORT_DESC,'up_time'=>SORT_DESC);
        $dataProvider->setSort($sort);
        $products = $dataProvider->getModels();
        $pageCount = $dataProvider->getPagination()->pageCount;
        
        $shop=Shop::find()->where(['id'=>$shop_id])->asArray()->one();
        $member_id = Yii::$app->user->id;
        if (! empty($member_id)) {
            $collection = CollectionShop::find()->where(array(
                    'member_id' => $member_id,
                    'shop_id' => $shop_id
            ))->one();
            $isFavorite = empty($collection) ? 0 : 1;
        } else {
            $isFavorite = 0;
        }
        return $this->render('index',[
            'carousels'=>$carousels,
            'products'=>$products,
            'pagecount'=>$pageCount,
            'isFavorite'=>$isFavorite,
            'shop'=>$shop
        ]);
    }
    /*店铺详情*/
    public function actionDetail(){
        $shop_id=Yii::$app->request->get('shop_id');
        $shop=Shop::find()->where(['id'=>$shop_id])->one();
        $member_id = Yii::$app->user->id;
        if (! empty($member_id)) {
            $collection = CollectionShop::find()->where(array(
                    'member_id' => $member_id,
                    'shop_id' => $shop_id
            ))->one();
            $isFavorite = empty($collection) ? 0 : 1;
        } else {
            $isFavorite = 0;
        }
        return $this->render('detail',[
            'isFavorite'=>$isFavorite,
            'shop'=>$shop
        ]);
    }

    
    public function actionArrive(){
        $model = new OrderArrive();
        $shop_id=Yii::$app->request->get('id');
        $shop=Shop::find()->where(['id'=>$shop_id])->one();
        if(empty($shop)){
           exit('页面不存在');
        }
        $payment=Plugin::find()->select(['id','name'])->indexBy(['name'])->where(['and',['type'=>'payment','status'=>1,'scene'=>2],['!=','id','money']])->asArray()->all();
        if(yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            $model->order_no='ar_'.Tools::get_order_no();
            $model->m_id=yii::$app->user->id;
            $model->shop_id=$shop_id;
            if ($model->save()){
                $this->redirect(['payment/arrive','payment_code'=>$model->payment_code,'id'=>$model->id]);
            } else {
                yii::$app->session->set('error','操作失败'); 
                $this->goBack();
            }
        }
        
        $searchModel = new OrderArriveSearch();
        $con=array();
        $con['num']=8;
        $con['m_id']=yii::$app->user->id;
        $con['payment_status']=1;
        $con['shop_id']=$shop_id;
        $dataProvider = $searchModel->search($con);
        $log=$dataProvider->getModels();
         return $this->render('arrive', [
                    'model' => $model,
                    'payment'=>$payment,
                    'shop'=>$shop,
                    'log'=>$log
            ]);
    }

}
