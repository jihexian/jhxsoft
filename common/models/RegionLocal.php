<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%region_local}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $parent_id
 */
class RegionLocal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region_local}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'parent_id'], 'required'],
            [['code', 'name', 'parent_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '编码',
            'name' => '名称',
            'parent_id' => '父级',
        ];
    }
    
    public static function getDropDownList($tree = [], &$result = [], $deep = 0, $separator = '--')
    {
        $deep++;
        foreach($tree as $list) {
            $result[$list['id']] = str_repeat($separator, $deep-1) . $list['name'];
            if (isset($list['children'])) {
                self::getDropDownList($list['children'], $result, $deep);
            }
        }
        return $result;
    }
    
    public static function lists()
    {
       // $list=false;
        $list = Yii::$app->cache->get('regionLocal');
        if ($list === false) {
            $query = static::find();
           
            $list = $query->asArray()->all();
            Yii::$app->cache->set('regionLocal', $list);
            
        }
        return $list;
    }
    public function beforeDelete(){
        
        $subCount = $this->find()->where(['parent_id'=>$this->id])->count();
        if($subCount>0){
            $this->addErrors(['id'=>'该分类下有子分类，请先删除子分类！']);
            return false;
        }        
        
        return parent::beforeDelete();
    }
    
    public function getRegion() {
        return $this->hasOne(RegionLocal::className(), ['id' => 'parent_id']);
    }
  
}
