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

    /**
     * Displays a single Product model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        //return $this->actionUpdate($id);
    }

    
    
    /**
     * qrcode
     * @param  $url
     * @return
     */
    public function actionQrcode($id)
    {
        $url='http://'.$_SERVER['SERVER_NAME'].'/product/detail?id='.$id;
        $qrCode = new QrCode($url);
        
        header('Content-Type: '.$qrCode->getContentType());
        return  $qrCode->writeString();
      
    }
    
    
    public function actionPng($id){
       
         return $this->render('png',['id'=>$id]);
    }
    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionStep1(){
        $model = new Product;
        return $this->render('step1', [
            'model' => $model, 
        ]);
    }

    public function actionCreate()
    {
    	$model = new Product;
    	$model->loadDefaultValues();
    	$attribute = new Attribute;
    	$attributeValue = new AttributeValue;
    	$modelsAttribute = [new Attribute];
    	$modelsSkus = [new Skus];
    	$modelsAttributeValue = [[new AttributeValue]];
    	$modelSkus = new Skus;
    	$modelProductModelAttr = new ProductModelAttr;
    	$modelsProductModelAttr = [[new ProductModelAttr]];
    	$modelCategoryModelAttr = [];
    	/*  获取平台类目顶级分类type_id */
    	$get=yii::$app->request->get();
    	if(isset($get['Product']['type_id'])&&empty($get['Product']['type_id'])){
    	    throw new NotFoundHttpException('没选择类目');
    	}
    	$type_id=$get['Product']['type_id'];
    	$model->type_id=$type_id;
    	$attributes=Attribute::find()->where(['type_id'=>$type_id])->groupBy(['attribute_name','attribute_id'])->all();
    	$modelCategoryModelAttr = CategoryModelAttr::find()->where(['model_id'=>1])->with('categoryModelAttrValue')->all();
    	if ($model->load(Yii::$app->request->post())) {
    		//处理attribute输入值
    		$modelsAttribute = Model::createMultiple(Attribute::classname());
    		Model::loadMultiple($modelsAttribute, Yii::$app->request->post());  
			foreach ($modelsAttribute as &$attr){
				//加入type_id
			    if(mb_strpos($attr->attribute_name,'时间')!==false||mb_strpos($attr->attribute_name,'日期')!==false){
			        $attr->usage_mode=3;
			    }
			    $attr->type_id =$type_id;  //规格只跟product_type的顶级分类有关系
			}    		
    		$modelsSkus = Model::createMultiple(Skus::classname());
    		Model::loadMultiple($modelsSkus, Yii::$app->request->post());    		
    		$errors = array();
    		if (count($modelsSkus)==0){
    			$modelSkus->addError('info', '请添加商品规格信息'); 
    			$error = $modelSkus->getErrors('info');
    			$errors = ArrayHelper::merge($errors,  ['skus-info'=>$error]);
    		}    		
    		
    		if (isset($_POST['AttributeValue'])) {//row为sku表的行，indexAttribute为表头，即对应的attribute
    			foreach ($_POST['AttributeValue'] as $row => $attributeValues) {    				
    				foreach ($attributeValues as $indexAttribute => $attributeValueData) {
    					$data['AttributeValue'] = $attributeValueData;
    					$modelAttributeValue = new AttributeValue;
    					$modelAttributeValue->load($data);
    					$modelsAttributeValue[$row][$indexAttribute] = $modelAttributeValue;    					
    				}
    			}
    			$errors = ArrayHelper::merge($errors, ActiveForm::validateArray($modelsAttributeValue));
    		}
    
    		if (isset($_POST['ProductModelAttr'])) {
    			foreach ($_POST['ProductModelAttr'] as $row => $productModelAttrs) {
    				foreach ($productModelAttrs as $indexAttribute => $productModelAttr) {    						
    					$dataAttr['ProductModelAttr'] = $productModelAttr;
    					$productModelAttr = new ProductModelAttr;
    					$productModelAttr->load($dataAttr);
    					$modelsProductModelAttr[$row][$indexAttribute] = $productModelAttr;    								
    				}
    			}
    			$errors = ArrayHelper::merge($errors, ActiveForm::validateArray($modelsProductModelAttr));
    		} 
    		$errors = ArrayHelper::merge($errors,
    				ActiveForm::validateMultiple($modelsAttribute),
    				ActiveForm::validateMultiple($modelsSkus),
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
    				//处理规格
    				foreach ($modelsAttribute as $indexAttribute=>$modelAtt){    					
    					$attributeDb = Attribute::find()->where(['type_id'=>$model->type_id,'attribute_name'=>$modelsAttribute[$indexAttribute]->attribute_name])->one();
    					if (empty($attributeDb)){
    						if (!($flag = $modelsAttribute[$indexAttribute]->save(false))) {
    							break;
    						}
    					}else{
    						$modelsAttribute[$indexAttribute] = $attributeDb;
    					}
    					foreach ($modelsAttributeValue as $indexValue => $attrValues) {
    							//处理规格值
    						$modelsAttributeValue[$indexValue][$indexAttribute]->attribute_id = $modelsAttribute[$indexAttribute]->attribute_id;
    						$attributeValueDb = AttributeValue::find()->where(['attribute_id'=>$modelsAttribute[$indexAttribute]->attribute_id,'value_str'=>$modelsAttributeValue[$indexValue][$indexAttribute]->value_str])->one();
    						if (empty($attributeValueDb)){
    							if (!($flag = $modelsAttributeValue[$indexValue][$indexAttribute]->save(false))) {
    								break;
    						}
    						}else{
    							$modelsAttributeValue[$indexValue][$indexAttribute] = $attributeValueDb;
    						}    							    							    						
    					}    					
    				}
    				
    				foreach ($modelsSkus as $indexSku=>$sku){
    					$sku_values = array();
    					$skuId = $model->product_id;    					
    					foreach ($modelsAttributeValue[$indexSku] as $indexAttribute=>$attrValue){
    						$skuId = $skuId.'_'.$modelsAttributeValue[$indexSku][$indexAttribute]->value_id;
    						if (!isset($sku_values[$modelsAttribute[$indexAttribute]->attribute_name])){
    							$sku_values[$modelsAttribute[$indexAttribute]->attribute_name] = array();
    						}    						
    						if (empty($sku_values[$modelsAttribute[$indexAttribute]->attribute_name])){
    							array_push($sku_values[$modelsAttribute[$indexAttribute]->attribute_name],$modelsAttributeValue[$indexSku][$indexAttribute]->value_str);
    						}    						
    					}
    					//添加skus
    					$modelsSkus[$indexSku]->sku_id=$skuId;
    					$modelsSkus[$indexSku]->product_id=$model->product_id;
    					$modelsSkus[$indexSku]->sku_values=json_encode($sku_values,JSON_UNESCAPED_UNICODE);
    					if (!($flag = $modelsSkus[$indexSku]->save(false))) {
    						break;
    					}    					
    				}	
    				
    				//初始化product库存和价格信息
    				$maxSalePrice = 0;
    				$minSalePrice = 0;
    				$minPlusPrice=0;
    				$stock = 0;
    				//添加skuitem
    				foreach ($modelsSkus as $indexSku=>$sku){
    					if ($maxSalePrice==0&$sku->sale_price>0){
    						$maxSalePrice = $sku->sale_price;
    						$minSalePrice = $sku->sale_price;
    					}else{
    						if ($sku->sale_price>0&$maxSalePrice<$sku->sale_price){
    							$maxSalePrice = $sku->sale_price;
    						}
    						if ($sku->sale_price>0&$minSalePrice>$sku->sale_price){
    							$minSalePrice = $sku->sale_price;
    						}
    					}
    					if(!empty($sku->plus_price)&&$sku->plus_price>0){
    					    if($minPlusPrice==0){
    					        $minPlusPrice = $sku->plus_price;
    					    }
    					    if ($minPlusPrice>$sku->plus_price){
    					        $minPlusPrice = $sku->plus_price;
    					    }
    					}
    					if ($sku->stock>0){
    						$stock = $stock+$sku->stock;
    					}
    						
    					$skuId = $sku->sku_id;
    					$valueIds = explode("_", $skuId);
    					foreach ($modelsAttribute as $indexAttr=>$attribute){
    						$modelSkuItem = new SkuItem();
    						$modelSkuItem->sku_id = $skuId;
    						$modelSkuItem->attribute_id = $attribute->attribute_id;
    						$modelSkuItem->value_id = $valueIds[$indexAttr+1];
    						if (!($flag = $modelSkuItem->save(false))) {
    							break;
    						}
    					}
    					if (!$flag){
    						break;
    					}
    				}
    				//添加productModelAttr
    				foreach ($modelsProductModelAttr as $productModelAttrs){
    					foreach ($productModelAttrs as $productModelAttr){
    						if (!isset($productModelAttr->model_attr_value_id)||(isset($productModelAttr->model_attr_value_id)&&empty($productModelAttr->model_attr_value_id))){//去空
    							continue;
    						}
    						$productModelAttr->product_id=$model->product_id;
    						$productModelAttr->model_id = $model->model_id;
    						if (!($flag = $productModelAttr->save(false))) {
    							break;
    						}
    					}
    					if (!$flag){
    						break;
    					}
    				}
    				//更新product库存和价格信息
    				$model->stock =$stock;
    				$model->max_price = $maxSalePrice;
    				$model->min_price = $minSalePrice;
    				$model->plus_price=$minPlusPrice;
    				$flag = $model->save(false);
    					
    			}   
    			 
    			if ($flag) {
    				$transaction->commit();
    				Yii::$app->session->setFlash('success', '添加成功');
    				return $this->redirect(['product/update','id'=>$model->product_id]);
    			} else {
    				$transaction->rollBack();
    			}
    		} catch (Exception $e) {
    		    yii::error($e->getMessage());
    			$transaction->rollBack();
    		}
    	}
    	}//valid 
   
    	return $this->render('create', [
    			'model' => $model,
    			'attribute' => $attribute,
    			'attributeValue' => $attributeValue,
    			'modelSkus' => $modelSkus,
    	        'attributes'=>$attributes, //类目下的属性
    			'modelProductModelAttr' => $modelProductModelAttr,
    			'modelCategoryModelAttr' => $modelCategoryModelAttr,
    	]);
    }
    
    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Url::remember();
    	$model = $this->findModel($id);//product   
    	$promotionLogic = new PromotionLogic();
//     	$promFlag = $promotionLogic->checkProm($id);
//     	if ($promFlag){
//     		Yii::$app->session->setFlash('error', '该商品正在参加营销活动，无法修改');
//             return $this->redirect(['index']);
//     		//return $this->actionIndex();
//     	} 	
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
    	$attribute = new Attribute;//新增规则对象
    	$attributeValue = new AttributeValue();//新增规则值对象
    	$modelSkus = new Skus(); //skus对象
    	$modelProductModelAttr = new ProductModelAttr();
    	if ($model->load(Yii::$app->request->post())) {
    		$modelsAttribute = Model::createMultiple(Attribute::classname());
    		Model::loadMultiple($modelsAttribute, Yii::$app->request->post());
    		foreach ($modelsAttribute as &$attr){
    			//加入type_id
    		    if(mb_strpos($attr->attribute_name,'时间')!==false||mb_strpos($attr->attribute_name,'日期')!==false){
    		        $attr->usage_mode=3;
    		    }
    			$attr->type_id = $model->type_id;
    		}
    		$modelsSkus = Model::createMultiple(Skus::classname());
    		Model::loadMultiple($modelsSkus, Yii::$app->request->post());
    		$errors =array();
    		if (count($modelsSkus)==0){
    			$modelSkus->addError('info', '请添加商品规格信息'); 
    			$error = $modelSkus->getErrors('info');
    			$errors = ArrayHelper::merge($errors,  ['skus-info'=>$error]);
    		} 
    		if (isset($_POST['AttributeValue'][0][0])) {
    			foreach ($_POST['AttributeValue'] as $row => $attributeValues) {
    				foreach ($attributeValues as $indexAttribute => $attributeValues) {
    					$data['AttributeValue'] = $attributeValues;
    					$modelAttributeValue = new AttributeValue;
    					$modelAttributeValue->load($data);
    					$modelsAttributeValue[$row][$indexAttribute] = $modelAttributeValue;
    					
    				}    			
    			}
    			$errors = ArrayHelper::merge($errors, ActiveForm::validateArray($modelsAttributeValue));
    		}
    		if (isset($_POST['ProductModelAttr'])) {
    			foreach ($_POST['ProductModelAttr'] as $row => $productModelAttrs) {
    				foreach ($productModelAttrs as $indexAttribute => $productModelAttr) {    
    					$dataAttr['ProductModelAttr'] = $productModelAttr;
    					$modelProductModelAttr = new ProductModelAttr;
    					$modelProductModelAttr->load($dataAttr);
    					$modelsProductModelAttr[$row][$indexAttribute] = $modelProductModelAttr;    					
    				}
    			}
    			$errors = ArrayHelper::merge($errors, ActiveForm::validateArray($modelsProductModelAttr));
    		}
    		$errors = ArrayHelper::merge($errors,
    				ActiveForm::validateMultiple($modelsAttribute),
    				ActiveForm::validateMultiple($modelsSkus),
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
    					//处理规格
    					foreach ($modelsAttribute as $indexAttribute=>$modelAtt){
    						$attributeDb = Attribute::find()->where(['type_id'=>$model->type_id,'attribute_name'=>$modelsAttribute[$indexAttribute]->attribute_name])->one();
    						if (empty($attributeDb)){
    							if (!($flag = $modelsAttribute[$indexAttribute]->save(false))) {
    								break;
    							}
    						}else{
    							$modelsAttribute[$indexAttribute] = $attributeDb;
    						}
    						foreach ($modelsAttributeValue as $indexValue => $attrValues) {
    							//处理规格值
    							$modelsAttributeValue[$indexValue][$indexAttribute]->attribute_id = $modelsAttribute[$indexAttribute]->attribute_id;
    							$attributeValueDb = AttributeValue::find()->where(['attribute_id'=>$modelsAttribute[$indexAttribute]->attribute_id,'value_str'=>$modelsAttributeValue[$indexValue][$indexAttribute]->value_str])->one();
    							if (empty($attributeValueDb)){
    								if (!($flag = $modelsAttributeValue[$indexValue][$indexAttribute]->save(false))) {
    									break;
    								}
    							}else{
    								$modelsAttributeValue[$indexValue][$indexAttribute] = $attributeValueDb;
    							}
    						}
    					
    					}
    					foreach ($modelsSkus as $indexSku=>$sku){
    						$sku_values = array();
    						$skuId = $model->product_id;
    						foreach ($modelsAttributeValue[$indexSku] as $indexAttribute=>$attrValue){
    							$skuId = $skuId.'_'.$modelsAttributeValue[$indexSku][$indexAttribute]->value_id;
    							if (!isset($sku_values[$modelsAttribute[$indexAttribute]->attribute_name])){
    								$sku_values[$modelsAttribute[$indexAttribute]->attribute_name] = array();
    							}
    							if (empty($sku_values[$modelsAttribute[$indexAttribute]->attribute_name])){
    								array_push($sku_values[$modelsAttribute[$indexAttribute]->attribute_name],$modelsAttributeValue[$indexSku][$indexAttribute]->value_str);
    							}
    						}
    						//skus
    						$modelsSkus[$indexSku]->sku_id=$skuId;
    						$modelsSkus[$indexSku]->product_id=$model->product_id;
    						$modelsSkus[$indexSku]->sku_values=json_encode($sku_values,JSON_UNESCAPED_UNICODE);
    						//查看skus是否已经存在
    						$modelsSkusDb = Skus::find()->where(['sku_id'=>$modelsSkus[$indexSku]->sku_id,'product_id'=>$model->product_id])->one();
    						if (empty($modelsSkusDb)){//添加
    							if (!($flag = $modelsSkus[$indexSku]->save(false))) {
    								break;
    							}
    						}else{
    							$modelsSkusDb->product_id=$model->product_id;
    							$modelsSkusDb->weight=$modelsSkus[$indexSku]->weight;
    							$modelsSkusDb->stock=$modelsSkus[$indexSku]->stock;
    							$modelsSkusDb->market_price=$modelsSkus[$indexSku]->market_price;
    							$modelsSkusDb->plus_price=$modelsSkus[$indexSku]->plus_price;
    							$modelsSkusDb->sale_price=$modelsSkus[$indexSku]->sale_price;
    							$modelsSkusDb->sku_num=$modelsSkus[$indexSku]->sku_num;
    							$modelsSkusDb->image=$modelsSkus[$indexSku]->image;
    							if (strlen($modelsSkus[$indexSku]->thumbImg)>0){
    								//处理attachment插件初始不加载thumbImg字段，生成时候会加载
    								$modelsSkusDb->thumbImg=$modelsSkus[$indexSku]->thumbImg;
    							}
    							$modelsSkusDb->sku_values = json_encode($sku_values,JSON_UNESCAPED_UNICODE);
    							if (!($flag = $modelsSkusDb->save(false))) {
    								break;
    							}
    							$modelsSkus[$indexSku] = $modelsSkusDb;
    							foreach ($oldSkus as $oldSku){
    								if($modelsSkus[$indexSku]->sku_id==$oldSku){
    									unset($oldSkus[$modelsSkus[$indexSku]->sku_id]);
    								}
    							}
    						}
    						
    					}
    					
    					//初始化product库存和价格信息
    					$maxSalePrice = 0;
    					$minSalePrice = 0;
    					$minPlusPrice=0;
    					$stock = 0;
    					//添加skuitem
    					foreach ($modelsSkus as $indexSku=>$sku){
    						if ($maxSalePrice==0&$sku->sale_price>0){
    							$maxSalePrice = $sku->sale_price;
    							$minSalePrice = $sku->sale_price;
    						}else{
    							if ($sku->sale_price>0&$maxSalePrice<$sku->sale_price){
    								$maxSalePrice = $sku->sale_price;
    							}
    							if ($sku->sale_price>0&$minSalePrice>$sku->sale_price){
    								$minSalePrice = $sku->sale_price;
    							}
    						}
    						if(!empty($sku->plus_price)&&$sku->plus_price>0){
    						    if($minPlusPrice==0){
    						        $minPlusPrice = $sku->plus_price;
    						    }
    						    if ($minPlusPrice>$sku->plus_price){
    						        $minPlusPrice = $sku->plus_price;
    						    }
    						}
    					
    						if ($sku->stock>0){
    							$stock = $stock+$sku->stock;
    						}
    					
    						$skuId = $sku->sku_id;
    						$valueIds = explode("_", $skuId);
    						foreach ($modelsAttribute as $indexAttr=>$attribute){
    							$modelSkuItem = new SkuItem();
    							$modelSkuItem->sku_id = $skuId;
    							$modelSkuItem->attribute_id = $attribute->attribute_id;
    							$modelSkuItem->value_id = $valueIds[$indexAttr+1];
    							$skuItemDb = SkuItem::find()->where(['sku_id'=>$skuId,'attribute_id'=>$attribute->attribute_id,'value_id'=>$valueIds[$indexAttr+1]])->one();
    							if (empty($skuItemDb)){
    								if (!($flag = $modelSkuItem->save(false))) {
    									break;
    								}
    							}
    							
    						}
    						if (!$flag){
    							break;
    						}
    					}
    					//删除以前的sku和sku_item
    					foreach ($oldSkus as $oldSku){
    					    $checkSkus = $promotionLogic->checkSkusProming($oldSku,$model->prom_type);
    					    if ($checkSkus['status']==1) {
    					        $transaction->rollBack();
    					        Yii::$app->session->setFlash('error',$checkSkus['msg'].'请先删除活动');
    					        return $this->goBack();
    					    }
    						if(!$flag = Skus::deleteAll(['sku_id'=>$oldSku])){
    							break;
    						}
    						if(!$flag = SkuItem::deleteAll(['sku_id'=>$oldSku])){
    							break;
    						}
    					}
    					//添加productModelAttr
    					if (isset($modelsProductModelAttr)){
    						foreach ($modelsProductModelAttr as $productModelAttrs){
    							foreach ($productModelAttrs as $productModelAttr){
    								if (!isset($productModelAttr->model_attr_value_id)||(isset($productModelAttr->model_attr_value_id)&&empty($productModelAttr->model_attr_value_id))){//去空
    									continue;
    								}
    								$productModelAttrDb = ProductModelAttr::find()->where(['product_id'=>$model->product_id,'model_attr_value_id'=>$productModelAttr->model_attr_value_id])->one();
    								if (empty($productModelAttrDb)){
    									$productModelAttr->product_id=$model->product_id;
    									$productModelAttr->model_id = $model->model_id;
    									if (!($flag = $productModelAttr->save(false))) {
    										break;
    									}
    									$productModelAttrDb = $productModelAttr;
    								}
    									
    								foreach ($oldProductModelAttrs as $oldProductModelAttr){
    									if($productModelAttrDb->product_model_attr_id==$oldProductModelAttr){
    										unset($oldProductModelAttrs[$oldProductModelAttr]);
    									}
    								}    						
    							}
    							if (!$flag){
    								break;
    							}
    						}
    					}    					
    					////删除以前的productModelAttr
    					foreach ($oldProductModelAttrs as $oldProductModelAttr){
    						$productModelAttrDb  = ProductModelAttr::find()->where(['product_model_attr_id'=>$oldProductModelAttr])->one();
    						if (!empty($productModelAttrDb)){
    							if(!$flag = $productModelAttrDb->delete()){
    								break;
    							}
    						}
    					}
    					
    					//更新product库存和价格信息
    					$model->stock =$stock;
    					$model->max_price = $maxSalePrice;
    					$model->min_price = $minSalePrice;
    					$model->plus_price=$minPlusPrice;
    					$flag = $model->save(false);
    					
    				}
    					
    				if ($flag) {
    					$transaction->commit();
    				//	return $this->redirect(['index']);
    					Yii::$app->session->setFlash('success', '编辑成功');
    					return $this->redirect(['product/update','id'=>$model->product_id]);
    				} else {
    					$transaction->rollBack();
    					Yii::$app->session->setFlash('error', current($model->getErrors()));
    					return $this->goBack();
    				}
    			} catch (Exception $e) {
    				$transaction->rollBack();
    				Yii::$app->session->setFlash('error', current($model->getErrors()));
    				return $this->goBack();
    			}
    			
    		}
    	}
    	return $this->render('update', [
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
    



    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->softDelete();
        Yii::$app->session->setFlash('success', '操作成功');
        return $this->goBack();
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Product::find()->where(['product_id'=>$id,'shop_id'=>Yii::$app->session->get('shop_id')])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionStock($id){
        $model=$this->findModel($id);
        $skus=Skus::find()->where(['product_id'=>$id])->asArray()->all();
        if(!empty($skus)){
            $arr=json_decode($skus[0]['sku_values']);
        }
        $stores=Store::find()->where(['status'=>1])->all();
        return $this->render('stock',[
            'model'=>$model,
            'skus'=>$skus,
            'arr'=>$arr,
            'stores'=>$stores,
        ]);
    }
    /**
     * desc 区域库存管理
     * @$data=array('store_id'=>'仓库id','sku_id'=>'商品sku_id')
     * @return number[]|string[]
     */
    public function actionUpdateStoreStock(){
        if(yii::$app->request->isPost){
            $datas=yii::$app->request->post('data');
            $flag=0;
            foreach($datas as $data){
                
                $store=StoreStock::findOne(['store_id'=>$data['store_id'],'sku_id'=>$data['sku_id']]);
                if(!empty($store)){
                    $store->stock=$data['stock'];
                    $store->save();
                    if($store->hasErrors()){
                        $flag++;
                    }
                }else{
                    $storeStock=new StoreStock();
                    $storeStock->load($data,'');
                    $storeStock->save();
                    if($storeStock->hasErrors()){
                        $flag++;
                    }
                }
                
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($flag>0){
                return ['status'=>0,'msg'=>'操作失败'];
            }else{
                return ['status'=>1,'msg'=>'操作成功'];
            }
            
        }
        
        
    }
    
    public function actionMap(){
        return $this->render('map');
    }
    
}
