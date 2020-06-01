<?php

namespace backend\controllers;

use Yii;
use common\models\CategoryModel;
use yii\data\ActiveDataProvider;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\CategoryModelAttr;
use yii\helpers\ArrayHelper;
use common\helpers\Model;
use yii\web\Response;
use common\models\CategoryModelAttrValue;
use backend\widgets\ActiveForm;
/**
 * CategoryModelController implements the CRUD actions for CategoryModel model.
 */
class CategoryModelController extends Controller
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
     * Lists all CategoryModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CategoryModel::find()->where(['shop_id'=>Yii::$app->session->get('shop_id')]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoryModel model.
     * @param integer $id
     * @return mixed
     */
//     public function actionView($id)
//     {
//         return $this->render('view', [
//             'model' => $this->findModel($id),
//         ]);
//     }

    /**
     * Creates a new CategoryModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {	

    	$model = new CategoryModel();
    	$modelAttr = new CategoryModelAttr();  
    	$modelAttr->loadDefaultValues();
    	$categoryModelAttr = [new CategoryModelAttr];
    	$modelAttrValue = new CategoryModelAttrValue();
    	$modelAttrValue->loadDefaultValues();
    	 
    	if ($model->load(Yii::$app->request->post())) {
    		$errors = array();
    		$categoryModelAttr = Model::createMultiple(CategoryModelAttr::classname());
    		Model::loadMultiple($categoryModelAttr, Yii::$app->request->post());
//     		if (Yii::$app->request->isAjax) {
//     			Yii::$app->response->format = Response::FORMAT_JSON;
//     			return ArrayHelper::merge(
//     					ActiveForm::validateMultiple($categoryModelAttr),
//     					ActiveForm::validate($model)
//     			);
//     		}
    		// validate all models
    		$errors = ArrayHelper::merge($errors,
    				ActiveForm::validateMultiple($categoryModelAttr)
    		);    		
    		//$valid = $model->validate();
    		//$valid = Model::validateMultiple($categoryModelAttr) && $valid;
    		if (isset($_POST['CategoryModelAttrValue'])) {
    			foreach ($_POST['CategoryModelAttrValue'] as $row => $attributeValues) {
    				//foreach ($attributeValues as $indexAttribute => $attributeValues) {
    				foreach ($attributeValues as $indexAttribute => $attributeValue) {
    					//$data['AttributeValue'] = $attributeValues;
    					$data['CategoryModelAttrValue'] = $attributeValue;
    					$modelAttributeValue = new CategoryModelAttrValue;
    					$modelAttributeValue->load($data);
    					$modelsAttributeValue[$row][$indexAttribute] = $modelAttributeValue;
    					//$valid = $modelAttributeValue->validate();    					
    				}
    			}
    			$errors = ArrayHelper::merge($errors, ActiveForm::validateArray($modelsAttributeValue));
    		}
    		$errors = ArrayHelper::merge($errors,
    				ActiveForm::validate($model)
    		);
    		if (Yii::$app->request->isAjax) {
    			Yii::$app->response->format = Response::FORMAT_JSON;
    			return $errors;
    		}
    		$valid = empty($errors)? true:false;
    		if ($valid) {
    			$transaction = \Yii::$app->db->beginTransaction();
    			try {
    				$attributeCach = array();//缓存已经存入过的attribute $attributeCach[$indexAttribute] = $modelAttribute;
    				$valueCach = array();//缓存已经存入过的value
    				if ($flag = $model->save(false)) {    					
    					if (isset($categoryModelAttr) && is_array($categoryModelAttr)) {
    						foreach ($categoryModelAttr as $index=>$attribute){
    							//处理属性
    							if (!isset($attributeCach[$index])){
    								    
    									$attributeDb = CategoryModelAttr::find()->where(['model_id'=>$model->model_id,'attr_name'=>$categoryModelAttr[$index]->attr_name])->one();
    									if (empty($attributeDb)){
    										$categoryModelAttr[$index]->model_id = $model->model_id;
    										if (!($flag = $categoryModelAttr[$index]->save(false))) {
    											break;
    										}
    										$attributeCach[$index] = $categoryModelAttr[$index];
    									}else{
    										$attributeCach[$index] = $attributeDb;
    									}
    								
    							}
    							if (isset($modelsAttributeValue[$index])&&is_array($modelsAttributeValue[$index])){
    								foreach ($modelsAttributeValue[$index] as $indexValue=>$modelAttributeValue){
    									//处理属性值
    									if (!isset($valueCach[$index][$modelAttributeValue->value_str])){
    										$modelAttributeValue->model_attribute_id = $attributeCach[$index]->model_attr_id;
    										$attributeValueDb = CategoryModelAttrValue::find()->where(['model_attribute_id'=>$attributeCach[$index]->model_attr_id,'value_str'=>$modelAttributeValue->value_str])->one();
    										if (empty($attributeValueDb)){
    											if (!($flag = $modelAttributeValue->save(false))) {
    												break;
    											}
    											$valueCach[$indexAttribute][$modelAttributeValue->value_str] = $modelAttributeValue;
    										}else{
    											$valueCach[$indexAttribute][$modelAttributeValue->value_str] = $attributeValueDb;
    										}
    									}
    								}
    							}
    							
    						}
    					
    					}
    				}
    				if ($flag) {
    					$transaction->commit();
    					return $this->redirect(['index']);
    				}
    			} catch (\Exception $e) {
    				$transaction->rollBack();
    			}
    		}else {    			
    			Yii::$app->session->setFlash('error', current(current($errors)));    			
    			return $this->render('create', [    		
	    			'model' => $model,
	    			'modelAttr' => $modelAttr,
    				'modelAttrValue' => $modelAttrValue
    			]);
    		}
    	}
    	
    	return $this->render('create', [
    			'model' => $model,
    			'modelAttr' => $modelAttr,
    			'modelAttrValue' => $modelAttrValue
    	]);
        
    }

    /**
     * Updates an existing CategoryModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	
    	$model = $this->findModel($id);
    	$modelAttr = new CategoryModelAttr();
    	$modelAttr->loadDefaultValues();    	 
    	$categoryModelAttr = $model->categoryModelAttr;
    	$categoryModelAttrValue = $model->categoryModelAttrValue;
    	$modelAttrValue = new CategoryModelAttrValue();
    	$modelAttrValue->loadDefaultValues();
    	
    	if ($model->load(Yii::$app->request->post())) {
    		$errors = array();
    		$oldIDs = ArrayHelper::map($categoryModelAttr, 'model_attr_id', 'model_attr_id');
    		$oldValueIDs = ArrayHelper::map($categoryModelAttrValue, 'model_attr_value_id', 'model_attr_value_id');
    		$categoryModelAttr = Model::CreateMultiple(CategoryModelAttr::classname(), $categoryModelAttr,'model_attr_id');
    		Model::loadMultiple($categoryModelAttr, Yii::$app->request->post());
    		$errors = ArrayHelper::merge($errors,
    				ActiveForm::validateMultiple($categoryModelAttr)
    		);
    		$deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($categoryModelAttr, 'model_attr_id', 'model_attr_id')));
    		if (isset($_POST['CategoryModelAttrValue'])) {
    			foreach ($_POST['CategoryModelAttrValue'] as $row => $attributeValues) {
    				foreach ($attributeValues as $indexAttribute => $attributeValue) {
    					$data['CategoryModelAttrValue'] = $attributeValue;
    					$modelAttributeValue = new CategoryModelAttrValue;
    					$modelAttributeValue->load($data);
    					if (isset($data['CategoryModelAttrValue']['model_attr_value_id'])){
    						$modelAttributeValue->model_attr_value_id = $data['CategoryModelAttrValue']['model_attr_value_id'];
    					}
    					$modelsAttributeValue[$row][$indexAttribute] = $modelAttributeValue;    					
    					//$valid = $modelAttributeValue->validate();    					
    				}    				
    			}
    			$errors = ArrayHelper::merge($errors, ActiveForm::validateArray($modelsAttributeValue));    			
    		}
    		
//     		if (Yii::$app->request->isAjax) {
//     			Yii::$app->response->format = Response::FORMAT_JSON;
//     			return ArrayHelper::merge(
//     					ActiveForm::validateMultiple($categoryModelAttr),
//     					ActiveForm::validate($model)
//     			);
//     		}
    		// validate all models
    	    		
//     		$valid = $model->validate();
//     		$valid = Model::validateMultiple($categoryModelAttr) && $valid;
    		$errors = ArrayHelper::merge($errors,
    				ActiveForm::validate($model)
    		);
    		if (Yii::$app->request->isAjax) {
    			Yii::$app->response->format = Response::FORMAT_JSON;
    			return $errors;
    		}
    		$valid = empty($errors)? true:false;
    		if ($valid) {
    			$transaction = \Yii::$app->db->beginTransaction();
    			try {
    				if ($flag = $model->save(false)) {
    					if (! empty($deletedIDs)) {
    					 	if(!$flag === CategoryModelAttr::deleteAll(['model_attr_id' => $deletedIDs])){
    					 	   $transaction->rollBack(); 
    					 	   return Yii::$app->session->setFlash('error','系统错误！');
    						}
    					}  
    					//修改属性  					
    					foreach ($categoryModelAttr as $modelAttr) {
    						$modelAttr->model_id = $model->model_id;
    						if (! ($flag = $modelAttr->save(false))) {
    							$transaction->rollBack();    							
    							return Yii::$app->session->setFlash('error',current($modelAttr->getErrors()));
    						}
    					}
    					$attributeCach = array();//缓存已经存入过的attribute $attributeCach[$indexAttribute] = $modelAttribute;
    					$valueCach = array();//缓存已经存入过的value
    					$valueCachIds = array();//缓存已经存入过的valueId
    					if (isset($categoryModelAttr) && is_array($categoryModelAttr)) {
    						foreach ($categoryModelAttr as $index=>$attribute){
    							//处理属性
    							if (!isset($attributeCach[$index])){
    					
    								$attributeDb = CategoryModelAttr::find()->where(['model_id'=>$model->model_id,'attr_name'=>$categoryModelAttr[$index]->attr_name])->one();
    							
    								$attributeCach[$index] = $attributeDb;
    								
    							}
    							if (isset($modelsAttributeValue[$index])&&is_array($modelsAttributeValue[$index])){
    								foreach ($modelsAttributeValue[$index] as $indexValue=>$modelAttributeValue){
    									//处理属性值
    									if (!isset($valueCach[$index][$modelAttributeValue->value_str])){
    										$modelAttributeValue->model_attribute_id = $attributeCach[$index]->model_attr_id;
    										if (isset($modelAttributeValue->model_attr_value_id)){
    											$attributeValueDb = CategoryModelAttrValue::find()->where(['model_attr_value_id'=>$modelAttributeValue->model_attr_value_id])->one();
    											$attributeValueDb->value_str=$modelAttributeValue->value_str;
    											$attributeValueDb->status = $modelAttributeValue->status;
    											$attributeValueDb->sort = $modelAttributeValue->sort;
    										}else{
    											$attributeValueDb = CategoryModelAttrValue::find()->where(['model_attribute_id'=>$attributeCach[$index]->model_attr_id,'value_str'=>$modelAttributeValue->value_str])->one();
    										}    										
    										if (empty($attributeValueDb)){
    											if (!($flag = $modelAttributeValue->save(false))) {
    											    $transaction->rollBack();
    											    return Yii::$app->session->setFlash('error',current($modelAttributeValue->getErrors()));
    											}
    											$valueCach[$indexAttribute][$modelAttributeValue->value_str] = $modelAttributeValue;
    											array_push($valueCachIds, $modelAttributeValue->model_attr_value_id);
    											
    										}else{
    											if (!($flag = $attributeValueDb->save(false))) {
    											    $transaction->rollBack();
    											    return Yii::$app->session->setFlash('error',current($attributeValueDb->getErrors()));
    											}
    											$valueCach[$indexAttribute][$modelAttributeValue->value_str] = $attributeValueDb;
												array_push($valueCachIds, $modelAttributeValue->model_attr_value_id);    										
    										}
    									}
    								}
    							}
    						}
    						$deletedValueIDs = array_diff($oldValueIDs, $valueCachIds);
    						if (! empty($deletedValueIDs)) {
    							foreach ($deletedValueIDs as $valueid){
    								$deleteValue = CategoryModelAttrValue::findOne(array('model_attr_value_id'=>$valueid));
    								if(!$flag = $deleteValue->delete()){
    									$error = $deleteValue->getErrors('model_id');
    									$transaction->rollBack();
    									return Yii::$app->session->setFlash('error','系统错误');
    								}    								    								
    							}    							   							
    						}
    					}
    				}
    				if ($flag) {
    					$transaction->commit();    					
    					return $this->redirect(['index']);
    				}else{    					
    					Yii::$app->session->setFlash('error',$error);
    				}
    			} catch (\Exception $e) {
    				$transaction->rollBack();    				
    			}
    		}else{
    			Yii::$app->session->setFlash('error', current(current($errors)));
    		}
    		return $this->render('update', [
    			'model' => $model,
    			'modelAttr' => $modelAttr,
    			'modelAttrValue' => $modelAttrValue
    		]);
    	}else {
            return $this->render('update', [
                'model' => $model,
            	'modelAttr' => $modelAttr,
            	'modelAttrValue' => $modelAttrValue
            ]);
        }
    	
    }

    /**
     * Deletes an existing CategoryModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $flag = $model->delete();
		if (!$flag){
			Yii::$app->session->setFlash('error', $model->getErrors('model_id'));
		}else{
			Yii::$app->session->setFlash('success', '操作成功');
		}
        return $this->redirect(['index']);
		
        return $this->redirect(['index']);
    }

    /**
     * Finds the CategoryModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoryModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoryModel::find()->where(['model_id'=>$id,'shop_id'=>Yii::$app->session->get('shop_id')])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    

}
