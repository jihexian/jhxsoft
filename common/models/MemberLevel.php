<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_level}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property integer $sort
 * @property integer $status
 * @property integer $create_at
 */
class MemberLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_level}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'sort', 'status', 'create_at'], 'integer'],
			['sort','default', 'value' => '99'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父级ID',
            'name' => '会员级别',
            'sort' => '排序',
            'status' => '状态',
            'create_at' => '创建时间',
        ];
    }
    /*获取所有父级分类，按排序排序*/
    public static function GetParentOrderBySort($pid=0){
        $level=(new \yii\db\Query())->from(self::tableName())->where(['pid' => 0,'status'=>1])->orderBy("sort asc")->all();
        return $level;
    }
    /*通过会员等级ID获取会员等级名称*/
    public static function GetNameById($id){
        $name=(new \yii\db\Query())-select("name")->from(self::tableName())->where(['id' => $id])->findOne();
        return $name;
    }
}
