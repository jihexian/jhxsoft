<?php

namespace common\modules\config\models;

use common\behaviors\CacheInvalidateBehavior;
use common\modules\attachment\models\Attachment;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property string $type
 * @property string $desc
 */
class Config extends \yii\db\ActiveRecord
{
    const TYPE_ARRAY = 'array';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'type'], 'required'],
            [['name', 'group'], 'string', 'max' => 50],
            ['type', 'in', 'range' => array_keys(self::getTypeList())],
            ['value', 'filter', 'filter' => function ($val) {            	
                if ($this->type == 'checkbox') {
                    return serialize($val);
                }
                return $val;
            }, 'skipOnEmpty' => true],
            [['value', 'description', 'extra'], 'string'],
            [['value'], 'checkExchange','skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        if ($this->type == 'checkbox' && !empty($this->value)) {
            $this->value = unserialize($this->value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '配置名',
            'value' => '配置值',
            'description' => '配置描述',
            'type' => '配置类型',
            'extra' => '配置项',
            'group' => '分组',
        ];
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => CacheInvalidateBehavior::className(),
                'tags' => [
                    \Yii::$app->config->cacheTag
                ]
            ]
        ];
    }

    public static function getTypeList()
    {
        return \Yii::$app->config->get('config_type_list');
    }
    public function checkExchange($attribute, $params)
    {
    	if ($this->name=='site_credits_exchange'){
    		if (!is_numeric($this->value)||strpos($this->value,'.')){
    			$this->addError('value','积分必须为整数');
    		}    		
    	} 
    	if ($this->name=='distribut'){
    	    if (!preg_match("/^\d+%-\d+%$/", $this->value)) {
    	        $this->addError('value','分销比例格式不正确！');
    	    }
    	    $percents = explode("-", str_replace("%", "", $this->value));    	    
    	    if ($percents[0]>100||$percents[1]>100||$percents[0]+$percents[1]>100) {
    	        $this->addError('value','一级分销比例只能设置为0%-100%');
    	    }
    	    if ($percents[1]>100) {
    	        $this->addError('value','二级分销比例只能设置为0%-100%');
    	    }
    	    if ($percents[0]+$percents[1]>100) {
    	        $this->addError('value','一级分销比例加二级分销比例最高只能为100%');
    	    }
    	    

    	} 
    }
}
