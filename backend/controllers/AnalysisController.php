<?php

namespace backend\controllers;

use Yii;
use common\models\Order;
use common\models\OrderSearch;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\logic\AnalysisLogic;
use yii\data\ArrayDataProvider;
use common\models\DistributLogSearch;
use common\models\Member;
use common\models\MemberSearch;
use common\models\ShopSearch;
use common\models\ShopCommissionLogSearch;
use yii\data\Pagination;
use common\models\Shop;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use common\models\Village;
use common\models\Product;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class AnalysisController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   
                ],
            ],
        ];
    }
    

    /**
     * 销售统计
     * @return mixed
     */
    public function actionIndex()
    {
    	$params = Yii::$app->request->queryParams;
    	isset($params['type'])? $type = $params['type']:$type = 1;
    	$data = array();
    	!empty($params['beginTime'])? $data['beginTime']=$params['beginTime']:$data['beginTime'] =date('Y-m-d',strtotime("-7 days"));
    	!empty($params['endTime'])? $data['endTime']=$params['endTime']:$data['endTime'] = date('Y-m-d',time()); 
    	$params['beginTime'] = $data['beginTime'];
    	$params['endTime'] = $data['endTime'];
    	$params['type'] = $type;    	 
    	$params['beginTimeCn'] = $this->setDate($data['beginTime']);
    	$params['endTimeCn'] = $this->setDate($data['endTime']);
        $analysisLogic = new AnalysisLogic();
        $resultAmount = $analysisLogic->getOrderAmount($type, $data);
        $resultCount = $analysisLogic->getOrderCount($type,$data);
        $resutl = array();
        $result['orderAmount'] = $resultAmount['orderAmount'];
        $result['orderCount'] = $resultCount['orderCount'];
        $result['labels'] = $resultAmount['labels'];
        return $this->render('index', [
        	'result'=>$result,
        	'params'=>$params,
        ]);
        
    }
    /**
     * 退款统计
     * @return mixed
     */
    public function actionRefund()
    {
        $params = Yii::$app->request->queryParams;
        isset($params['type'])? $type = $params['type']:$type = 1;
        $data = array();
        !empty($params['beginTime'])? $data['beginTime']=$params['beginTime']:$data['beginTime'] = date('Y-m-d',strtotime("-7 days"));
        !empty($params['endTime'])? $data['endTime']=$params['endTime']:$data['endTime'] = date('Y-m-d',time());
        $params['beginTime'] = $data['beginTime'];
        $params['endTime'] = $data['endTime'];
        $params['type'] = $type;
        $params['beginTimeCn'] = $this->setDate($data['beginTime']);
        $params['endTimeCn'] = $this->setDate($data['endTime']);
        
        $analysisLogic = new AnalysisLogic();
        $orderRefundAmount = $analysisLogic->getRefundAmount($type, $data);
        $orderRefundCount = $analysisLogic->getRefundCount($type, $data);
        $orderRefundUndoAmount = $analysisLogic->getRefundUndoAmount($type, $data);
        $orderRefundUndoCount = $analysisLogic->getRefundUndoCount($type, $data);
        
        $resutl = array();
        $result['orderRefundAmount'] = $orderRefundAmount['orderRefundAmount'];
        $result['orderRefundCount'] = $orderRefundCount['orderRefundCount'];
        $result['orderRefundUndoAmount'] = $orderRefundUndoAmount['orderRefundUndoAmount'];
        $result['orderRefundUndoCount'] = $orderRefundUndoCount['orderRefundUndoCount'];
        $result['labels'] = $orderRefundAmount['labels'];
        return $this->render('refund', [
            'result'=>$result,
            'params'=>$params,
        ]);
        
    }

    /**
     * 会员统计
     * @return mixed
     */
    public function actionMember()
    {
    	$params = Yii::$app->request->queryParams;
    	isset($params['type'])? $type = $params['type']:$type = 1;
    	$data = array();
    	!empty($params['beginTime'])? $data['beginTime']=$params['beginTime']:$data['beginTime'] =date('Y-m-d',strtotime("-7 days"));
    	!empty($params['endTime'])? $data['endTime']=$params['endTime']:$data['endTime'] = date('Y-m-d',time());
    	$params['beginTime'] = $data['beginTime'];
    	$params['endTime'] = $data['endTime'];
    	$params['type'] = $type;
    	$params['beginTimeCn'] = $this->setDate($data['beginTime']);
    	$params['endTimeCn'] = $this->setDate($data['endTime']);
    	
    	$analysisLogic = new AnalysisLogic();
    	$result = $analysisLogic->getFollowCount(2, $data);
    	return $this->render('member', [
    			'result'=>$result,
    			'params'=>$params,
    	]);
    }
    /**
     * 分类统计
     * @return mixed
     */
    public function actionCategory()
    {
    	$params = Yii::$app->request->queryParams;
    	$data = array();
    	!empty($params['beginTime'])? $data['beginTime']=$params['beginTime']:$data['beginTime'] = date('Y-m-d',time());
    	!empty($params['endTime'])? $data['endTime']=$params['endTime']:$data['endTime'] = date('Y-m-d',time());
    	$params['beginTime'] = $data['beginTime'];
    	$params['endTime'] = $data['endTime'];
    	$params['beginTimeCn'] = $this->setDate($data['beginTime']);
    	$params['endTimeCn'] = $this->setDate($data['endTime']);
    	$analysisLogic = new AnalysisLogic();
    	$result = $analysisLogic->getCategoryCount($data);
    	$dataProvider = new ArrayDataProvider([
    			'allModels' => $result,
    			'pagination' => false,
    			'sort' => [
    					'attributes' => ['total','amount'],
    			],
    	]);
    	return $this->render('category', [
    			'result'=>$result,
    			'dataProvider' => $dataProvider,
    			'params'=>$params,
    	]);
    }
    /**
     * 单品统计
     * @return mixed
     */
    public function actionProduct()
    {
        $params = Yii::$app->request->queryParams;
        $data = array();
        !empty($params['beginTime'])? $data['beginTime']=$params['beginTime']:$data['beginTime'] =date('Y-m-d',strtotime("-7 days"));
        !empty($params['endTime'])? $data['endTime']=$params['endTime']:$data['endTime'] = date('Y-m-d',time());
        $params['beginTime'] = $data['beginTime'];
        $params['endTime'] = $data['endTime'];
          
        $params['beginTimeCn'] = $this->setDate($data['beginTime']);
        $params['endTimeCn'] = $this->setDate($data['endTime']);
       
        $query = Product::find();
        $query->andWhere(['>','create_at', strtotime($params['beginTime'])]);
        $query->andWhere(['<','create_at', strtotime($params['endTime'])]);
        $query->andFilterWhere([
            'status'=>1,//发布
            'is_del'=>0,//未删除
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sale'=>SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' =>15,
                'validatePage'=>false,
            ],
        ]);
        return $this->render('product', [
            'dataProvider' => $dataProvider,
            'params'=>$params,
        ]);
    }
    
    private function setDate($date){
    	$paramstr = explode('-', $date);
    	$return = intval($paramstr[0]).'年'.intval($paramstr[1]).'月'.intval($paramstr[2]).'日';
    	return $return;
    }
    /**
     *分销抽成统计
     */
    public function actionDistribut()
    {
        $data=yii::$app->request->get();
        $data['is_distribut']=1;
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search($data);
        return $this->render('distribut', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * 平台抽成统计
     */
    public function actionShopCommission()
     {
         $params = Yii::$app->request->queryParams;
         $data = yii::$app->request->get();
         !empty($params['beginTime'])? $data['beginTime']=$params['beginTime']:$data['beginTime'] = date('Y-m-d',time());
         !empty($params['endTime'])? $data['endTime']=$params['endTime']:$data['endTime'] = date('Y-m-d',strtotime("+1 day"));
         $params['beginTime'] = $data['beginTime'];
         $params['endTime'] = $data['endTime'];
         $params['beginTimeCn'] = $this->setDate($data['beginTime']);
         $params['endTimeCn'] = $this->setDate($data['endTime']);
         
          $query = Shop::find()->alias('p')->joinWith('category as c')
         ->joinWith(['shopcommissionlog as s'=>function($query){
                     $data=yii::$app->request->get();
                     if(!empty($data['beginTime'])){
                     $beginTime=strtotime($data['beginTime']);
                     $endTime=strtotime($data['endTime']);
                     $query->where(['between','s.updated_at',$beginTime,$endTime]);
                 }
            }])
            ->select(['p.*','sum(s.percentage) as total'])
            ->groupBy('p.id'); 

         //$whereCondition = array();
         //$data['beginTime']? $whereCondition[['between','s.updated_at',$beginTime,$endTime]]=1:"";
         
/*           $query = Shop::find()
         ->alias('p')
         ->joinWith('category as c')
         ->joinWith(['shopcommissionlog as s'])
         ->select(['p.*','sum(s.percentage) as total'])
         ->groupBy('p.name'); */

         
            
         if(isset($data['name'])){
             $query->andFilterWhere(['like', 'p.name', $data['name']]);
         }
          $dataProvider= new ActiveDataProvider([
               'query' => $query,
               'pagination' => [
                       'pageSize' => 15,
               ],
            ]);
          return $this->render('shop_commission', [
             'dataProvider' => $dataProvider,
                  'params'=>$params
             ]);  
     }
     /**
      * 扶贫村点抽成
      */
     public function actionVillageCommission()
     {
         $params = Yii::$app->request->queryParams;
         $data = yii::$app->request->get();
         !empty($params['beginTime'])? $data['beginTime']=$params['beginTime']:$data['beginTime'] = date('Y-m-d',time());
         !empty($params['endTime'])? $data['endTime']=$params['endTime']:$data['endTime'] = date('Y-m-d',strtotime("+1 day"));
         $params['beginTime'] = $data['beginTime'];
         $params['endTime'] = $data['endTime'];
         $params['beginTimeCn'] = $this->setDate($data['beginTime']);
         $params['endTimeCn'] = $this->setDate($data['endTime']);
         
         $query = Village::find()->alias('v')
         ->joinWith(['villagecommissionlog as s'=>function($query){
             $data=yii::$app->request->get();
             if(!empty($data['beginTime'])){
                 $beginTime=strtotime($data['beginTime']);
                 $endTime=strtotime($data['endTime']);
                 $query->where(['between','s.updated_at',$beginTime,$endTime]);
             }
         }])
         
         ->select(['v.*','sum(s.percentage) as total'])
         ->groupBy('v.id');
         
         
         if(isset($data['name'])){
             $query->andFilterWhere(['like','v.name',$data['name']]);
         }
         $dataProvider= new ActiveDataProvider([
                 'query' => $query,
                 'pagination' => [
                         'pageSize' => 15,
                 ],
                 'sort' => [
                         'defaultOrder' => [
                                 'sort'=>SORT_ASC,
                                 'id' => SORT_ASC,
                         ],
                 ]
         ]);
         return $this->render('village_commission', [
                 'dataProvider' => $dataProvider,
                 'params'=>$params
         ]);  
     }
     //用户扶贫统计
     public function actionMemberVillageCommission(){
         $params = Yii::$app->request->queryParams;
         $data = yii::$app->request->get();
         !empty($params['beginTime'])? $data['beginTime']=$params['beginTime']:$data['beginTime'] = date('Y-m-d',time());
         !empty($params['endTime'])? $data['endTime']=$params['endTime']:$data['endTime'] = date('Y-m-d',strtotime("+1 day"));
         $params['beginTime'] = $data['beginTime'];
         $params['endTime'] = $data['endTime'];
         $params['beginTimeCn'] = $this->setDate($data['beginTime']);
         $params['endTimeCn'] = $this->setDate($data['endTime']);
         
         $query = Member::find()->alias('m')
         ->joinWith(['villagecommissionlog as v'=>function($query){
             $data=yii::$app->request->get();
             if(!empty($data['beginTime'])){
                 $beginTime=strtotime($data['beginTime']);
                 $endTime=strtotime($data['endTime']);
                 $query->where(['between','m.updated_at',$beginTime,$endTime]);
             }
         }])
         
         ->select(['m.*','sum(v.percentage) as total'])
         ->groupBy('m.id');
         
         
         if(isset($data['name'])){
             $query->andFilterWhere(['like','m.username',$data['name']]);
         }
         $dataProvider= new ActiveDataProvider([
                 'query' => $query,
                 'pagination' => [
                         'pageSize' => 15,
                 ],
                 'sort' => [
                         'defaultOrder' => [
                                 'total' => SORT_DESC,
                         ],
                 ]
         ]);
         return $this->render('member_village_commission', [
                 'dataProvider' => $dataProvider,
                 'params'=>$params,
         ]);  
     }
      
}
