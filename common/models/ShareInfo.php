<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%share_info}}".
 *
 * @property integer $mid
 * @property integer $share_mid
 * @property integer $product_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class ShareInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%share_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mid', 'product_id'], 'required'],
            [['mid', 'share_mid', 'product_id'], 'integer'],
        ];
    }
    
    public function behaviors()
    {
    	$behaviors = [
    			TimestampBehavior::className(),
    	];
    	return $behaviors;
    }
    
}
