<?php

namespace common\models;

use common\behaviors\PositionBehavior;
use common\behaviors\CacheInvalidateBehavior;
use common\modules\attachment\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\behaviors\CheckShopBehavior;

/**
 * This is the model class for table "carousel_item".
 *
 * @property integer $id
 * @property integer $carousel_id
 * @property string $image
 * @property string $url
 * @property string $caption
 * @property integer $status
 * @property integer $sort
 *
 * @property Carousel $carousel
 */
class CarouselItem extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%carousel_item}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $key = array_search('carousel_id', $scenarios[self::SCENARIO_DEFAULT], true);
        $scenarios[self::SCENARIO_DEFAULT][$key] = '!carousel_id';
        return $scenarios;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'sort',
                'groupAttributes' => ['carousel_id']
            ],
            'cacheInvalidate' => [
                'class' => CacheInvalidateBehavior::className(),
                'keys' => [
                    function ($model) {
                        return [
                            Carousel::className(),
                            $model->carousel->key
                        ];
                    }
                ]
            ],
          CheckShopBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['carousel_id'], 'required'],
            [['carousel_id', 'status', 'sort','shop_id'], 'integer'],
            [['url', 'caption'], 'string', 'max' => 1024],
            ['image', 'safe'],
			['thumbImg','string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'carousel_id' => Yii::t('common', 'Carousel ID'),
            'image' => Yii::t('common', 'Image'),
            'url' => Yii::t('common', 'Url'),
            'caption' => Yii::t('common', 'Caption'),
            'status' => '是否启用',
            'sort' => '排序',
			'thumbImg' => '缩略图',
        ];
    }

    public function attributeHints()
    {
        return [
            'url' => '格式: /site/index a=1&b=2',
            'sort'=>'数字越小越排在前面',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarousel()
    {
        return $this->hasOne(Carousel::className(), ['id' => 'carousel_id']);
    }
	/*自己写的获取幻灯片方法*/
	public function getCarousels($id,$num,$shopId=null)
    {
	    if (empty($shopId)) {
	        $data=(new \yii\db\Query())->from($this->tableName())->where(['status' =>1,'carousel_id'=>$id])->orderBy([
	                'sort' => SORT_ASC,
	                'id' => SORT_DESC,
	        ])->limit($num)->all();
	    }else{
	        $data=(new \yii\db\Query())->from($this->tableName())->where(['status' =>1,'carousel_id'=>$id,'shop_id'=>$shopId])->orderBy([
	                'sort' => SORT_ASC,
	                'id' => SORT_DESC,
	        ])->limit($num)->all();
	    }
		
		return $data;
	}
}
