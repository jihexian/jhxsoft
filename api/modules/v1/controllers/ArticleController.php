<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v1\controllers;


use api\common\controllers\Controller;
use api\modules\v1\models\Article;
use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
class ArticleController extends Controller
{

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
				[
						'class' => QueryParamAuth::className(),
						'tokenParam' => 'token',
						'optional' => [
								'index',
								'get-cate-name',
								'view'
						]
				]
		]);
	}
    /**
     * @api {get} /v1/articles 文章列表
     * @apiVersion 1.0.0
     * @apiName index
     * @apiGroup Article
     *
     * @apiParam {Integer} [cid] 分类ID.
     * @apiParam {String} [module]  模块类型
	   @apiParam {int} [num]  请求数量
	    @apiParam {bool} [childs]  是否获取子分类下的文章
		*
     */
    public function actionIndex($cid=null, $module='base',$num=4,$childs=true,$title=null)
    {
		if($childs)  //是否获取子分类下的文章
			$cid=$this->getchild($cid);
		$query = Article::find()->published()
           ->andFilterWhere(['category_id'=>$cid])  //当cid为数组时会启用in筛选条件
           ->andFilterWhere(['module' => $module])
		   ->andFilterWhere(['like' ,'title', $title]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ],
        	'pagination' => [
				'pageSize' =>$num,
			],
        ]);
        return $dataProvider;
		
    }
	/*获取子分类*/
	private function getchild($cid){
		$cate=new Category();
		$c=$cate->getChilds($cid);
		foreach($c as $key=>$v)
			$childArr[$key]=$v['id'];
		if(!empty($childArr))
			array_push($childArr,$cid);
		else
			$childArr[0]=$cid;
		return $childArr;
	}
	public function actionGetCateName()
	{
		$request = Yii::$app->request;
		$cid=$request->post("cid");
		$cate=new Category();
		$c=$cate->getPtitle($cid);
		return $c;
	}
    /**
     * @api {get} /v1/articles/id:\d+ 文章内容
	   @$addPrefix bool 是否添加图片地址前缀
     * @apiVersion 1.0.0
     * @apiName view
     * @apiGroup Article
     *
     */
    public function actionView($id,$addPrefix=true)
    {
        request()->setQueryParams(['expand'=>'data']);
        $model = Article::find()->published()->where(['id' => $id])->with('data')->one();
        if ($model === null) {
            return ['status'=>0,'message'=>'文章不存在'];
        }
        $model->addView();
               
		if($addPrefix&&$model->module=='base'){
			$model->data->content=$this->replaceImgUrl($model->data->content);
		}elseif ($addPrefix&&$model->module=='photo'){
			$model = $this->serializeData($model);
			foreach ($model['data']['photos'] as &$v){
				empty($v['thumbImg'])? $v['thumbImg']=Yii::$app->params['defaultImg']['default']:$v['thumbImg']=$v['thumbImg'];
				empty($v['url'])? $v['url']=Yii::$app->params['defaultImg']['default']:$v['url']=$v['url'];
			}
		}
			
        return $model;
       
    }
	private function replaceImgUrl($content){
		preg_match_all("/<img(.*)src=\"(\/[^\"]+)\"[^>]+>/isU",$content,$matches); //匹配已/正斜杠开头的图片地址，如果本身是http开头的网络地址无需加上域名
           $img = "";  
            if(!empty($matches)) {  
            //注意，上面的正则表达式说明src的值是放在数组的第三个中  
                $img = $matches[2];  
            }else {  
                $img = "";  
            }  
            if (!empty($img)) {  
                $img_url = Yii::$app->params['domain'];  
                $patterns= array();  
                $replacements = array();  
                foreach($img as $imgItem){  
					$final_imgUrl = $img_url.$imgItem;  
					$replacements[] = $final_imgUrl;  
					$img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";  
					$patterns[] = $img_new;  
                }  
				//让数组按照key来排序  
				ksort($patterns);  
				ksort($replacements);
				$replace_content = preg_replace($patterns, $replacements, $content); //存在图片则在图片地址前面加上域名
			}else
				$replace_content =$content;  //如果没有图片直接返回整个原文*/
		return $replace_content;
	}
	
	
	
}