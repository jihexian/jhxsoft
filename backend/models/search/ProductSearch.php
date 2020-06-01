<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Product;
use common\models\ProductCategory;
use common\models\ProductType;

/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'model_id', 'cat_id', 'type_id', 'brand_id', 'up_time', 'down_time', 'create_at', 'update_at', 'visit', 'favorite', 'sortnum', 'comments', 'sale', 'shop_id', 'stock','hot','prom_id','prom_type'], 'integer'],
            [['name', 'unit', 'status'], 'safe'],
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
        $query = Product::find()->notTrashed();
		if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
			$num=$params['num'];
		else
			$num=10;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'up_time' => SORT_DESC,
                ]
            ],
			'pagination' => [
				'pageSize' =>$num,
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
        
        if (!isset($this->status)){
        	$this->status=1;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'product_id' => $this->product_id,
            'model_id' => $this->model_id,
            //'cat_id' => $this->cat_id,
           
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
            'shop_id' => $this->shop_id,
            'max_price' => $this->max_price,
            'min_price' => $this->min_price,
           
        	'prom_id' => $this->prom_id,
        	'prom_type'=>$this->prom_type,
        	'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit', $this->unit]);
		if(isset($params['child'])&&$params['child']){ //如果请求参数包含条数，则限制返回数量。单次查询最大50条
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
		
		
	    
        return $dataProvider;
    }
    /*获取子分类*/
    private function getchild($cid){
    	$cate=new ProductCategory();
    	$c=$cate->getChilds($cid);
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
       $dd=array();
       foreach ($data as $key=>$vo){
           $dd[$key]=$vo['type_id'];
       }
       array_push($dd, $id);
       return $dd;
    
        
    }
}
