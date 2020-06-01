<?php
/**
 * Created by notepad++.
 * Author: 小黑
 * DateTime: 2018/4/9
 * Description:
 */
namespace api\modules\v1\controllers;

/*幻灯片接口*/

use yii;
use  api\common\controllers\Controller;
use  common\models\CarouselItem;
use  common\models\CarouselItemSearch;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use common\models\Carousel;

class CarouselItemController extends Controller
{

    public function actionList(){
        $data = Yii::$app->request->post();
        if(isset($data['key'])&&$data['key']){
            $carousel = Carousel::find()->where(array('key'=>$data['key']))->one();
            $data['carousel_id']=$carousel->id;
            unset($data['key']);
        }     
        $model=new CarouselItemSearch();
        $data=$model->search($data);
        return $data;
    }
   
 
}