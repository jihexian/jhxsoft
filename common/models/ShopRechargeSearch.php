<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ShopRecharge;

/**
 * ShopRechargeSearch represents the model behind the search form about `common\models\ShopRecharge`.
 */
class ShopRechargeSearch extends ShopRecharge
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'score', 'created_at', 'updated_at', 'pay_status'], 'integer'],
            [['order_no', 'payment_code', 'payment_name', 'transaction_id'], 'safe'],
            [['pay_amount'], 'number'],
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
        $query = ShopRecharge::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'pay_amount' => $this->pay_amount,
            'score' => $this->score,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pay_status' => $this->pay_status,
        ]);

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'payment_code', $this->payment_code])
            ->andFilterWhere(['like', 'payment_name', $this->payment_name])
            ->andFilterWhere(['like', 'transaction_id', $this->transaction_id]);

        return $dataProvider;
    }
}
