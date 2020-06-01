<?php
/**
 * 
  * 支付方式
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年5月17日下午5:14:26
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\widgets\pay;
use common\models\Plugin;
use common\models\Member;
use Yii;
use yii\base\Widget;
class PayWidget extends Widget {
    public $payment;
    public $order_no;
    public $parent_sn;
    public $user_money;//用户现金余额
    public function init(){
        parent::init();
        $this->payment=Plugin::find()->where(['type'=>'payment','status'=>1,'scene'=>2])->orderBy(['id' => SORT_DESC])->all();
        $this->user_money=Member::find()->select('user_money')->where(['id'=>Yii::$app->user->id])->one();
        
    }
    public function run(){
         parent::run();
         return $this->render('select',[
                 'payment'=>$this->payment,
                 'order_no'=>$this->order_no,
                 'parent_sn'=>$this->parent_sn,
                 'user_money'=>$this->user_money,
         ]);
    }
}