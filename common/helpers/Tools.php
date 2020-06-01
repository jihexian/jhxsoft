<?php
/*
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-07-03 10:24
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace  common\helpers;

use common\models\Product;
use common\models\Shipping;
use common\models\Member;
use yii\helpers\Url;
use yii;
use common\models\Shop;
use phpDocumentor\Reflection\Types\Null_;


class Tools{

    /**
     * 转换skus_value的格式，从json改为sting
     */
    public static function get_skus_value($value){
        $su=json_decode($value,true);
        $str='';
        foreach ($su as $key => $value) {
            $str.=$key.':'.$value[0].' ';
            # code...
        }
        return $str;
    }

    //生成订单序列号
    public static function get_order_no(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8). str_pad(mt_rand(1, 99999), 8, '0', STR_PAD_LEFT);
    }

   //获取会员价格
   public static function get_member_price($price){
        return $price;

    }

        /**
         * 用户id获取用户名称
         * @param $m_id用户id
         * @return mixed
         */
        public static function get_user_name($m_id){
           $data=Member::findOne($m_id);
           return $data['username'];

        }
        
        /**
                         * 用户id获取管理员名称
         * @param $m_id用户id
         * @return mixed
         */
        public static function get_admin_name($m_id){
           $data=\common\modules\user\models\User::findOne($m_id);
           if($data){
               return $data['username'];
           }else{
               return '';
           }
          
            
        }
        //获取支付状态
        /*将数字状态转成文字*/
        public static function get_status($status){
            $str="";
            switch ($status)
            {
                case 1:
                    $str="待支付";
                    break;
                case 2:
                    $str="待发货";
                    break;
                case 3:
                    $str="已发货";
                    break;
                case 4:
                    $str="待评价";
                    break;
                case 5:
                    $str="已完成";
                    break;
                case 6:
                    $str="已退款";
                    break;
                case 7:
                    $str="部分退款";
                    break;
                case 8:
                    $str="用户取消";
                    break;
                case 9:
                    $str="超时作废";
                    break;
                case 10:
                    $str="退款中";
                    break;
                case 11:
                    $str="拒绝退款";
                    break;
                default:
                    $str="未知状态";
            }
            return $str;
        }

        public static function get_status_bottom($status,$order_id){

            $str='';
            switch ($status)
            {
                
                case 1:


                    $str='<a class="a_cancel_order" data-href="'.Url::to(['order/cancel','order_id'=>$order_id]).'">取消订单</a>'.'<a href="'.Url::to(['order/pay','order_id'=>$order_id]).'" class="crfff bgred bdn">立即支付</a>';


                    break;
                case 2:

                    $str='<a href="'.Url::to(['order/apply','order_id'=>$order_id]).'">申请退款</a>';

                    break;
                case 3:

                    $str='<a href="'.Url::to(['order/shipping','order_id'=>$order_id]).'">查看物流</a>'.'<a href="'.Url::to(['order/confirm','order_id'=>$order_id]).'" class="crfff bgred bdn">确认收货</a>';

                    break;
                case 4:
                    $str='<a href="'.Url::to(['/product-comment/list','order_id'=>$order_id]).'" class="crfff bgred bdn">点评</a>';
                    break;
                case 5:

                    $str='<a class="a_del_order" data-href="'.Url::to(['order/delete','order_id'=>$order_id]).'">删除订单</a>';

                    break;
                case 6:  //退款完成
                    $str='<a class="a_del_order" data-href="'.Url::to(['order/delete','order_id'=>$order_id]).'">删除订单</a>';
                    break;
                case 7:
                    $str="部分退款";
                    break;
                case 8:

                    $str='<a class="a_del_order" data-href="'.Url::to(['order/delete','order_id'=>$order_id]).'">删除订单</a>';

                    break;
                case 9: //超时作废
                    $str='<a class="a_del_order" data-href="'.Url::to(['order/delete','order_id'=>$order_id]).'">删除订单</a>';
                    break;
                case 10:
                    $str="退款中";
                    break;
           
                case 11:
                    $str='<a href="'.Url::to(['order/result','order_id'=>$order_id]).'">查看原因</a>';
                    break;
                default:
                    $str="未知状态";
            }
            return $str;
        }

    /**
     * @param $status
     * @return string
     * 支付状态
     */
    public static function pay_status($status)
    {
        $str = "";
        switch ($status) {
            case 0:
                $str = "未支付";
                break;
            case 1:
                $str = "已支付";
                break;
            default:
                $str="未支付";
        }
        return $str;
    }

    /**
     * 获取订单状态
     */
    public static function shipping_status($status){
        $str = "";
        switch ($status) {
            case 0:
                $str = "未发货";
                break;
            case 1:
                $str = "已发货";
                break;
            case 2:
                $str = "部分发货";
                break;
            case 3:
                $str = "已收货";
                break;
            default:
                $str="未发货";
        }
        return $str;
    }
    /**
     * 获取订单状态
     */
    public static function refuse_status($status){
        $str = "";
        switch ($status) {
            case 0:
                $str = "等待退款";
                break;
            case 1:
                $str = "拒绝退款";
                break;
            case 2:
                $str = "退款成功";
                break;
            default:
                $str="未知";
        }
        return $str;
    }
    /**
     * 获取积分和金额流水类型
     */
    public static function get_account_log($type){
        $str = "";
        switch ($type) {
            case 1:
                $str = "订单消费";
                break;
            case 2:
                $str = "充值";
                break;
            case 3:
                $str = "活动赠送";
                break;
            case 4:
                $str="管理员操作";
                break;
            case 5:
                $str="到店支付";
                break;
            case 6:
                $str="分销提成";
                break;
            case 7:
                $str="订单退回";
                break;
            case 8:
                $str="提现";
                break;
            case 9:
                $str="红包";
                break;
            default:
                $str="系统出错";
        }
        return $str;
    }

    /**
     * 获取商品图片封面
     * @param $product_id
     * @return mixed
     */
    public static function get_product_image($product_id){
        $product=Product::findOne($product_id);
        
        return isset($product['image'][0])? $product['image'][0]['thumbImg']:Yii::$app->params['defaultImg']['default'];
    }

    /**
     * 获取配送方式
     */
    public static function get_delivery_name($id){
    $shipping=Shipping::findOne($id);
    return $shipping['name'];
    }
    /**
     *获取店铺名
     */
    public static function get_shop_name($id){
        $str = "";
        $str=Shop::find()->andWhere(['id'=>$id])->one()->__get('name');
        return $str;
    }
    
   public static  function isMobile() {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
    public static function getDelivery($id){
        switch ($id){
            case 0:$str='物流配送';break;
            case 1:$str='电子票';break;
            case  2:$str='门店自提';break;
            default: $str='物流配送';break;
        }
        return $str;
    }
    
    //对emoji表情转义
    public static function emoji_encode($str){
        $strEncode = '';
        
        $length = mb_strlen($str,'utf-8');
        
        for ($i=0; $i < $length; $i++) {
            $_tmpStr = mb_substr($str,$i,1,'utf-8');
            if(strlen($_tmpStr) >= 4){
                $strEncode .= '[[EMOJI:'.rawurlencode($_tmpStr).']]';
            }else{
                $strEncode .= $_tmpStr;
            }
        }
        
        return $strEncode;
    }
    //对emoji表情转反义
    public static function emoji_decode($str){
        $strDecode = preg_replace_callback('|\[\[EMOJI:(.*?)\]\]|', function($matches){
            return rawurldecode($matches[1]);
        }, $str);
            return $strDecode;
    }
  
   
   
}