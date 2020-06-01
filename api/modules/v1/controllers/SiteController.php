<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 2017/4/14
 * Time: 下午10:49
 */

namespace api\modules\v1\controllers;


use api\common\controllers\Controller;

use api\modules\v1\models\Article;
use common\models\Carousel;
use common\models\CarouselItem;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Category;
use yii\helpers\ArrayHelper;

use yii;

class SiteController extends Controller
{

   private $num;
    public function actionIndex()
    {
        $query = CarouselItem::find()
            ->joinWith('carousel')
            ->where([
                '{{%carousel_item}}.status' => 1,
                '{{%carousel}}.status' => Carousel::STATUS_ACTIVE,
                '{{%carousel}}.key' => 'index',
            ])
            ->orderBy(['sort' => SORT_ASC]);
        $carousels = [];
        foreach ($query->all() as $k => $item) {
            $carousels[$k]['title'] = $item->caption;
            $carousels[$k]['image'] = $item->image->url;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Article::find()->published(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return [
            'carousels' => $carousels,
            'articleList' => $this->serializeData($dataProvider)
        ];
    }
    
    public function actionArticles($num=4,$isTop=0,$category_id=0)
    {
    	$query = Article::find()->published();
    	if ($isTop){
    		$query->andWhere(['is_top'=>1]);
    	}
    	if($category_id){
            $query->andWhere(['category_id'=>$category_id]);
        }
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

    /**
     * 获取全部的分类
     */
    public function actionCategory($num=10,$isTop=0){


        $query = Category::find()->where(['status'=>1,'module'=>'base','pid'=>0]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' =>$num,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    'sort' => SORT_ASC
                ]
            ]
        ]);
    }
    public function actionMore($num=10,$isTop=0,$category_id=0)
    {

        $query = Article::find()->published();
        if ($isTop){
            $query->andWhere(['is_top'=>1]);
        }

        if($category_id){
            $query->andWhere(['category_id'=>$category_id]);
        }
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
       // return $dataProvider;
        $row[0]=Category::find()->where(['status'=>1,'id'=>$category_id])->asArray()->one();
        $totalCount=$dataProvider->getTotalCount();
        $total_pages=ceil($totalCount/$num);
        $row[0]['data']=$dataProvider->getModels();
        $page=Yii::$app->request->get('page');
        if(!$page){
            $current_page=1;
        }else if($page>$total_pages){
            $current_page=$total_pages;
        }else{
            $current_page=$page;
        }
        if($total_pages>=$page){

            $row[0]['total_page']=$total_pages;
            $row[0]['current_page']=$current_page;
        }else{
            $row[0]['total_page']=1;
            $row[0]['current_page']=1;
            $row[0]['data']=array();
        }
        return  $row;
    }



}