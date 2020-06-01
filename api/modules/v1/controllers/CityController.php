<?php
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
class CityController extends Controller{
    public function actionIndex(){
        return ['status'=>1];
    }
}