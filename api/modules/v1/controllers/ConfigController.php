<?php
/**
 * Created by notepad++.
 * Author: vamper
 * DateTime: 2018/8/10
 * Description:
 */
namespace api\modules\v1\controllers;

/*幻灯片接口*/

use yii;
use  api\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use common\modules\config\models\Config;

class ConfigController extends Controller
{


   public function actionInfo(){
	 	$configs = Config::find()->where('id>0')->all();
	 	return ['items'=>$configs];
   }
   
}