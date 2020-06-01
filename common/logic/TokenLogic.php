<?php
/**
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-06-04 9:26
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\components\Controller;
use common\models\Member;
use common\models\AccessToken;
use yii;
class TokenLogic
{
    public $token;
    public $obj;
 /*   public function __construct()
    {
        $this->obj=new AccessToken();
    }*/
    public static function get_uid()
    {
        $token=Yii::$app->request->getBodyParam("token");
        //获取用户信息
        $accesstoken=new AccessToken();
        $data= $accesstoken->auth_token($token);
        return $data['id'];
    }
}