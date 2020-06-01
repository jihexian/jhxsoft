<?php
/**
 * Author: vamper
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 */
namespace frontend\models;


use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use yii;
use common\helpers\Util;
class Member extends \common\models\Member implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
	/**
	 * @inheritdoc
	 */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
	
    public function optimisticLock(){
    	return "version";
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }
    /**
     * @inheritdoc
     */
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
        return $this->password === Util::encrypt($password);
    }




}