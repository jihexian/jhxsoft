<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    public $shop_status;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        
        
        return [
            [['product_id', 'model_id', 'cat_id', 'type_id', 'brand_id', 'up_time', 'down_time', 'create_at', 'update_at', 'visit', 'favorite', 'sortnum', 'comments', 'sale', 'shop_id', 'stock','hot','prom_id','prom_type','is_index_show','is_top','is_new','sort'], 'integer'],
            [['name', 'unit', 'status','shop_status'], 'safe'],
            [['max_price', 'min_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find()->joinWith('shop')->notTrashed();
		if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
			$num=$params['num'];
		else
			$num=10;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                   /*  'is_top'=>SORT_DESC, */
                    'sort'=>SORT_ASC,
                    'up_time' => SORT_DESC,
                    'product_id'=>SORT_DESC,
                ]
            ],
			'pagination' => [
				'pageSize' =>$num,
			    'validatePage'=>false,
			],
        ]);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;        
        $dataProvider->setSort($sort);
        
        if (isset($params['ProductSearch'])){
        	$this->load($params);
        }else{
        	$this->load($params,'');
        }
        
    
/* 
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        } */

        $query->andFilterWhere([
            'product_id' => $this->product_id,
            'model_id' => $this->model_id,
            //'cat_id' => $this->cat_id,
            'is_index_show'=>$this->is_index_show,
            'brand_id' => $this->brand_id,
            'up_time' => $this->up_time,
            'down_time' => $this->down_time,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'visit' => $this->visit,
            'favorite' => $this->favorite,
            'sortnum' => $this->sortnum,
            'comments' => $this->comments,
            'sale' => $this->sale,
			'hot' => $this->hot,  //热销
			'is_new'=>$this->is_new,
            'is_top'=>$this->is_top,
            'shop_id' =>$this->shop_id,
            'max_price' => $this->max_price,
            'min_price' => $this->min_price,
        	'prom_id' => $this->prom_id,
        	'prom_type'=>$this->prom_type,
        	Product::tableName().'.status' => $this->status,
            Shop::tableName().'.status' => $this->shop_status,
        ]);

        $query->andFilterWhere(['like', Product::tableName().'.name', $this->name])
            ->andFilterWhere(['like', 'unit', $this->unit]);
            if(isset($params['child'])&&$params['child']&&$this->cat_id){ //如果请求参数包含条数，则限制返回数量。单次查询最大50条
			$cid=$this->getchild($this->cat_id);
			$query->andFilterWhere([
				'cat_id'=>$cid
        	]);
		}else{
			$query->andFilterWhere([
				'cat_id' => $this->cat_id,
        	]);
		} 
		
	    //获取自己及子分类的商品
	 	if($this->type_id){
    		$types=$this->getAll($this->type_id);
    		$query->andFilterWhere([
    		    'type_id'=>$types,
    		]);

		} 
		$sql=$query->createCommand()->getRawSql();
	    
        return $dataProvider;
    }
    /*获取子分类*/
    private function getchild($cid){
    	$cate=new ProductCategory();
    	$c=$cate->getChilds($cid);
    	$childArr=array();
    	foreach($c as $key=>$v)
    		$childArr[$key]=$v['category_id'];
    	if(!empty($childArr))
    		array_push($childArr,$cid);
    	else
    		$childArr[0]=$cid;
    	return $childArr;
    }
    private  function getAll($id){
       $data=ProductType::find()->where(['parent_id'=>$id,'status'=>1])->asArray()->all();
       static  $dd = array(); 
       $dd[]=$id;

       if(!empty($data)){
       foreach ($data as $key=>$vo){
           $dd[]=$vo['type_id'];
           $this->getAll($vo['type_id']);
        }
       }

       return array_unique($dd); 
    }
    
    public function rankProduct(){
        $query = Product::find();
        $query->andFilterWhere([
            'status'=>1,//发布
            'is_del'=>0,//未删除
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sale'=>SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' =>10,
                'validatePage'=>false,
            ],
        ]);
        return $dataProvider;
    }
}
