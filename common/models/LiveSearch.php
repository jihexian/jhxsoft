<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Live;

/**
 * LiveSearch represents the model behind the search form about `common\models\Live`.
 */
class LiveSearch extends Live
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'roomid', 'live_status', 'start_time', 'end_time', 'is_top', 'is_del', 'created_at', 'updated_at', 'sort'], 'integer'],
            [['name', 'cover_img', 'anchor_name', 'anchor_img', 'goods', 'live_replay'], 'safe'],
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
        $query = Live::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                    'status'=> SORT_ASC,
                    'start_time' => SORT_DESC,
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
            'roomid' => $this->roomid,
            'live_status' => $this->live_status,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_top' => $this->is_top,
            'is_del' => $this->is_del,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'cover_img', $this->cover_img])
            ->andFilterWhere(['like', 'anchor_name', $this->anchor_name])
            ->andFilterWhere(['like', 'anchor_img', $this->anchor_img])
            ->andFilterWhere(['like', 'goods', $this->goods])
            ->andFilterWhere(['like', 'live_replay', $this->live_replay]);

        return $dataProvider;
    }
}
