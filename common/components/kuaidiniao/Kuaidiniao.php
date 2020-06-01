<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2018年12月22日 下午10:58:36
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\components\kuaidiniao;
use Yii;
class Kuaidiniao {
   
   private $EBusinessID;
   private $AppKey;
   private $ReqURL;
   public function __construct() {
       $this->EBusinessID='1411945';
       $this->AppKey='42793871-9213-41d5-9619-8eb53afcac4b';  
       $this->ReqURL='http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';//测试地址'http://testapi.kdniao.com:8081/api/dist';
      
     
       
   }
  
    /**
     * Json方式  物流信息订阅
     */
   public function orderTracesSubByJson(){
        $requestData="{'OrderCode': 'SF201608081055208281',".
            "'ShipperCode':'SF',".
            "'LogisticCode':'3100707578976',".
            "'PayType':1,".
            "'ExpType':1,".
            "'IsNotice':0,".
            "'Cost':1.0,".
            "'OtherCost':1.0,".
            "'Sender':".
            "{".
            "'Company':'LV','Name':'Taylor','Mobile':'15018442396','ProvinceName':'上海','CityName':'上海','ExpAreaName':'青浦区','Address':'明珠路73号'},".
            "'Receiver':".
            "{".
            "'Company':'GCCUI','Name':'Yann','Mobile':'15018442396','ProvinceName':'北京','CityName':'北京','ExpAreaName':'朝阳区','Address':'三里屯街道雅秀大厦'},".
            "'Commodity':".
            "[{".
            "'GoodsName':'鞋子','Goodsquantity':1,'GoodsWeight':1.0}],".
            "'Weight':1.0,".
            "'Quantity':1,".
            "'Volume':0.0,".
            "'Remark':'小心轻放'}";
        
        
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1008',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result=$this->sendPost('http://api.kdniao.com/api/dist', $datas);
        
        //根据公司业务处理返回的信息......
        
        return $result;
    }
    
    /**
     * 即时查询
     * Json方式 查询订单物流轨迹
     */
    function getOrderTracesByJson($requestData){
       // $requestData= "{'OrderCode':'','ShipperCode':'YTO','LogisticCode':'12345678'}";
        
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        //yii::error($datas);
        //yii::error($this->ReqURL);
        $result=$this->sendPost($this->ReqURL, $datas);
        
        //根据公司业务处理返回的信息......
        
        return $result;
    }
    
    /**
     * Json方式 单号识别
     */
    function getOrderInfo(){
        $requestData= "{'LogisticCode':'1000745320654'}";
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '2002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result=$this->sendPost($this->ReqURL, $datas);
        
        //根据公司业务处理返回的信息......
        
        return $result;
    }
    
    /**
     * Json方式 智选物流
     */
    function getExpRecommendByJson(){
        $requestData= "{'MemberID':'123456','WarehouseID':'1','Detail':[{'OrderCode':'12345','IsCOD':0,'Sender':{'ProvinceName':'广东省','CityName':'广州','ExpAreaName':'龙岗区','Subdistrict':'布吉街道','Address':'518000'},'Receiver':{'ProvinceName':'广东','CityName':'梅州','ExpAreaName':'丰顺','Subdistrict':'布吉街道','Address':'518000'},'Goods':[{'ProductName':'包','Volume':'','Weight':'1'}]},{'OrderCode':'12346','IsCOD':0,'Sender':{'ProvinceName':'广东省','CityName':'广州','ExpAreaName':'龙岗区','Subdistrict':'布吉街道','Address':'518000'},'Receiver':{'ProvinceName':'湖南','CityName':'长沙','ExpAreaName':'龙岗区','Subdistrict':'布吉街道','Address':'518000'},'Goods':[{'ProductName':'包','Volume':'','Weight':'1'}]}]}";
     
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '2006',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result=$this->sendPost($this->ReqURL, $datas);
        
        //根据公司业务处理返回的信息......
        
        return $result;
    }
    
    /**
     * Json方式 导入运费模板
     */
    function importCostTemplateByJson(){
        $requestData= "{'MemberID':'123456','Detail':[{'ShipperCode':'YD','SendProvince':'广东','SendCity':'广州','SendExpArea':'天河','ReceiveProvince':'湖南','ReceiveCity':'长沙','ReceiveExpArea':'龙岗','FirstWeight':'1','FirstFee':'8','AdditionalWeight':'1','AdditionalFee':'10','WeightFormula':''},{'ShipperCode':'YD','SendProvince':'广东','SendCity':'广州','SendExpArea':'天河','ReceiveProvince':'湖南','ReceiveCity':'长沙','ReceiveExpArea':'雨花','FirstWeight':'1','FirstFee':'8','AdditionalWeight':'1','AdditionalFee':'10','WeightFormula':'{{w-0}-0.4}*{{{1000-w}-0.4}+1}*4.700+ {{w-1000}-0.6}*[(w-1000)/1000]*4.700）','ShippingType':'1','IntervalList':[{'StartWeight': 1.0,'EndWeight': 2.0, 'Fee': 3.0}]}]}";
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '2004',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result=$this->sendPost($this->ReqURL, $datas);
        
        //根据公司业务处理返回的信息......
        
        return $result;
    }
    
  

    
    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * 
     */
    function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        yii::error($url_info);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);
        
        return $gets;
    }
    
    
    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @ DataSign签名
     */
   private function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
    
}


