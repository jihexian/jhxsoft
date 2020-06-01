<?php
use common\logic\OrderLogic;
use common\models\Order;
use common\models\OrderArrive;
use common\models\Recharge;
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once dirname(dirname(__FILE__))."/lib/WxPay.Api.php";
require_once dirname(dirname(__FILE__))."/lib/WxPay.Notify.php";
require_once 'log.php';

$f = dirname(dirname(__FILE__));
//初始化日志
$logHandler= new CLogFileHandler($f."/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
        Log::DEBUG("call back:" . json_encode($data));
       
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }
        
        $appid = $data['appid']; //公众账号ID
        $order_sn = $data['out_trade_no']; //商户系统的订单号，与请求一致。
        $attach = $data['attach']; //商家数据包，原样返回

        
        $money=$data['total_fee']/100;
        $logic=new OrderLogic();
        $l=$logic->update_pay_status($order_sn,$money, array('transaction_id' => $data["transaction_id"],'payment_code'=>'wxMini','payment_name'=>'微信小程序支付')); // 修改订单支付状态
    
        if($l['status']==1){
            return true;
        }else{  
            return false;
        }
       
	}
}
//Log::DEBUG("begin notify");
//$notify = new PayNotifyCallBack();
//$notify->Handle(false);
