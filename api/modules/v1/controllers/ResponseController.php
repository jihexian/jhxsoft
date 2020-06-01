<?php
/*
 * descripe:支付接口返回接收
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-07-09 10:56
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use yii;
use api\common\controllers\Controller;
use plugins\wxMini\WxMini;
class ResponseController extends Controller{
/*
    public function init()
    {

        //获取通知的数据
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xml = file_get_contents('php://input');

    }*/
    public function actionNotify(){

        //获取通知的数据
        //$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $xml = file_get_contents('php://input');
        //Yii::error($xml.'123456666');
        $pay=new WxMini();
        $pay->response();
        exit();
    }





}