<?php 
/**
 *地址管理
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月21日 下午12:22:52
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\controllers;
use yii;
use yii\base\Exception;
use frontend\common\controllers\Controller;
use common\models\Address;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\filters\AccessControl;
use common\models\Region;


class AddressController extends Controller{
   
    
    public function behaviors()
    {
        return [
                'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                                [
                                        'allow' => true,
                                        'actions' => ['index','add','delete','update','default'],
                                        'roles' => ['@'],
                                ],
                                
                        ],
                        
                ],
        ];
    }
    /**
     * 地址列表
     */
    public function actionIndex(){
        $type = Yii::$app->request->get('type'); //0为购物车购买 1为直接购买
        $id = Yii::$app->request->get('id');
       $query=Address::find()->where(['status'=>1,'uid'=>Yii::$app->user->id]);
       $data= new ActiveDataProvider([
            'query' => $query,
         /*    'pagination' => [
               'pageSize' => 20,
            ], */
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
       
      return  $this->render('index',[
           'data'=>$data->getModels(),
              'type'=>$type,
              'id'=>$id,
       ]);
       
        
    }
    
   /**
    * 
    *  添加收货地址
    */
    public function actionAdd(){
        $model = new Address();
        $data=Yii::$app->request->post();
        $province=Region::find()->where(['level'=>1])->asArray()->all();
     
        if($data){
            $mid=yii::$app->user->id;
            $address=Address::find()->where(['uid'=>$mid])->all();
            if(empty($address))
               $model->is_default=1; 
           
             $model->province_id=$data['province_id'];
             $model->city_id=$data['city_id'];
             $model->region_id=$data['region_id'];
            $model->uid=Yii::$app->user->id;
            $model->userName=$data['userName'];
            $model->detailInfo=$data['detailInfo'];
            $model->telNumber=$data['telNumber'];
            
            if ($model->save($data)) {
               $arr=array('status'=>1,'msg'=>'操作成功');         
            }else{
                $arr=array('status'=>0,'msg'=>current($model->getErrors()));
           
            }
            return Json::encode($arr);
           
        }else {
            return $this->render('add', [
                'model' => $model,
                'province'=>$province
                
            ]);
        }
    }
    
    /**
     * 删除纪录
     * @param  $id
     */
    public function actionDelete($id){
        $uid=Yii::$app->user->id;
        $model = Address::find()->where(['id'=>$id,'uid'=>$uid])->one();
        if($model['is_default']==1){
            $arr=array('status'=>0,'msg'=>'默认地址无法删除');
            return json_encode($arr);
        }
        if($model->delete()){
            $arr=array('status'=>1,'msg'=>'操作成功');
        }else{
            $arr=array('status'=>0,'msg'=>'操作失败');
        }
        return json_encode($arr);
    }
    /**
     * 更新纪录
     * @param  $id
     * @return string
     */
    public function actionUpdate($id){
        $model = Address::find()->where(['id'=>$id,'uid'=>\Yii::$app->user->id])->one();
        $data=Yii::$app->request->post();
        $province=Region::find()->where(['level'=>1])->asArray()->all();
        if($data){
            
            $model->province_id=$data['province_id'];
            $model->city_id=$data['city_id'];
            $model->region_id=$data['region_id'];
            $model->userName=$data['userName'];
            $model->detailInfo=$data['detailInfo'];
            $model->telNumber=$data['telNumber'];
            
            if ($model->save($data)) {
                $arr=array('status'=>1,'msg'=>'操作成功');
            }else{
                $arr=array('status'=>0,'msg'=>json_encode($model->errors));
            }
            return json_encode($arr);
            
        }else {
  
            return $this->render('update', [
                'data'=>$model,
                'province'=>$province
            ]); 
        }
    }
    
    public function actionDefault(){
        $id=Yii::$app->request->post('id');
        $transaction = Yii::$app->db->beginTransaction();
        $uid=Yii::$app->user->id;
        try{
            $model = Address::find()->where(['id'=>$id,'uid'=>$uid])->one();
         $model->is_default=1;
         $model->save();
         $data = Address::updateAll(['is_default' => 0], ['and','uid='.$uid,['<>','id',$id]]);
         if($model->hasErrors()||!$data){
             $transaction->rollBack();
             $arr=array('status'=>0,'msg'=>'操作失败');
         }
         $transaction->commit(); 
         $arr=array('status'=>1,'msg'=>'操作成功');
         return json_encode($arr);
        }catch (Exception $e) {
         $arr=array('status'=>0,'msg'=>'操作失败');
         return json_encode($arr);
        $transaction->rollBack();
    } 
    }
    
   
}
