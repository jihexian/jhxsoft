<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Address;

/**
 * AddressSearch represents the model behind the search form about `common\models\Address`.
 */
class AddressSearch extends Address
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'uid', 'status', 'sort', 'is_default','is_pickup'], 'integer'],
            [['userName', 'postalCode', 'province_id', 'city_id', 'region_id', 'detailInfo', 'nationalCode', 'telNumber'], 'safe'],
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
        $query = Address::find();

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
            'uid' => $this->uid,
            'status' => $this->status,
            'sort' => $this->sort,
            'is_default' => $this->is_default,
            'is_pickup'=>$this->is_pickup,
        ]);

        $query->andFilterWhere(['like', 'userName', $this->userName])
            ->andFilterWhere(['like', 'postalCode', $this->postalCode])
            ->andFilterWhere(['like', 'province_id', $this->province_id])
            ->andFilterWhere(['like', 'city_id', $this->city_id])
            ->andFilterWhere(['like', 'region_id', $this->region_id])
            ->andFilterWhere(['like', 'detailInfo', $this->detailInfo])
            ->andFilterWhere(['like', 'nationalCode', $this->nationalCode])
            ->andFilterWhere(['like', 'telNumber', $this->telNumber]);

        return $dataProvider;
    }
}
