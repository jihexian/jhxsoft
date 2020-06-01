<?php

namespace backend\controllers;

use Yii;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use backend\models\Product;
use backend\models\Skus;
use backend\models\Attribute;
use backend\models\AttributeValue;
use common\models\ProductModelAttr;
use common\models\CategoryModelAttr;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\logic\PromotionLogic;
use backend\models\search\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\web\Response;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class PlatformController extends Controller
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
            
//             'ajax-update-field' => [
//                 'class' => 'common\\actions\\AjaxUpdateFieldAction',
//                 'allowFields' => ['hot'],
//                 'findModel' => [$this, 'findModel']
//             ],
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 回收站列表
     *
     * @return mixed
     */
    public function actionTrash()
    {
        $query = \backend\models\Product::find()->onlyTrashed();
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                        'defaultOrder' => [
                                'product_id' => SORT_DESC
                        ]
                ]
        ]);
        return $this->render('trash',[
                'dataProvider' => $dataProvider
        ]);
    }
    
    
    
    /**
     * 还原
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionReduction()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = Product::find()->where(['product_id' => $id])->onlyTrashed()->one();
        if(!$model) {
            throw new NotFoundHttpException('不存在!');
        }
        $model->restore();
        return [
                'message' => '操作成功'
        ];
    }
    
    /**
     * 彻底删除
     * @return array
     * @throws NotFoundHttpException
     * @throws \Exception
     */
   /*  public function actionHardDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = Product::find()->where(['product_id' => $id])->onlyTrashed()->one();
        if(!$model) {
            throw new NotFoundHttpException('不存在!');
        }
        $model->delete();
        return [
                'message' => '操作成功'
        ];
    } */
    
    /**
     * view an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        Url::remember();
    	$model = $this->findModel($id);//product   
    	$promotionLogic = new PromotionLogic();
    	$promFlag = $promotionLogic->checkProm($id);
    	if ($promFlag){
    		Yii::$app->session->setFlash('error', '该商品正在参加营销活动，无法修改');
    		return $this->actionIndex();
    	} 	
    	$skuList = Skus::find()->where(['product_id'=>$id])->all();//商品的sku列表
    	$skus = Skus::find()->where(['product_id'=>$id])->asArray()->all();
    	$attributeArray = array();//临时对象
    	$attributeValueArray = array();//临时对象
    	$oldSkus = array();
    	$attributeValuesIds = array();////临时对象
    	foreach ($skus as &$sku){
    		unset($sku['sku_values']);//js部分需要去掉sku_values json值，否则解析不了
    		$skuId = $sku['sku_id'];
    		$oldSkus[$skuId] = $skuId;
    		$skuValuesId = explode('_', $skuId);//每个sku的value值数组
    		unset($skuValuesId[0]);//去掉prodcut_id    		
    		foreach ($skuValuesId as $k => $valuesId){
    			if (in_array($valuesId,$attributeValuesIds)){
    				continue;
    			}else{
    				$v = AttributeValue::find()->where(['value_id'=>$valuesId])->one();
    				array_push($attributeValuesIds, $valuesId);
    				if (!isset($attributeValueArray[$k])){
    					$attributeValueArray[$k] = array();
    				}
    				array_push($attributeValueArray[$k], $v);
    				if (!isset($attributeArray[$k])){
    					$attributeArray[$k] = Attribute::find()->where(['attribute_id'=>$v->attribute_id])->one();
    				}    				
    			}
    		}
    	}
    	
    	$attributes = array_values($attributeArray);//该商品的规格
    	$attributeValues = array();//该商品的规格值
    	foreach ($attributeValueArray as $key=>$values){
    		$attributeValues = ArrayHelper::merge($attributeValues, $values);
    	}
    
    	$productModelAttrs = ProductModelAttr::find()->where(['product_id'=>$id])->all();
    	$oldProductModelAttrs= array();
    	foreach ($productModelAttrs as $productModelAttr){
    		$productModelAttrId = $productModelAttr->product_model_attr_id;
    		$oldProductModelAttrs[$productModelAttrId] = $productModelAttrId;
    	}
    	$modelCategoryModelAttr = CategoryModelAttr::find()->where(['model_id'=>$model->model_id])->all();
    	$attribute = new Attribute;
    	$attributeValue = new AttributeValue();
    	$modelSkus = new Skus();
    	$modelProductModelAttr = new ProductModelAttr();
    	 
    	
    	
    	return $this->render('view', [
    			'model' => $model,
    			'attribute' => $attribute,
    			'attributeValue' => $attributeValue,
    			'modelSkus' => $modelSkus,
    			'skus'=>json_encode($skus),
    			'skuList'=>$skuList,
    			'attributes'=>$attributes,
    			'attributeValues'=>$attributeValues,
    			'modelProductModelAttr' => $modelProductModelAttr,
    			'modelCategoryModelAttr' => $modelCategoryModelAttr,
    			'productModelAttrs' =>$productModelAttrs
    	]);
    }
    
    public function actionUpdate($id){
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $model->detachBehavior('checkShopBehavior');
            $status = Yii::$app->request->post('Product')['status'];
            $model->status = $status;
            $flag = $model->save();
            if ($flag) {
                Yii::$app->session->setFlash('success','修改成功！');
                return $this->redirect('index');
            }else {
                Yii::$app->session->setFlash('error',current($model->getErrors()));
            }
        }
        return $this->render('update',['model'=>$model]);
    }
    


    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//     public function actionDelete($id)
//     {
//     	$model = $this->findModel($id);
//         $model->status = 0;
//         $model->save();
//         return $this->redirect(['index']);
//     }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
