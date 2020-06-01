<?php
/**
 * author: vamper
 * Date: 2018/11/10
 */
namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class CheckShopBehavior extends Behavior
{
    public function events()
    {
        
        $module = Yii::$app->id;
        if ($module=='backend'||$module=='seller') {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'initShop',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'checkShop',
                ActiveRecord::EVENT_BEFORE_DELETE => 'checkShop',
            ];
        }else{
            return [];
        }
        
    }
    
    public function checkShop($event){
        
      
        $session = \Yii::$app->session;
        $shopId = $session->get("shop_id");
        if (($this->owner->getOldAttribute('shop_id')!=$this->owner->shop_id)||(!empty($shopId)&$shopId!=$this->owner->shop_id)) {
            $event->isValid = false;
            $this->owner->addError('shop_id','不属于你的店铺,更新失败');
        }  
    }
    
    public function initShop($event){
        $session = \Yii::$app->session;
        $shopId = $session->get("shop_id");
        $this->owner->shop_id = $shopId;        
    }
    
    
}
