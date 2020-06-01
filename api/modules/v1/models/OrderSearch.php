<?php

namespace api\modules\v1\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    
    
  
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'm_id', 'payment_status', 'delivery_status', 'shop_id',  'status', 'is_del'], 'integer'],   
            [['parent_sn','order_no'],'safe']
            
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
        $query = Order::find();
       
       
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
            $num=$params['num'];
            else
                $num=10;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
                'pagination' => [
                        'defaultPageSize' =>$num,
                    'validatePage'=>false,
                ],
        ]);

        $this->load($params,'');
 
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if(isset($params['status'])){
            $query->andFilterWhere(['status'=>$this->status]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'm_id' => $this->m_id,
            'payment_status' => $this->payment_status,
            'delivery_status' => $this->delivery_status,
            'shop_id' => $this->shop_id,
            'is_del' => $this->is_del,    
            'order_no' => $this->order_no,  
            'parent_sn' => $this->parent_sn,  
        ]);
        return $dataProvider;
    }
}
