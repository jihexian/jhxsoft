<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ShopWithdraw;

/**
 * ShopWithdrawSearch represents the model behind the search form about `common\models\ShopWithdraw`.
 */
class ShopWithdrawSearch extends ShopWithdraw
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'apply_id', 'status', 'updated_at', 'created_at'], 'integer'],
            [['money'], 'number'],
            [['type', 'account', 'name', 'bank', 'mark'], 'safe'],
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
        $query = ShopWithdraw::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        if (isset($params['ShopWithdrawSearch'])){
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
            'money' => $this->money,
            'shop_id' => $this->shop_id,
            'apply_id' => $this->apply_id,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'mark', $this->mark]);

        return $dataProvider;
    }
}
