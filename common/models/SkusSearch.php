<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Skus;

/**
 * SkusSearch represents the model behind the search form about `common\models\Skus`.
 */
class SkusSearch extends Skus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku_id', 'sku_num', 'image', 'thumbImg', 'sku_values'], 'safe'],
            [['product_id', 'stock'], 'integer'],
            [['weight', 'market_price', 'sale_price'], 'number'],
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
        $query = Skus::find();

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
            'product_id' => $this->product_id,
            'weight' => $this->weight,
            'stock' => $this->stock,
            'market_price' => $this->market_price,
            'sale_price' => $this->sale_price,
        ]);

        $query->andFilterWhere(['like', 'sku_id', $this->sku_id])
            ->andFilterWhere(['like', 'sku_num', $this->sku_num])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'thumbImg', $this->thumbImg])
            ->andFilterWhere(['like', 'sku_values', $this->sku_values]);

        return $dataProvider;
    }
}
