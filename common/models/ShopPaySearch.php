<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ShopPay;

/**
 * ShopPaySearch represents the model behind the search form about `common\models\ShopPay`.
 */
class ShopPaySearch extends ShopPay
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'sort', 'updated_at', 'created_at','shop_id'], 'integer'],
            [['account', 'name', 'bank'], 'safe'],
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
        $query = ShopPay::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params,'');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'sort' => $this->sort,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'shop_id'=>$this->shop_id,
        ]);


        //     ->andFilterWhere(['like', 'account', $this->account])
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'bank', $this->bank]);

        return $dataProvider;
    }
}
