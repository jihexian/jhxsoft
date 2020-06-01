<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProductComment;

/**
 * ProductCommentSearch represents the model behind the search form about `common\models\ProductComment`.
 */
class ProductCommentSearch extends ProductComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'member_id', 'pid','appraise', 'reply_member_id', 'status','reply_status', 'created_at', 'updated_at', 'order_sku_id', 'order_no', 'total_stars', 'des_stars', 'delivery_stars', 'service_stars','goods_id','shop_id','is_nickname'], 'integer'],
            [['content', 'image'], 'safe'],
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
    
    
    
    public function extraFields()
    {
    	return [    			
    			'member',
    			'replys'
    	];
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
    	if (isset($params['shop_id'])){
    	    //$query = ProductComment::find()->from(ProductComment::tableName() . ' u1')->where(['u1.shop_id'=>$params['shop_id']])->joinWith(['member','replys','product']);
    	    $query = ProductComment::find()->from(ProductComment::tableName() . ' u1');
    	}else{
    		$query = ProductComment::find()->joinWith(['member','replys'])->joinWith('product')->from(ProductComment::tableName() . ' u1');
    	}
    	
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
        	$num=$params['num'];
        else
        	$num=10;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'comment_id' => SORT_DESC
                ]
            ],
        		'pagination' => [
        				'defaultPageSize' =>$num,
        		],
        ]);
        if (isset($params['ProductComment'])){
        	$this->load($params);
        }else{
        	$this->load($params,'');
        }
        

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'u1.comment_id' => $this->comment_id,
            'u1.member_id' => $this->member_id,
        	'u1.goods_id'=>$this->goods_id,
            'u1.pid' => $this->pid,
            'u1.reply_member_id' => $this->reply_member_id,
            'u1.status' => $this->status,
            'u1.created_at' => $this->created_at,
            'u1.updated_at' => $this->updated_at,
            'u1.order_sku_id' => $this->order_sku_id,
            'u1.order_no' => $this->order_no,
            'u1.total_stars' => $this->total_stars,
            'u1.des_stars' => $this->des_stars,
            'u1.delivery_stars' => $this->delivery_stars,
            'u1.service_stars' => $this->service_stars,
        	'u1.appraise' => $this->appraise,
        	'u1.reply_status'=>$this->reply_status,
			'u1.is_nickname'=>$this->is_nickname
        		
        ]);

        $query->andFilterWhere(['like', 'u1.content', $this->content])
            ->andFilterWhere(['like', 'u1.image', $this->image]);

        return $dataProvider;
    }
}
