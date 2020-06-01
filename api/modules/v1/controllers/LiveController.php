<?php
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use api\common\controllers\XcxApi;
use Yii;
use common\models\Live;
use yii\data\ActiveDataProvider;
class LiveController extends Controller{
    public function init(){
        parent::init();
        $live_data=yii::$app->cache->get('live_data');
        if($live_data!=1){
            $model=new XcxApi();
            $model->UpdateLive();
            yii::$app->cache->set('live_data', 1,300);
        }
    }
    /**
     * 获取直播列表
     * @param $live_status直播状态 101: 直播中, 102: 未开始, 103: 已结束, 104: 禁播, 105: 暂停中, 106: 异常, 107: 已过期
     * @return mixed[]
     */
    public function actionIndex($live_status=null,$num=10){
  
        if($live_status!=null){
            $query =Live::find()->where(['status'=>1,'live_status'=>$live_status]);
        }else{
            $query =Live::find()->where(['status'=>1]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                    'status'=> SORT_ASC,
                    'start_time' => SORT_DESC,
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [ 
                'pageSize' =>$num,
                'validatePage'=>false
            ],
        ]);
        return $dataProvider;
    }
/*     public function actionList(){
        $model=new XcxApi();
        $page=Yii::$app->request->get('page',1);
        $num=Yii::$app->request->get('num',20);
        if($page<1){
            $page=1;
        }
        $post_data = array(
            "start" => ($page-1)*20,
            "limit"=>$num
        );
        $data=$model->getLiveList($post_data);
        return ['items'=>$data];
    } */
    
    
    
    /**
     * 获取指定直播房间录像
     * @return number[]|string[]|number[]|mixed[]
     */
    public function actionRecord(){
        $id=Yii::$app->request->post('id');
        if(!$id){
            return ['status'=>0,'msg'=>'缺少房间号id！'];
        }
        return ['status'=>1,'items'=>$data];
    }

}

?>