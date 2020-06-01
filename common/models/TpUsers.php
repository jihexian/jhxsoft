<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tp_users".
 *
 * @property string $user_id
 * @property string $email
 * @property string $password
 * @property string $paypwd
 * @property integer $sex
 * @property integer $birthday
 * @property string $user_money
 * @property string $frozen_money
 * @property string $distribut_money
 * @property string $pay_points
 * @property string $address_id
 * @property string $reg_time
 * @property string $last_login
 * @property string $last_ip
 * @property string $qq
 * @property string $mobile
 * @property integer $mobile_validated
 * @property string $oauth
 * @property string $openid
 * @property string $unionid
 * @property string $head_pic
 * @property integer $province
 * @property integer $city
 * @property integer $district
 * @property integer $email_validated
 * @property string $nickname
 * @property integer $level
 * @property string $discount
 * @property string $total_amount
 * @property integer $is_lock
 * @property integer $is_distribut
 * @property integer $first_leader
 * @property integer $second_leader
 * @property integer $third_leader
 * @property string $token
 */
class TpUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tp_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthday', 'pay_points', 'address_id', 'reg_time', 'last_login', 'province', 'city', 'district', 'first_leader', 'second_leader', 'third_leader'], 'integer'],
            [['user_money', 'frozen_money', 'distribut_money', 'discount', 'total_amount'], 'number'],
            [['qq', 'mobile'], 'required'],
            [['email'], 'string', 'max' => 60],
            [['password', 'paypwd'], 'string', 'max' => 32],
            [['sex', 'email_validated', 'level', 'is_lock', 'is_distribut'], 'string', 'max' => 1],
            [['last_ip'], 'string', 'max' => 15],
            [['qq', 'mobile'], 'string', 'max' => 20],
            [['mobile_validated'], 'string', 'max' => 3],
            [['oauth'], 'string', 'max' => 10],
            [['openid', 'unionid'], 'string', 'max' => 100],
            [['head_pic'], 'string', 'max' => 255],
            [['nickname'], 'string', 'max' => 50],
            [['token'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'email' => 'Email',
            'password' => 'Password',
            'paypwd' => 'Paypwd',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'user_money' => 'User Money',
            'frozen_money' => 'Frozen Money',
            'distribut_money' => 'Distribut Money',
            'pay_points' => 'Pay Points',
            'address_id' => 'Address ID',
            'reg_time' => 'Reg Time',
            'last_login' => 'Last Login',
            'last_ip' => 'Last Ip',
            'qq' => 'Qq',
            'mobile' => 'Mobile',
            'mobile_validated' => 'Mobile Validated',
            'oauth' => 'Oauth',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'head_pic' => 'Head Pic',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'email_validated' => 'Email Validated',
            'nickname' => 'Nickname',
            'level' => 'Level',
            'discount' => 'Discount',
            'total_amount' => 'Total Amount',
            'is_lock' => 'Is Lock',
            'is_distribut' => 'Is Distribut',
            'first_leader' => 'First Leader',
            'second_leader' => 'Second Leader',
            'third_leader' => 'Third Leader',
            'token' => 'Token',
        ];
    }
}
