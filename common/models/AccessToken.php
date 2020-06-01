<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $sort
 * @property integer $deep
 */
class AccessToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%access_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'expire_in'], 'integer'],
            [['token'], 'string', 'max' => 128],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'token值',
            'uid' => '用户id',
            'expire_in' => '在什么时间失效',
        ];
    }
	public function getMember(){
		return $this->hasOne(Member::className(), ['id' => 'uid']);
	}
   /*生成一个随机token,*/
   public function create_token($uid){
	  //这里使用4次md5以及时间和随机数，最大情况避免token重复，生成一个128位的token值
	 $token=md5(time().rand(1000,9999)).md5(time().'abc'.rand(1000,9999)).md5(time().'cba'.rand(1000,9999)).md5(time().'def'.rand(1000,9999));
	  
	 if($this->no_exit($token)) //如果数据库不存在相同的token，该token可用
	 {
	   $this->uid=$uid;
	   $this->token=$token;
	   $this->expire_in=time()+15*60;  //token有效期为15分钟
	   if ($this->save()) {
		   return $token;
       }
	   else{
		   return "token无法保存到数据库";
	   }
	 }
     else
		$this->create_token($uid);
   }
   //检查是否已存在相同的记录
   private function no_exit($token){
	    $log=self::find()->where(['token' => $token])->asArray()->one();
		if(empty($log))
			return true;
		else
			return false;
   }
   //验证token是否有效
   public function  auth_token($token){
	   $log= self::find()->where(['token' => $token])->with("member")->asArray()->one();
	   if(!empty($log))
	   {
		   if(time()>$log['expire_in']) //如果已超出有效时间
		   {
			   AccessToken::deleteAll(['token' => $token]);  //删除失效数据
			   return false;
		   }else
		   {
			   return $log['member']; //如果登陆信息是有效的，返回用户信息
		   }
		   
	   }else{
		   return false;
	   }
   }


}
