<?php

namespace backend\controllers;

use Yii;
use common\models\Shipping;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ShippingFree;
use common\models\ShippingFreeRegions;
use common\models\ShippingSpecifyRegions;
use common\models\ShippingSpecifyRegionItem;
use backend\widgets\ActiveForm;
use common\helpers\Model;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\models\CategoryModel;
use common\models\Product;
use yii\web\ServerErrorHttpException;

/**
 * ShippingController implements the CRUD actions for Shipping model.
 */
class ShippingController extends Controller
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

    /**
     * Lists all Shipping models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Shipping::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Shipping model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Shipping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shipping;
        $flag = $model->loadDefaultValues();
		$modelFree = new ShippingFree();
		$modelItem = new ShippingSpecifyRegionItem();
		$modelsItem = [new ShippingSpecifyRegionItem];
		$modelsFree = [new ShippingFree];
        if ($model->load(Yii::$app->request->post())) {
        	$errors = array();
        	if ($model->is_free==1) {
        		//卖家承担运费
        	}else{
        		//自定义运费
        		$modelsItem = Model::createMultiple(ShippingSpecifyRegionItem::classname());
        		Model::loadMultiple($modelsItem, Yii::$app->request->post());        		
        		$errors = ArrayHelper::merge($errors,
        				ActiveForm::validateMultiple($modelsItem)
        		);
        		//指定包邮
        		$modelsFree = Model::createMultiple(ShippingFree::classname());
        		Model::loadMultiple($modelsFree, Yii::$app->request->post());
        		if (count($modelsFree)==0){
        			$model->free_condition=0;
        		}else{
        			$model->free_condition=1;
        			$errors = ArrayHelper::merge($errors,
        					ActiveForm::validateMultiple($modelsFree)
        			);
        		}
        			        			
        		
        	}
        	$errors = ArrayHelper::merge($errors,        			
        			ActiveForm::validate($model)
        	);
        	if (Yii::$app->request->isAjax) {
    			Yii::$app->response->format = Response::FORMAT_JSON;   				
    			return $errors;
    		}	
    		$valid = empty($errors)? true:false;  
    		if($valid){
    			$transaction = Yii::$app->db->beginTransaction();
    			try {
    				
    				if ($flag = $model->save(false)) {
    					//添加Item
    					foreach ($modelsItem as $item){
    						$item->shipping_id = $model->shipping_id;
    						if (!$flag = $item->save(false)) {
    							break;
    						}
    					}
    					if ($model->free_condition==1){
    						//添加free
    						foreach ($modelsFree as $free){
    							$free->shipping_id = $model->shipping_id;
    							if (!$flag = $free->save(false)) {
    								break;
    							}
    						}
    					}    					
    				}
    				
	    			if ($flag) {
	    				$transaction->commit();
	    				return $this->redirect(['index']);
	    			} else {
	    				$transaction->rollBack();
	    			}
	    		} catch (Exception $e) {
	    			$transaction->rollBack();
	    		}
    		}else{
        		Yii::$app->session->setFlash('error', current(current($errors)));        		
        	}
        	 return $this->render('create', [
                'model' => $model,
        		'modelFree' => $modelFree,
        		'modelItem' => $modelItem,
            		'modelsItem' => $modelsItem,
            		'modelsFree' => $modelsFree,
            ]);
    		            
        }     	
        return $this->render('create', [
        		'model' => $model,
        		'modelFree' => $modelFree,
        		'modelItem' => $modelItem,
        ]);
        
    }

    /**
     * Updates an existing Shipping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelFree = new ShippingFree();
        $modelItem = new ShippingSpecifyRegionItem();       
        $modelsFree = $model->frees;
        $modelsItem = $model->items;
       
        if ($model->load(Yii::$app->request->post())) {
        	$errors = array();
        	$oldItemIDs = ArrayHelper::map($modelsItem, 'item_id', 'item_id');
        	$modelsItem = Model::createMultiple(ShippingSpecifyRegionItem::classname(), $modelsItem,'item_id');
        	Model::loadMultiple($modelsItem, Yii::$app->request->post());
        	$deleteItemIDs = array_diff($oldItemIDs, array_filter(ArrayHelper::map($modelsItem, 'item_id', 'item_id')));
        	
        	
        	$oldFreeIDs = ArrayHelper::map($modelsFree, 'free_id', 'free_id');
        	$modelsFree = Model::createMultiple(ShippingFree::classname(), $modelsFree,'free_id');
        	Model::loadMultiple($modelsFree, Yii::$app->request->post());
        	$deleteFreeIDs = array_diff($oldFreeIDs, array_filter(ArrayHelper::map($modelsFree, 'free_id', 'free_id')));
        	if (count($modelsFree)==0){
        		$model->free_condition=0;
        	}else{
        		$model->free_condition=1;
        		$errors = ArrayHelper::merge($errors,
        				ActiveForm::validateMultiple($modelsFree)
        		);
        	}
        	$errors = ArrayHelper::merge(
        				$errors,
        				ActiveForm::validateMultiple($modelsItem),
        				ActiveForm::validate($model)
        		);
        	if (Yii::$app->request->isAjax) {
        		Yii::$app->response->format = Response::FORMAT_JSON;
        		return $errors;
        	}
        	$valid = empty($errors)? true:false;
        	if ($valid) {
        		$transaction = Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $model->save(false)) {        				
        				//item
        				$flagDelItem = true;
        				if (! empty($deleteItemIDs)) {        					
        					foreach ($deleteItemIDs as $itemId){
        						$deleteItem = ShippingSpecifyRegionItem::findOne(array('item_id'=>$itemId));
        						if (!($flagDelItem = $deleteItem->delete())){
        							break;
        						}        						
        					}
        				}
        				$flagSaveItem = true;
        				foreach ($modelsItem as $item) {
        					$item->shipping_id = $model->shipping_id;
        					$old_regions = $item->getOldAttribute('regions');
        					if (! ($flagSaveItem = $item->save(false))) {
        						break;
        					}
        				}
        				//free
        				$flagDelFree = true;
        				if (! empty($deleteFreeIDs)) {        					
        					foreach ($deleteFreeIDs as $freeId){
        						$deleteFree = ShippingFree::findOne(array('free_id'=>$freeId));
        						if (!($flagDelFree = $deleteFree->delete())){
        							break;
        						}        						
        					}
        				}    

        				$flagSaveFree = true;
        				foreach ($modelsFree as $free) {
        					$free->shipping_id = $model->shipping_id;
        					if (! ($flagSaveFree = $free->save(false))) {
        						break;
        					}
        				}
        			}
        			
        			if ($flag&$flagDelFree&$flagDelItem&$flagSaveFree&$flagSaveItem) {
        				$transaction->commit();
        				return $this->redirect(['index']);
        			}else{
        				$transaction->rollBack();
        			}
        			
        		}catch (Exception $e) {
                    $transaction->rollBack();
                }
                
        	}else{
        		Yii::$app->session->setFlash('error', current(current($errors)));        		
        	}
        	 return $this->render('update', [
                'model' => $model,
        		'modelFree' => $modelFree,
        		'modelItem' => $modelItem,
            		'modelsItem' => $modelsItem,
            		'modelsFree' => $modelsFree,
            ]);
            
        } else {
            return $this->render('update', [
                'model' => $model,
        		'modelFree' => $modelFree,
        		'modelItem' => $modelItem,
            		'modelsItem' => $modelsItem,
            		'modelsFree' => $modelsFree,
            ]);
        }
    }

    /**
     * Deletes an existing Shipping model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$transaction = Yii::$app->db->beginTransaction();
    	try {
    		$shipping = $this->findModel($id);
    		$items = $shipping->items;
    		$frees = $shipping->frees;
    		$flagDelItem = true;
    		$flagDelFree = true;
    		$flagDelShipping = true;
    		$error ='';
    		foreach ($items as $item){
    			if (!($flagDelItem = $item->delete())){
    				$error = '删除特殊运费项出错！';    				
    				break;
    			}    			
    		}
    		foreach ($frees as $free){
    			if (!($flagDelFree = $free->delete())){
    				$error = '删除免邮项出错！';
    				break;
    			}
    		}
    		Product::updateAll(array('shipping_id'=>null),array('shipping_id'=>$shipping->shipping_id));    		 			
    		if(!($flagDelShipping = $shipping->delete())){
    			$error = $shipping->getErrors('shipping_id');
    		}
    		if ($flagDelFree&&$flagDelItem&&$flagDelShipping){
    			$transaction->commit();
    			Yii::$app->session->setFlash('success', '操作成功');
    		}else{
    			$transaction->rollBack();
    			Yii::$app->session->setFlash('error', $error);
    		}	
    		
    	} catch (Exception $e) {
    		$transaction->rollBack();
    		Yii::$app->session->setFlash('error', $error);
    	}  
    	return $this->redirect(['index']);
    }

    /**
     * Finds the Shipping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Shipping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shipping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function getTemplate(){
        $id=Yii::$app->request->post('id');
        $model = Shipping::findOne(['shipping_id'=>$id]);
        return json_encode($model);
    }

}
