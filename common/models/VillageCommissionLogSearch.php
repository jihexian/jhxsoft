<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * VillageCommissionLogSearch represents the model behind the search form about `common\models\VillageCommissionLog`.
 */
class VillageCommissionLogSearch extends VillageCommissionLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','created_at', 'updated_at'], 'integer'],
                [['order_no', 'desc', 'm_id', 'shop_id', 'village_id'], 'safe'],
            [['money', 'percentage'], 'number'],
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
        $query = VillageCommissionLog::find()
        ->alias('p')
        ->joinWith('member as m')
        ->joinWith('shop as s')
        ->joinWith('village as v');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        if (isset($params['VillageCommissionLogSearch'])){
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
         
            //'m_id' => $this->m_id,
            //'shop_id' => $this->shop_id,
            'money' => $this->money,
            'percentage' => $this->percentage,
//             'village_id' => $this->village_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
        ->andFilterWhere(['like',  'm.username',$this->m_id])
        ->andFilterWhere(['like', 's.name', $this->shop_id])
        ->andFilterWhere(['like', 'v.name', $this->village_id])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
