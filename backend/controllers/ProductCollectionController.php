<?php

namespace backend\controllers;

use Yii;
use common\models\ProductSearch;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use common\models\SkuItem;
use backend\models\Product;
use backend\models\Skus;
use backend\models\Attribute;
use backend\models\AttributeValue;
use common\helpers\Model;
use common\models\ProductModelAttr;
use common\models\CategoryModelAttr;
use common\models\ProductType;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use backend\widgets\ActiveForm;
use yii\helpers\Url;
use common\logic\PromotionLogic;
use common\models\StoreStock;
use Endroid\QrCode\QrCode;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            
            'ajax-update-field' => [
                'class' => 'common\\actions\\AjaxUpdateFieldAction',
                'allowFields' => ['hot'],
                'findModel' => [$this, 'findModel']
            ],
            'switcher' => [
                'class' => 'backend\widgets\grid\SwitcherAction'
            ]
        ];
    }
    
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        Url::remember();
        $searchModel = new ProductSearch();
        Yii::$app->request->setQueryParams(ArrayHelper::merge(["shop_id"=>Yii::$app->session->get('shop_id')], Yii::$app->request->queryParams));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    
    
 
    
}
