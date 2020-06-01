<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Service;

/**
 * ServiceSerach represents the model behind the search form about `common\models\Service`.
 */
class ServiceSearch extends Service
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'sku_id', 'type', 'created_at', 'apply_status', 'updated_at', 'user_id', 'receive_status', 'status', 'refund_type', 'mid', 'shop_id', 'mobile'], 'integer'],
            [['company', 'delivery_no', 'mark', 'name'], 'safe'],
            [['amount'], 'number'],
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
        $query = Service::find();

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
            'order_id' => $this->order_id,
            'sku_id' => $this->sku_id,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'apply_status' => $this->apply_status,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'receive_status' => $this->receive_status,
            'status' => $this->status,
            'amount' => $this->amount,
            'refund_type' => $this->refund_type,
            'mid' => $this->mid,
            'shop_id' => $this->shop_id,
            'mobile' => $this->mobile,
        ]);

        $query->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'delivery_no', $this->delivery_no])
            ->andFilterWhere(['like', 'mark', $this->mark])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
