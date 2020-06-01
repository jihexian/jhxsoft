<?php
/**
 * User: Vamper
 * Date: 20181107
 */

namespace backend\common\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
class Controller extends \yii\web\Controller
{
		
	 public function init(){
		parent::init();
		$shopId = Yii::$app->session->get('shop_id');
		if (empty($shopId)) {		   
		    Yii::$app->session->set("shop_id",1);
		}	 	
	} 
	public function performAjaxValidation($model)
	{
	    if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax && Yii::$app->request->get('ajax-validate')) {
	        if ($model->load(Yii::$app->request->post())) {
	            Yii::$app->response->format = Response::FORMAT_JSON;
	            return  json_encode(ActiveForm::validate($model));
	            Yii::$app->end();
	        }
	    }
	}
	
}