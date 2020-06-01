<?php
namespace common\components\job;
use common\logic\CommentLogic;
use common\models\OrderSku;
use yii\base\BaseObject;
use yii\helpers\Json;

class JobProductComment extends BaseObject implements \yii\queue\JobInterface
{
    public $data;    
    
    public function execute($queue)
    {
        $this->comment($this->data);
    }
        
    private function comment($data){
        $commentLogic = new CommentLogic();
        $result = $commentLogic->addComment($data);       
        echo Json::encode($result);
    }    
   
}