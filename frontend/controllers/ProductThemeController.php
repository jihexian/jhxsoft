<?php
namespace frontend\controllers;
use Yii;
use common\models\ProductSearch;
use frontend\common\controllers\Controller;
use common\models\Carousel;
use common\models\CarouselItem;
use common\logic\ProductThemeLogic;
use common\models\ProductTheme;

class ProductThemeController extends Controller
{
    
    public function actionIndex(){
        //轮播图
      $carousel = Carousel::find()->where(array('key'=>'theme'))->one();
        $model=new CarouselItem();
        $carousels=$model->getCarousels($carousel->id,4,1);
        $productThemeLogic = new ProductThemeLogic();
        $lists = $productThemeLogic->getThemeList(8);
        return $this->render('index',
                [
                        'carousels'=>$carousels,
                        'lists'=>$lists
                ]); 
 
    }

    
    /**
     * 第一书记
     *
     * @return mixed
     */
    public function actionFirst()
    {
        $data=Yii::$app->request->get();
        $data['status']=1;
        $data['num']=10;
        $data['first']=1;
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($data);
        $products = $dataProvider->getModels();
        
        //轮播图
        $carousel = Carousel::find()->where(array('key'=>'frist'))->one();
        $model=new CarouselItem();
        $carousels=$model->getCarousels($carousel->id,4,1);
        return $this->render('first',[
                'carousels'=>$carousels,
                'products'=>$products,
                'num'=>$data['num'],
        ]);
    }
    

    public function actionInfo(){
        $data=Yii::$app->request->get();
        $id = $data['id'];
        $theme = ProductTheme::findOne($id);
        $data['theme_id'] = $id;
        $data['status']=1;
        $data['num']=10;
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $sort->defaultOrder = array('hot'=>SORT_DESC,'up_time'=>SORT_DESC);
        $dataProvider->setSort($sort);
        $products = $dataProvider->getModels();
        return $this->render('info',[
                'products'=>$products,
                'theme'=>$theme,
        ]
      );
    }
    
   
    
    
    
    
}
