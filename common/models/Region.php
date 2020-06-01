<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $level
 * @property integer $parent_id
 * @property integer $status
 * @property integer $sort
 * @property string $key
 */
class Region extends \yii\db\ActiveRecord
{
   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'sort','is_hot'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 100],
            [['level'], 'string', 'max' => 4],
            [['firstKey'], 'string', 'max' => 1],
            [['name'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', '表id'),
            'name' => Yii::t('backend', '地区名称'),
            'level' => Yii::t('backend', '地区等级 分省市县区'),
            'parent_id' => Yii::t('backend', '父id'),
            'status' => Yii::t('backend', '状态'),
            'sort' => Yii::t('backend', '排序'),
            'firstKey' => Yii::t('backend', '首字母'),
            'is_hot'=>Yii::t('backend', '热点城市'),
            'code'=>'编号'
        ];
    }
    /**
     * 获取光省份/自治区数据
     * @return array[]|\yii\db\ActiveRecord[][]
     */
    public   function getProvinceList(){
        $list=self::find()->where(['status'=>1,'level'=>1])->select(['id','name'])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])->cache(365*30*24*3600)->all();
        $rs=array();
        foreach ($list as $row){
            $rs[$row["id"]] = $row;
        }
        return $rs;
    }
    /**
     * 获取所有城市数据
     * @return array[]|\yii\db\ActiveRecord[][]
     */
    public  function getCityList(){
        $list=self::find()->where(['status'=>1,'level'=>2])->select(['id','name','firstKey'])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])->cache(365*30*24*3600)->all();
        $rs=array();
        foreach ($list as $row){
            $rs[$row['firstKey']][] = $row;
        }
        return $rs;
    }
    
    /**
     * 获取所有城市数据-根据字母分类
     * @return array[]|\yii\db\ActiveRecord[][]
     */
    public function getCityGroupByKey(){
        $list=self::find()->where(['status'=>1,'level'=>2])->select(['id','name','firstKey'])->orderBy([ 'firstKey' => SORT_ASC])->cache(365*30*24*3600)->all();
        $rs=array();
        foreach ($list as $row){
            $rs[$row['firstKey']][]= $row;
        }
        return $rs;
    }
    
    /**
     * 通过省份获取城市列表
     */
    public function getCityListByProvince($provinceId = 0){
        $list=self::find()->where(['status'=>1,'level'=>2,'parent_id'=>$provinceId])->select(['id','name'])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])->cache(365*30*24*3600)->all();
        return $list;
    }
    
    public function getHotCity(){
        $list=self::find()->where(['status'=>1,'level'=>2,'is_hot'=>1])->select(['id','name'])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])->cache(365*30*24*3600)->all();
        return $list;
    }
    public static function getRegions($id=null,$parent_id=null,$level=null){
        if (isset($id)) {
            $result = self::find()->where(['id'=>$id])->asArray()->all();
        }else if (isset($parent_id)) {
            $result = self::find()->where(['parent_id'=>$parent_id])->asArray()->all();
        }else{
            $result = [];
        }
        return ArrayHelper::map($result, "id", "name");
    }
    /**
     * 获取所有子类目.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSons()
    {
       // return $this->hasMany(self::className(), ['parent_id' => 'id'])->onCondition(['level' =>[1,2]]);
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }
    /**
     * 获取最顶级分类id
     * @param int $id
     * @return int $num
     */
    public static function topId($id){
        $query = static::find()->where(['id'=>$id])->one();
        if(!empty($query)){
            if($query['parent_id']!=0){
                $num=self::topId($query['parent_id']);
            }else{
                $num= $query['id'];
            }
        }else{
            $num= $id;
        }
        return $num;
    }
 
  
}
