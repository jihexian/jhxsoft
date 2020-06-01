<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CardItem;

/**
 * CardItemSearch represents the model behind the search form about `common\models\CardItem`.
 */
class CardItemSearch extends CardItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'card_id', 'use_time', 'mid', 'created_at', 'updated_at', 'version'], 'integer'],
            [['info', 'password', 'card_no'], 'safe'],
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
        $query = CardItem::find();

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
            'card_id' => $this->card_id,
            'use_time' => $this->use_time,
            'mid' => $this->mid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'card_no', $this->card_no]);

        return $dataProvider;
    }
}
