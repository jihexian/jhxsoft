<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shop_category}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $percent
 * @property integer $sort
 * @property integer $status
 */
class ShopCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['percent'], 'number'],
            [['sort','status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['status','default', 'value' =>1],
            [['percent'], 'match', 'pattern' => '/^[0]+(.[0-9]{1,2})?$/','message'=>'数据格式不对'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '行业名',
            'percent' => '提成点',
            'sort' => '排序',
                'status'=>'状态',
        ];
    }
    
    public static function getDropDownList()
    {
        $sql = 'SELECT id, name FROM ' . self::tableName() . ' ORDER BY sort ASC ';
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_KEY_PAIR);
    }
}
