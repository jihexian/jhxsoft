<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/7/24
 * Time: 下午4:22
 */

namespace common\helpers;

use yii;
use yii\helpers\ArrayHelper;
use common\models\Product;
use common\models\SmsLog;

class Util
{
    /**
     * 解析url 格式: route[空格,回车]a=1&b=2
     * @param $url
     * @return array
     */
    public static function parseUrl($url)
    {
        if (strpos($url, '//') !== false) {
            return $url;
        }
        // 空格换行都行
        $url = preg_split('/[ \r\n]+/', $url);
        if (isset($url[1])) {
            $tmp = $url[1];
            unset($url[1]);
            $tmpParams = explode('&', $tmp);
            $params = [];
            foreach ($tmpParams as $tmpParam) {
                list($key, $value) = explode('=', $tmpParam);
                $params[$key] = $value;
            }
            $url = array_merge($url, $params);
        }
        return $url;
    }

    public static function getEntityList()
    {
        return [
            'common\models\Suggest' => '留言',
            'common\models\Page' => '单页',
            'common\models\Article' => '文章',
            'common\modules\book\models\Book' => '书',
            'common\modules\book\models\BookChapter' => '书章节',
        ];
    }
    public static function getEntityName($entity)
    {
        $entityList = self::getEntityList();
        return ArrayHelper::getValue($entityList, $entity, $entity);
    }

    /*为图片加上域名前缀
     $data 数组 要加上前缀的数据
     $key  字符串 储存图片地址数组索引 不传如默认为image
     */
    public static function ImagesAddPrefix($data,$key="image"){
    	if (empty($data))
    		return $data;
    	
    	if (gettype($data)=="object"||gettype($data)=="array"){
    		if(gettype($data)=="object"){
    			//如果是json对象,转成数组
    			$data=json_decode($data,true);
    		
    		}
    		foreach ($data as $k =>$v) {
    			if (!empty($v[$key])){

    			    $data[$k][$key]=Yii::$app->params['domain'].$v[$key];
    			}else{//如果skus没有图片，使用商品主图
                      if($v['product_id']!=''){
                         $product=Product::findOne($v['product_id']);
                         if (count($product['image'])>0){
                         	$data[$k][$key]=Yii::$app->params['domain'].$product['image'][0]['thumbImg'];
                         }else{
                         	$data[$k][$key]=Yii::$app->params['defaultImg']['default'];
                         }
                          
                      }
                }
    		}
    	}else{
    		//如果是字符串，且不是json字符串
    	    $data = str_replace($key,'img src="'.Yii::$app->params['domain'].'/storage/upload',$data);   
    	}
    		
    	
    	return $data;
    }
    //判断二维数组是否存在值
    public static function deep_in_array($value, $array) {
    	foreach($array as $item) {
    		if(!is_array($item)) {
    			if ($item == $value) {
    				return true;
    			} else {
    				continue;
    			}
    		}
    		 
    		if(in_array($value, $item)) {
    			return true;
    		} else if(Util::deep_in_array($value, $item)) {
    			return true;
    		}
    	}
    	return false;
    }
    public static function encrypt($str){
    	$authCode = Yii::$app->params['auth_code'];
		return md5($authCode.$str);
	}
	/**
	 * 根据数组中的key组合新数组 
	 * $arr = [['id'=>1,'name'='A'],['id'=>1,'name'=>'B'],['id'=>2,'name'=>'C'],['id'=>1,'name'=>'D']]
	 * $key = 'id'
	 * array_key_array($arr,$key)
	 * ['1'=>[['id'=>1,'name'='A',['id'=>1,'name'=>'B'],['id'=>1,'name'=>'D']]],'2'=>[['id'=>2,'name'=>'C']]]
	 * @param array $arr
	 * @param string $key
	 * @return array 
	 */
	public static function array_key_array($arr,$key){
	    $result = [];
	    foreach ($arr as $v){
	        $result[$v[$key]][] = $v;
	    }
	    return $result;
	}
	/**
	 * 获取中文日期
	 * @param  string $date Y-m-d格式
	 * @return string 中文格式
	 */
	public static function getCnDate($date){
	    $paramstr = explode('-', $date);
	    $return = intval($paramstr[0]).'年'.intval($paramstr[1]).'月'.intval($paramstr[2]).'日';
	    return $return;
	}
	
	public static function checkSms($mobile,$scene,$verifyCode) {
	    $sms = SmsLog::find()->where(['mobile'=>$mobile,'scene'=>$scene,'code'=>$verifyCode])->one();
	    if (empty($sms)) {
	        return ['status'=>0,'msg'=>'验证码错误，请重新输入！'];
	    }else{
	        if ($sms->created_at+60*10<time()) {
	            return ['status'=>0,'msg'=>'验证码过期，请重新获取！'];
	        }
	    }
	    return ['status'=>1,'msg'=>'验证成功'];
	}
	
	public static function create_folders($dir) {
	    return is_dir($dir) or (Util::create_folders(dirname($dir)) and mkdir($dir, 0777));
	}
	
}