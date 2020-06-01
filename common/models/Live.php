<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use common\behaviors\PositionBehavior;
use Yii;
/**
 * This is the model class for table "{{%live}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $roomid
 * @property string $cover_img
 * @property integer $live_status
 * @property integer $start_time
 * @property integer $end_time
 * @property string $anchor_name
 * @property string $anchor_img
 * @property string $goods
 * @property string $live_replay
 * @property integer $is_top
 * @property integer $is_del
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 */
class Live extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%live}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roomid', 'live_status'], 'required'],
            [['id', 'roomid', 'live_status', 'start_time', 'end_time', 'is_top', 'is_del', 'sort'], 'integer'],
            [['goods', 'live_replay'], 'string'],
            [['name', 'anchor_name'], 'string', 'max' => 145],
            [['cover_img', 'anchor_img','share_img'], 'string', 'max' => 512],
            ['sort','default','value'=>99],
            [['roomid'], 'unique'],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            TimestampBehavior::className(),
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'sort',
            /*     'groupAttributes' => [
                    'pid'
                ], */
            ],
            ];
        return $behaviors;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', '标题'),
            'roomid' => Yii::t('common', '直播间id'),
            'share_img'=>Yii::t('common', '封面'),
            'cover_img' => Yii::t('common', '背景图'),
            'live_status' => Yii::t('common', '直播状态'),// 101: 直播中, 102: 未开始, 103: 已结束, 104: 禁播, 105: 暂停中, 106: 异常, 107: 已过期
            'start_time' => Yii::t('common', '直播计划开始时间'),
            'end_time' => Yii::t('common', '直播计划结束时间'),
            'anchor_name' => Yii::t('common', '主播名'),
            'anchor_img' => Yii::t('common', '主播头像'),
            'goods' => Yii::t('common', '商品'),
            'live_replay' => Yii::t('common', '回放内容'),
            'is_top' => Yii::t('common', '置顶'),
            'is_del' => Yii::t('common', '状态'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'sort' => Yii::t('common', '排序'),
        ];
    }
    public function get_status($live_status){
        switch ($live_status){
            case 101:$str='直播中';break;
            case 102:$str='未开始';break;
            case 103:$str='已结束';break;
            case 104:$str='禁播';break;
            case 105:$str='暂停中';break;
            case 106:$str='异常';break;
            case 107:$str='已过期';break;
            default:$str='异常';break;
        }
        return $str;
    }
}
