<?php
/**
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-06-05 8:13
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace api\modules\v1\models;


use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use yii;
class Member extends \common\models\Member implements IdentityInterface
{
    private $token;
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);

      /*     $log= self::find()->where(['access_token' =>$token])->one();
        if(!empty($log))
        {


           if(time()>$log['expire_in']) //如果已超出有效时间
            {

                return false;
            }else
            {
                return $log; //如果登陆信息是有效的，返回用户信息
            }

        }else{
            return false;
        }*/

    }

    public function optimisticLock(){
    	return "version";
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$members as $member) {
            if (strcasecmp($member['username'], $username) === 0) {
                return new static($member);
            }
        }
        return null;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */

    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    /**
     * @return string 生成accessToken
     */
    public function createAccessToken(){
        $this->token=md5(time().rand(1000,9999)).md5(time().'liwu.jihexian.com'.rand(1000,9999));

    }

    public function create_token($uid){
        //这里使用4次md5以及时间和随机数，最大情况避免token重复，生成一个128位的token值

            $this->createAccessToken();
            $data['access_token']=$this->token;
            $data['expire_in']=time()+env('TOKEN_EXPIRE_TIME');  //token有效期为15分钟
              //$data['expire_in']=time()+1*15;  //token有效期为15分钟
            if (Member::updateAll($data,['id'=>$uid])) {
                return $this->token;
            }
            else{
                return "token无法保存到数据库";
            }


    }

    //验证token是否有效
    public function  auth_token($token){
        $log= self::find()->where(['access_token' =>$token])->one();
        if(empty($log))
        {
            return false;
        }
        if(time()>$log['expire_in']) //如果已超出有效时间
        {
            return false;
        }

        return $log; //如果登陆信息是有效的，返回用户信息
            
    }







}