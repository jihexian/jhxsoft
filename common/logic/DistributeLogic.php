<?php

namespace common\logic;

use yii;
use common\models\Distribut;
use common\helpers\Util;
use common\models\Member;
use common\models\DistributLog;
use yii\db\StaleObjectException;
use common\models\Order;
use yii\helpers\Json;

class DistributeLogic{

    /**
     * 有上级ID，注册后先插入一条记录
     * @param上级ID $pid
     * @param下级ID $cid
     */
    public function FristLeader($pid,$cid){
        //判断上级id是否正确
        $member=Member::find()->where(['id'=>$pid,'is_distribut'=>1])->one();
        $distribute_open=Yii::$app->config->get('distribute_open');
        if($member&&$distribute_open){
            $distribute=new Distribut();
            $distribute->level=1;
            $distribute->pid=$pid;
            $distribute->cid=$cid;
            $distribute->save();
            //查看是否还有上级
            $this->SecondLeader($cid,$pid);
        }
    
    }
    
    
    /**
     * 
     * @param $pid  
     * @param $cid  
     */
    public function bind($pid,$cid){
        if ($pid==$cid) {
            return ['status'=>0,'msg'=>'参数错误！'];
        }
        //判断pid是否已经开通分销商
        $member = Member::find()->where(['id'=>$pid,'is_distribut'=>1])->one();
        $distribute_open=Yii::$app->config->get('distribute_open');
        if(empty($member)||!$distribute_open){
            return ['status'=>0,'msg'=>'没有分销权限！'];
        }else{
            //判断本人是否已经有上级分销商
            $myRelation = Distribut::find()->where(['cid'=>$cid])->one();
            if (!empty($myRelation)) {
                return ['status'=>2,'msg'=>'用户已绑定关系，无法再绑定其他关系！'];;
            }
            $distribute=new Distribut();
            $distribute->level=1;
            $distribute->pid=$pid;
            $distribute->cid=$cid;
            if (!$distribute->save()) {
                return ['status'=>0,'msg'=>current($distribute->getFirstErrors())];
            }
            $this->SecondLeader($cid,$pid);
            return ['status'=>1,'msg'=>'绑定成功！'];
        }
    }
    

    /**
     * 判断有几个上级,插入level=2,3的上级id
     * @param int $cid 用户id
     * @param int $pid 分享者id
     * @return boolean
     */
    public function SecondLeader($cid,$pid){
        $data=Distribut::findAll(['cid'=>$pid,'level'=>[1]]);
        if($data){
            foreach ($data as $k=>$v){
                $distribute=new Distribut();
                $distribute->level=$v['level']+1;
                $distribute->pid=$v['pid'];
                $distribute->cid=$cid;
                $distribute->save();
            }
        }
        return TRUE;
    }
    
    /**
     * 下单后分销金额记录
     */
    public function money($user,$product,$value){
        //判断分销金额是否大于0
             //判断是否有上级
            $data=Distribut::find()
                    ->where(['cid'=>$user])
                    ->asArray()
                    ->orderBy('level asc')
                    ->all();
            if(count($data)>0){

                //获取商品分销金额

                $distribute_money=$value['sku_sell_price_real']*0.01*$product['distribute_money']*$value['num'];
                

                //判断分销金额是否大于总支付金额，如果大于  ，分销金额等于总支付金额
                $order=Order::find()->where(['order_no'=>$value['order_no']])->one();
                if($distribute_money >=($order['pay_amount']-$order['delivery_price_real'])){
                    $distribute_money =$order['pay_amount']-$order['delivery_price_real'];
                }

                    foreach ($data as $v){
                        $log=new DistributLog();
                        $log->pid=$v['pid'];
                        $log->cid=$v['cid'];
                        $log->level=$v['level'];
                        $log->goods_id=$product['product_id'];
                        $log->change_money=$distribute_money*$this->levelmoney($v['level'])*0.01;
                        $log->status=2;
                        $log->order_no=$value['order_no'];
                        $log->save();
                    }
                
            } 
            return ['status'=>1];
    }
    /**
     * 分销等级获得金额比例
     */
    public function levelmoney($level){
        $value = Yii::$app->config->get('distribut');
        $percents = explode("-", str_replace("%", "", $value));    	
        if ($level==1) {
            return $percents[0];
        }
        if ($level==2) {
            return $percents[1];
        }
      
    }
    
    /**
     * 完成订单后修改status=1
     */
    public function changestatus($distributLog){
        $d=Yii::$app->db->beginTransaction(); 
        try {
           $num=0;
            foreach ($distributLog as $v){
                
                //update distribut
           $dd = Distribut::findOne(['pid'=>$v['pid'],'cid'=>$v['cid']]);
                $dd->num= $dd['num']+1;
                $dd->amount = bcadd($dd['amount'],$v['change_money'],2);
                $dd->save();
                if ($dd->hasErrors()) {                    
                    $d->rollBack();
                    return ['status'=>0,'msg'=>current($dd->getFirstErrors())];
                } 
             
                $v->status=1;                
                $money=$this->changemoney($v['pid'],$v['change_money'],$v['order_no']);
                if(!$v->save()||$money!=1){
                    $d->rollBack();
                    return ['status'=>0,'msg'=>'失败'];
                }else{
                    $num++;
                }
            }
            if($num==count($distributLog)){
                $d->commit();
                return ['status'=>1,'msg'=>'成功'];
            }else{
                $d->rollBack();
                return ['status' => 0, 'msg' => '失败4'];
            }
     
           
        } catch (\Exception $e) {
             $d->rollBack();
            return ['status' => 0, 'msg' => $e->getMessage()];
        }
      
       
    }
    /**
     * 更改member表累计分销金额
     */
    public function changemoney($id,$distribut_money,$order_no){
        //修改用户表，更新分销金额与用户余额
        $data=Member::find()->where(['id'=>$id])->one();
        $data->distribut_money=bcadd($data->distribut_money,$distribut_money,2); 
        $data->save();
        //新增用户流水纪录
       /* $account=new AccountLogic();
        $info = array();
        $info['order'][] = $order_no; 
        $info = Json::encode($info);
        $changeParams = array();        
        $changeParams['money'] = $distribut_money;        
        $m=$account->changeAccount($id, $changeParams, 6,$info,'分销收入');
        if($data->hasErrors()||$m['status']!=1){ */
        if($data->hasErrors()){
          return 0; 
        }else{
         return 1;
        }
    }
    
    /**
     * 取消订单或者退款，则赏金获取失败，status=3
     * @param $mid
     * @param $order
     * @return boolean
     */
    public function Cancel($mid,$order)
    {
        $data=DistributLog::find()
        ->where(['and',['status'=>2],['cid'=>$mid],['order_no'=>$order['order_no']]])
        ->all();
        $num=0;
        foreach ($data as $v){
            $v->status=3;
            if(!$v->save()){
                $num++;
            }
        }
        if($num>0){
            return ['status'=>0,'msg'=>'失败'];
        }else{
            return ['status'=>1,'msg'=>'操作成功'];
        }
      
        
    }

        
    
}
