<?php
/**
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-05-29 10:07
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use api\modules\v1\models\Address;
use common\models\Region;
use yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
class AddressController extends Controller
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
	 * 
	 */

	public function actionInfo(){

       $uid=Yii::$app->user->id;
		$addr=Address::find()->where('uid='.$uid)->one();
	  if($addr){
		  return ['status'=>1,'msg'=>'操作成功','items'=>$addr];
           // return $addr;
        }else{
		    return ['status'=>0,'msg'=>'操作失败','items'=>$addr];
        }

	}
  /**
   * $data=array('name'=>'姓名'，‘province'=>'省份/自治区’，'city'>'城市名称'，‘county'=>'县、区'）
   * @return number[]|string[]|boolean[]|\common\models\Address[]|NULL[]|number[]|string[]|\common\models\Address[]|array[]|NULL[]
   */
	public function actionUpdate(){
        $data = Yii::$app->request->post();
        $uid= Yii::$app->user->id;
      
        $addr=Address::find()->where(['and','uid='.$uid,'userName="'.$data['name'].'"'])->asArray()->one();
       if(empty($addr)){
           if($info=$this->updateData($data)) { 
               return ['status' => 1, 'msg' =>'操作成功','items'=>$info];
           }
        }elseif($addr['telNumber']!=$data['mobile']||$addr['detailInfo']!=$data['detail']){
            if($info=$this->updateData($data,$addr['id'])){ 
               return ['status'=>1,'msg'=>'操作成功','items'=>$info];
            }

        }else{
           return ['status'=>1,'msg'=>'操作成功','items'=>$addr];
       }
    }

     private  function updateData($data,$id=0){
        if($id!=0){//
            $addr = Address::findOne($id);
            $addr->delete();
        }
        $province_id=Region::find()->where(['name'=>$data['province']])->orderBy(['id' => SORT_ASC])->scalar();
        $city_id=Region::find()->where(['name'=>$data['city'],'parent_id'=>$province_id])->orderBy(['id' => SORT_ASC])->scalar();
        $region_id=Region::find()->where(['name'=>$data['county'],'parent_id'=>$city_id])->orderBy(['id' => SORT_ASC])->scalar();
         $address=new Address;
         $address->uid=Yii::$app->user->id;
         $address->userName=$data['name'];
         $address->telNumber=$data['mobile'];
         $address->province_id=$province_id;
         $address->city_id=$city_id;
         $address->region_id=$region_id;
         $address->detailInfo=$data['detail'];
         if(!$address->save()){
             return false;
         }else{
             $info= Address::findOne($address->id);
             return $info;
         }
    }

}
