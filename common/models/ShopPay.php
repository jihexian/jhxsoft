<?php

namespace common\models;

use common\behaviors\CheckShopBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\query\ShopPayQuery;

/**
 * This is the model class for table "{{%shop_pay}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $account
 * @property string $name
 * @property string $bank
 * @property integer $status
 * @property integer $sort
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $shop_id
 */
class ShopPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'sort','shop_id'], 'integer'],
            [['account', 'name', 'bank'], 'string', 'max' =>150],
            ['status','default','value'=>0],
            [['account', 'name', 'bank'],'required'],
            ['shop_id','default', 'value' =>Yii::$app->session->get('shop_id')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id'=>'店铺id',
            'account' => '提现账号',
            'name' => '收款人',
            'bank' => '开户行',
            'status' => '状态',
            'sort' => '排序',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
                [
                        'class' => TimestampBehavior::className(),
                ],
                CheckShopBehavior::className(),
        ];
        return $behaviors;
        
    }
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
 
    public static function getList($shop_id){
        $data=ShopPay::find()->where(['shop_id'=>$shop_id])->asArray()->all();
//         print_r($data);exit();
        $str=[];
        foreach ($data as $v){
            $str[$v['id']]=$v['bank'].$v['account'];
        }
        return $str;
    }
    public static function find(){
        return new ShopPayQuery(get_called_class());
    }
}
