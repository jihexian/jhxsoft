<?php
/**
 * 
 * Author: vamper
 * DateTime: 2018/08/18
 * Description:
 */

namespace common\behaviors;


use Yii;
use yii\base\Behavior;
use common\models\Module;
use common\components\ActiveRecord;
use common\modules\promotion\models\FlashSale;
use common\logic\PromotionLogic;

/**
 * 
 */
class PromotionBehavior extends Behavior
{
	
    public $prom_status = 0;//当前prom开启状态
	public $proming_status = 0;//当前所有活动是否有正在进行的活动 1为是
	public $prom_price = 0;//所有活动中sku最低活动价
	public $prom_stock = 0;
	public $prom_sku_id = array();
	public $proms;
	
	public function events()
	{
		return [
				ActiveRecord::EVENT_AFTER_FIND => 'initProm',
		];
	}

	/**
	 * Product加入商品营销信息
	 */
	public function initProm($event){
		$promotionLogic = new PromotionLogic();
		!isset($this->owner->prom_type)? $this->prom_status=0:$this->prom_status =$promotionLogic->checkOpen($this->owner->prom_type);//查看是否开启模块
		if ($this->prom_status){
				switch ($this->owner->prom_type){
					case 1://抢购
						$now = time();
						$this->proms = FlashSale::find()->where(['in','status',[0,1]])
						->andWhere(['=','goods_id',$this->owner->product_id])
						->andWhere(['>=','end_time',$now])
						->andWhere(['>','goods_num',0])->all();//获取所有有库存的未截止的为禁止的抢购信息（包括未开始的）
						foreach ($this->proms as $v){
							if ($v->proming_status==1){
								$this->proming_status = 1; //设置抢购信息
								if ($v->price<$this->prom_price||$this->prom_price==0) {
								    $this->prom_price = $v->price;
								}
								$this->prom_sku_id = $v->sku_id;
								break;
							}
						}
						
						break;
					case 2:
						break;
					case 3:
						break;
					case 4:
						break;
					default:
						break;
				}			
			}
	}
	
}