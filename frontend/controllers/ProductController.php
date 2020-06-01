<?php
namespace frontend\controllers;

use common\models\ProductSearch;
use Yii;
use frontend\common\controllers\Controller;
use common\models\Product;
use common\logic\ProductLogic;
use common\models\Collection;
use common\logic\ShareLogic;
use common\logic\CartLogic;
use yii\data\Pagination;
use yii\helpers\Url;
use common\components\weixin\BaseWechat;
use yii\helpers\Json;
use common\models\ProductComment;
use common\models\Carousel;
use common\models\CarouselItem;
use common\models\ProductCommentSearch;
use common\logic\CommentLogic;
use yii\rest\Serializer;
use yii\web\NotFoundHttpException;
use common\models\ProductType;
/**
 * Product controller.
 */
class ProductController extends Controller
{

    /**
     * 商品列表页.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $data=Yii::$app->request->get();
        $data['status']=1;
        $data['num']=10;
        $data['shop_status']=1;
        $searchModel = new ProductSearch();
        $type_id=yii::$app->request->get('type_id');
        if($type_id){
           $type=ProductType::find()->where(['status'=>1,'parent_id'=>$type_id])->all();
           if(!empty($type)){
               yii::$app->session->set('cate', $type);
           }
        }else{
            $cate=yii::$app->session->set('cate',array());
        }
        $cate=yii::$app->session->get('cate');
        $dataProvider = $searchModel->search($data);
        $products = $dataProvider->getModels();
        return $this->render('index',[
            'products'=>$products,
            'num'=>$data['num'],
             'type'=>$cate,
        ]);
    }




    /**
     * 商品详情页.
     *
     * @return mixed
     */
    public function actionDetail()
    {
        Url::remember();
        $productId = Yii::$app->request->get('id');
        $product =Product::find()->alias('p')->joinWith(['shop s'])->where(['product_id'=>$productId,'p.is_del'=>0,'p.status'=>1,'s.status'=>1])->one();
        if($product&&empty($product['plus_price'])){
            $minPlusPrice=0;
            foreach($product['skus'] as $sku){
                if(!empty($sku->plus_price)&&$sku->plus_price>0){
                    if($minPlusPrice==0){
                        $minPlusPrice = $sku->plus_price;
                    }
                    if ($minPlusPrice>$sku->plus_price){
                        $minPlusPrice = $sku->plus_price;
                    }
                }
            }
        }else{
            $minPlusPrice=$product['plus_price'];
        }
  
        if (! $product) {
           throw new NotFoundHttpException('商品不存在或者已下架');
        }
        $productLogic = new ProductLogic();
        $data = $productLogic->getDetail($productId);
        //判断喜欢
        $member_id = Yii::$app->user->id;
        if (! empty($member_id)) {
            $collection = Collection::find()->where(array(
                'member_id' => $member_id,
                'product_id' => $productId
            ))->one();
            $data['isFavorite'] = empty($collection) ? 0 : 1;
        } else {
            $data['isFavorite'] = 0;
        }
      
        //购物车总数
        $cartLogic = new CartLogic();
        $num = $cartLogic->getNum($member_id);
        
        foreach ($data['skus'] as &$sku){
            unset($sku['sku_values']);
        }
        //没登录且通过分享链接进入，把pid存到session
        $pid=yii::$app->request->get('pid','');
        if (Yii::$app->user->isGuest&&!empty($pid)) {
                yii::$app->session->set('pid', $pid);
        }
        //判断是否是微信浏览器
        if (strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
        //jssdk微信分享处理
            $url=yii::$app->request->getHostInfo().yii::$app->request->url;
            $weixin=new BaseWechat();
            $jssdk=$weixin->getJsSign($url);
            return $this->render('detail', [
                    'data' => $data,
                    'num'=>$num,
                    'jssdk'=>$jssdk,
            ]);
        }
        return $this->render('detail', [
            'data' => $data,            
            'num'=>$num,
            'plus_price'=>$minPlusPrice,
        ]);
    }

    
    public function actionComment(){
        $data=Yii::$app->request->post();
        $data['pid'] = 0;
        $data['status'] = 1;

        $data['num']=7;

        $searchModel = new ProductCommentSearch();
        $dataProvider = $searchModel->search($data);
        $comment = $dataProvider->getModels();
        foreach ($comment as $key=>$vo){
//             $image=$vo['image'];
//             if(count($image)>0){
//                 foreach ($image as $kk=>$va){
//                     $image[$kk]['url']=$va['url']!=''?Yii::$app->params['domain'].$va['url']:'';
//                     $image[$kk]['thumbImg']=$va['thumbImg']!=''?Yii::$app->params['domain'].$va['thumbImg']:'';
//                 }
//             }
            //$comment[$key]['image']=$image;
            $arr=$this->ch2arr($vo['member']['username']);
            $name=reset($arr).'**'.end($arr);
            $row=['username'=>$name,'avatarUrl'=>$vo['member']['avatarUrl']];
            $comment[$key]['created_at']=date('Y-m-d',$vo['updated_at']); 
            $comment[$key]['goods_id'] = $vo['product'];
            $comment[$key]['member_id']=$row;
         
        }
        //获取页数
        $pagecount=$dataProvider->getTotalCount();
        $pagecount=ceil($pagecount/$data['num']);
        //获取好评差评中评数
        $commentLogic = new CommentLogic();
        $commentCount = $commentLogic->getCounts($data['goods_id']);
        $serializer = new Serializer();
        return Json::encode(array_merge(['items'=>$serializer->serialize($dataProvider),'pages'=>$pagecount],['comment_count'=>$commentCount]));
        
    }
    function ch2arr($str)
    {
        $length = mb_strlen($str, 'utf-8');
        $array = [];
        for ($i=0; $i<$length; $i++)
            $array[] = mb_substr($str, $i, 1, 'utf-8');
            return $array;
    }

    /**
     * 拼团规则
     * @return string
     */
    public function actionPintuanRule()
    {   
        return $this->render('pintuan_rule');
    }
    /**
     * 拼团详情
     * @return string
     */
    public function actionPintuanDetail()
    {   
        return $this->render('pintuan_detail');
    }
}
