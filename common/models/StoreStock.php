<?php
/**
 * 区域仓库库存
 * Author wsyone wsyone@faxmail.com
 * Time:2020年2月20日上午11:08:36
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\models;

use Yii;
use api\modules\v1\models\Product;

/**
 * This is the model class for table "{{%store_stock}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $sku_id
 * @property integer $store_id
 * @property integer $stock
 * @property integer $created_at
 * @property integer $updated_at
 */
class StoreStock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_stock}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'sku_id', 'store_id', 'stock'], 'required'],
            [['id', 'product_id',  'store_id', 'stock'], 'integer'],
             [['sku_id'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'sku_id' => Yii::t('app', 'Sku ID'),
            'store_id' => Yii::t('app', 'Store ID'),
            'stock' => Yii::t('app', 'Stock'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return StoreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StoreQuery(get_called_class());
    }
    
    public  function getStore(){
        return $this->hasOne(Store::className(),['id'=>'store_id']);
    }
    
    public  function getProduct(){
        return $this->hasOne(Product::className(),['id'=>'product_id']);
    }
}
