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
use common\models\Nav as NavModel;

class NavController extends Controller
{

	public function actionNavitems()
    {
        $data = Yii::$app->request->post();
        $items = NavModel::getItems($data['key']);
        return ['items'=>$items];
    }
}