<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/3/2
 * Time: 下午2:07
 */

namespace api\common\controllers;
use yii;
use api\common\behaviors\ValidateBehavior;
use yii\filters\Cors;
use common\models\Order;
use common\models\Member;
use yii\db\StaleObjectException;
use common\helpers\Tools;
use yii\base\Exception;
/*use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;*/
class Controller extends \yii\rest\Controller
{
    /**
     * 配置让dataProvider返回items
     * @var array
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];
    
   
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['contentNegotiator']);
        $behaviors['cors'] = [
            'class' => Cors::className(),
        ];

        $behaviors['validate'] = ValidateBehavior::className();
        return $behaviors;
    }
	/*为图片加上域名前缀
		$data 数组 要加上前缀的数据 
		$key  字符串 储存图片地址数组索引 不传如默认为image
	*/
    public function ImagesAddPrefix($data,$key="image"){
    	if (empty($data))
    		return $data;    	
		if(gettype($data)=="object") //如果是json对象,转成数组
			$data=json_decode($data,true);

		return $data;
	}


}