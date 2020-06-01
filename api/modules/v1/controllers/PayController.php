<?php
/**
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-06-22 14:58
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use yii;
use common\models\Order;
use common\models\Member;
use plugins\wxMini\WxMini;
use common\models\Recharge;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\helpers\Tools;
use common\helpers\Util;
use common\logic\OrderLogic;
use common\logic\WithdrawalLogic;
use common\models\Plugin;

class PayController extends Controller
{

    public $payment;

    // 具体的支付类
    public $pay_code;

    // 具体的支付code
    public function behaviors ()
    {
        return ArrayHelper::merge(parent::behaviors(),
                [
                        [
                                'class' => QueryParamAuth::className(),
                                'tokenParam' => 'token',
                                'optional' => []
                        ]
                ]);
    }

    /**
     *
     * @param
     *            $order_id订单id
     * @int $parent_sn 联合订单id
     */
    public function actionXcx ()
    {
        $order_id = Yii::$app->request->post('order_id');
        $parent_sn = Yii::$app->request->post('parent_sn');
        if (! $order_id && ! $parent_sn) {
            return [
                    'status' => 0,
                    'msg' => '必须提供一个订单id'
            ];
        }
        $token = Yii::$app->request->post('token');
        $user = Member::find()->where([
                'access_token' => $token
        ])->one();
        // 判断是否是组合订单，分开金额
        $con = array();
        $total = 0;
        $pay_status = 0;
        if (! empty($parent_sn)) {
            $order = Order::find()->where([
                    'parent_sn' => $parent_sn
            ])->all();

            foreach ($order as $key => $vo) {
                $total += $vo['pay_amount'];
                $pay_status += $vo['payment_status'];
            }
            $con['pay_amount'] = $total;
            $con['order_no'] = $parent_sn;
        } else {
            $order = Order::find()->where([
                    'id' => $order_id
            ])->one();
            $con['pay_amount'] = $order['pay_amount'];
            $con['order_no'] = $order['order_no'];
            $pay_status = $order['payment_status'];
            $total = $con['pay_amount'];
        }

        // 下单时检查
        if (empty($order)) {
            return [
                    'status' => 0,
                    'msg' => '订单不存在'
            ];
        }

        if ($pay_status > 0) {
            return [
                    'status' => 0,
                    'msg' => '订单支付出错'
            ];
        }

        if ($total == 0) {
            $logic = new OrderLogic();
            $r = $logic->update_pay_status($con['order_no'], $total,
                    array(
                            'transaction_id' => time() . rand(1000, 9999),
                           'payment_code'=>'money',
                           'payment_name'=>'余额支付',
                 /*            'payment_code' => 'wxMini',
                            'payment_name' => '微信小程序支付' */
                    ));
            if ($r['status'] == 1) {
                return [
                        'status' => 1,
                        'is_cash' => 0,
                        'msg' => '支付成功'
                ];
            } else {
                return [
                        'status' => 0,
                        'msg' => '支付失败'
                ];
            }
        } else {

            if ($user['xcx_openid'] &&
                    strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                $pay = new WxMini();
                $notifyUrl = getenv('SITE_URL') . Url::to([
                        'response/notify'
                ]);
                $data = $pay->xcx($con, $user['xcx_openid'], $notifyUrl);
                $data = json_decode($data);
                return [
                        'status' => 1,
                        'items' => $data,
                        'is_cash' => 1
                ];
            } else {
                return [
                        'msg' => '未知错误',
                        'open_id' => $user['xcx_openid']
                ];
            }
        }
    }

    /**
     * 支付方式选择
     *
     * @param int $pay_id
     * @param int $order_id
     */
    public function actionMoney ()
    {
       $plugin=Plugin::find()->where(['type'=>'payment','status'=>1,'scene'=>2,'code'=>'Money'])->one();
       if(empty($plugin)){
           return [
               'status' => 0,
               'msg' => '余额支付未开启，请在后台插件内开启'
           ];
       }
        $parent_sn = yii::$app->request->post('parent_sn');
        $pay_pwd = Yii::$app->request->post('pay_pwd');
        $order_id = yii::$app->request->post('order_id');
        if (! $order_id && ! $parent_sn) {
            return [
                    'status' => 0,
                    'msg' => '必须提供一个订单号'
            ];
        }

        $con = array();
        $total = 0;
        $pay_status = 0;
        $pay_time = Yii::$app->config->get('pay_time');
        $info = array();
        if (! empty($parent_sn)) {
            $orders = Order::find()->where([
                    'parent_sn' => $parent_sn
            ])->all();
            foreach ($orders as $key => $vo) {
                $total += $vo['pay_amount'];
                $pay_status += $vo['payment_status'];
                $info['order'][$key] = $vo['order_no'];
            }
            $con['pay_amount'] = $total;
            $con['info'] = $info;
            $con['order_no'] = $parent_sn;
            if (isset($orders[0])) {
                $con['end_time'] = date('Y-m-d H:i:s',
                        $orders[0]['create_time'] + $pay_time * 3600);
            }
        } else {
            $order = Order::find()->where([
                    'id' => $order_id
            ])->one();
            $con['pay_amount'] = $order['pay_amount'];
            $con['order_no'] = $order['order_no'];
            $info = array();
            $info['order'][] = $order['order_no'];
            $con['info'] = $info;
            $con['end_time'] = date('Y-m-d H:i:s',
                    $order['create_time'] + $pay_time * 3600);
            $pay_status = $order['payment_status'];
        }

        if (empty($con)) {
            // throw new Exception('订单已支付');
            return [
                    'status' => 0,
                    'msg' => '订单不存在'
            ];
        }
        if ($pay_status > 0) {
            // throw new Exception('订单已支付');
            return [
                    'status' => 0,
                    'msg' => '订单已支付'
            ];
        }

        $user = Member::findOne(yii::$app->user->id);
        $con['user_money'] = $user['user_money'];

        // TODO:验证支付密码
        $pay_pwd = Util::encrypt($pay_pwd);
        $flag = Member::find()->andwhere([
                'id' => Yii::$app->user->id
        ])
            ->andWhere([
                'pay_pwd' => $pay_pwd
        ])
            ->count();
        if ($flag == 0) {
            return [
                    'status' => 0,
                    'msg' => '密码错误'
            ];
        }

        $logic = new OrderLogic();

        $message = $logic->money(yii::$app->user->id, $con);
        if ($message['status'] == 1) {
            return [
                    'status' => 1,
                    'msg' => '支付成功'
            ];
        } else {
            return [
                    'status' => 0,
                    'msg' => '支付失败'
            ];
        }
        // 订单状态及订单log处理
    }

    public function actionRecharge ()
    {
        $money = Yii::$app->request->post('money');
        if (empty($money)) {
            throw new \Exception('金额不能为空');
        }
        $token = Yii::$app->request->post('token');
        $user = Member::find()->where([
                'access_token' => $token
        ])->one();
        $recharge = new Recharge();
        $recharge->pay_amount = $money;
        $recharge->payment_code = 'wxMini'; // 'payment_code'=>'wxMini','payment_name'=>'微信小程序支付'
        $recharge->payment_name = '微信小程序支付';
        $recharge->m_id = $user['id'];
        $recharge->order_no = 're_' . Tools::get_order_no();

        if (! $recharge->save()) {
            return [
                    'status' => 0,
                    'msg' => current($recharge->getFirstErrors())
            ];
        }
        // 获取订单总金额
        $con = array();
        $con['pay_amount'] = $recharge->pay_amount;
        $con['order_no'] = $recharge->order_no;

        if ($user['xcx_openid'] &&
                strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            $pay = new WxMini();
            $notifyUrl = getenv('SITE_URL') . Url::to([
                    'response/notify'
            ]);
            $data = $pay->xcx($con, $user['xcx_openid'], $notifyUrl);
            $data = json_decode($data);
            return [
                    'status' => 1,
                    'items' => $data,
                    'is_cash' => 1
            ];
        } else {
            return [
                    'status' => 0,
                    'msg' => '未知错误',
                    'open_id' => $user['xcx_openid']
            ];
        }
    }

    /**
     * 获取url 中的各个参数 类似于 pay_code=alipay&bank_code=ICBC-DEBIT
     *
     * @param
     *            $str
     * @return
     */
    function parse_url_param ($str)
    {
        $data = array();
        $str = explode('?', $str);
        $str = end($str);
        $parameter = explode('&', $str);
        foreach ($parameter as $val) {
            $tmp = explode('=', $val);
            $data[$tmp[0]] = $tmp[1];
        }
        return $data;
    }

    public function actionWithdrawal ()
    {
        $money = yii::$app->request->post('money', 0);
        if (empty($money)) {
            return [
                    'status' => 0,
                    'msg' => '金额不能为0'
            ];
        }
        $client=yii::$app->request->post('client','wxMini');
        $type=Yii::$app->request->post('type',0);
        $logic = new WithdrawalLogic();
        $mid = yii::$app->user->id;
        $data=yii::$app->request->post();
        $data = $logic->apply($money, $mid,$client,$type,$data);
        return $data;
    }
}