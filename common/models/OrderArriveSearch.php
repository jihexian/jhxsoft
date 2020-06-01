<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderArrive;

/**
 * OrderArriveSearch represents the model behind the search form about `common\models\OrderArrive`.
 */
class OrderArriveSearch extends OrderArrive
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'm_id', 'payment_status', 'shop_id', 'is_shop_checkout', 'created_at', 'updated_at', 'user_id',  'pay_time'], 'integer'],
            [['order_no', 'remark', 'payment_no', 'payment_name','payment_code'], 'safe'],
            [['pay_amount', 'order_price'], 'number'],
            [['pay_amount','order_price'], 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
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
        $query = OrderArrive::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        
        if (isset($params['OrderArriveSearch'])){
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
            'id' => $this->id,
            'pay_amount' => $this->pay_amount,
            'm_id' => $this->m_id,
            'payment_status' => $this->payment_status,
            'shop_id' => $this->shop_id,
            'is_shop_checkout' => $this->is_shop_checkout,
            'order_price' => $this->order_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'payment_code' => $this->payment_code,
            'payment_name' => $this->payment_name,
            'pay_time' => $this->pay_time,
        ]);

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'payment_no', $this->payment_no])
            ->andFilterWhere(['like', 'payment_name', $this->payment_name]);

        return $dataProvider;
    }
}
