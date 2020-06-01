<?php
namespace common\models;
use backend\models\search\SearchModelTrait;
use common\modules\attachment\models\AttachmentIndex;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Hashids\Hashids;
/**
 * User model.
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $access_token
 * @property int $expired_at
 * @property string $email
 * @property string $auth_key
 * @property int $created_at
 * @property int $updated_at
 * @property int $confirmed_at
 * @property int $blocked_at
 * @property int $login_at
 * @property string $password write-only password
 * @property Profile $profile write-only password
 */
class ShopUser extends ActiveRecord implements IdentityInterface
{

    use SearchModelTrait;
   
    public $password;
    public $code;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%shop_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return array_merge($scenarios, [
            'register' => ['username', 'mobile', 'password','shop_id','level','m_id'],
            'connect'  => ['username','mobile',  'email'],
            'create'   => ['username', 'mobile', 'password','shop_id','level','m_id'],
            'update'   => ['username','mobile',  'email', 'password','shop_id','level'],
            'settings' => ['username', 'mobile', 'email', 'password'],
            'resetPassword' => ['password']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'string', 'on' => 'search'],
       /*      ['username', 'required', 'on' => 'create'],
            ['username', 'unique', 'on' => 'create'], */
            ['mobile', 'required', 'on' => 'create'],
            [['mobile'], 'unique', 'on' => 'create'],
            ['m_id','unique','message' => '该用户名下已有店铺'],
            ['mobile','match','pattern' =>'/^1[0-9]{10}$/','message'=>'手机号码格式不正确！'],
            ['mobile', 'unique', 'message' => '手机号已被占用'],
            ['password', 'required', 'on' => ['register']],
            [['m_id','level','status','shop_id'],'integer'],
            ['shop_id','required'],
            [['status'],'default','value'=>1]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'mobile'=>'手机号码',
            'password' => '密码',
            'email' => '邮箱',
            'created_at' => '注册时间',
            'login_at' => '最后登录时间',
            'm_id'=>'用户id',
            'shop_id'=>'店铺id',
            'level'=>'用户角色',
            'status'=>'状态'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'blocked_at' => null]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['access_token' => $token])->andWhere(['>', 'expired_at', time()])->one();
    }

    /**
     * Finds user by username or email.
     *
     * @param string $username
     *
     * @return mixed
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['username' => $username])
            ->andWhere(['blocked_at' => null])
            ->one();
    }

    public static function findByEmail($email)
    {
        return static::find()->where(['email' => $email])
            ->andWhere(['blocked_at' => null])
            ->one();
    }

    public static function findByMobile($mobile)
    {
        return static::find()->where(['mobile' => $mobile])
        ->andWhere(['blocked_at' => null])
        ->one();
    }
    public static function findByUsernameOrEmail($login)
    {
        return static::find()->where(['or', 'username = "' . $login . '"','mobile = "' . $login . '"',  'email = "' . $login . '"'])
            ->andWhere(['blocked_at' => null])
            ->one();
    }
    /**
     * Finds user by password reset token.
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'blocked_at' => null
        ]);
    }

    /**
     * Finds out if password reset token is valid.
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token.
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString().'_'.time();
    }

    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
        $this->expired_at = time() + 60 * 60 * 24 * 365;
    }

    public function removeAccessToken()
    {
        $this->access_token = null;
        $this->expired_at = null;
    }
    public function create()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing shop_user');
        }

        $this->confirmed_at = time();
        $this->password = $this->password == null ? '123456' : $this->password;
        $this->generateAuthKey();
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    public function beforeSave($insert)
    {
        if (!empty($this->password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }
        if (!empty($this->mobile)) {
        $this->username=$this->mobile;
        $this->email=$this->mobile.'@mobile.com';
        }
        return parent::beforeSave($insert);
    }
    public function block()
    {
        return (bool)$this->updateAttributes([
            'blocked_at' => time(),
            'auth_key'   => \Yii::$app->security->generateRandomString(),
        ]);
    }
    /**
     * UnBlocks the user by setting 'blocked_at' field to null.
     */
    public function unblock()
    {
        return (bool)$this->updateAttributes(['blocked_at' => null]);
    }

    /**
     * Confirms the user by setting 'confirmed_at' field to current time.
     */
    public function confirm()
    {
        $result = (bool) $this->updateAttributes(['confirmed_at' => time()]);
        return $result;
    }

    public function getAvatar($width = 96, $height = 0)
    {
        if (empty($height)) {
            $height = $width;
        }
     /*    if($this->profile->avatar) {
            return Yii::$app->storage->thumbnail($this->profile->avatar, $width, $height);
        } */
        return $this->getDefaultAvatar($width, $height);
    }

    public static function getDefaultAvatar($width, $height)
    {
        list ($basePath, $baseUrl) = \Yii::$app->getAssetManager()->publish("@common/static");

        $name = "avatars/avatar_" . $width."x".$height. ".png";
        if(file_exists($basePath . DIRECTORY_SEPARATOR . $name)) {
            return $baseUrl . "/" . $name;
        }
        return $baseUrl . "/" . "avatar_200x200.png";
    }

    /**
     * @return bool Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return $this->confirmed_at != null;
    }
    /**
     * @return bool Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->blocked_at != null;
    }

    public function getShop(){
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
    
    public function getMember(){
        return $this->hasOne(Member::className(), ['id' => 'm_id']);
    }
    
    public function getCode(){
        $hashids=new Hashids('Jihexiandjdjdjfy77784',8);
        $this->code=$hashids->encode($this->id);
        return $this->code;
    }
    
    public function afterFind(){
        $this->code = $this->getCode();
    }


}
