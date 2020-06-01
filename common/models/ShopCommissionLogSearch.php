<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ShopCommissionLog;

/**
 * ShopCommissionLogSearch represents the model behind the search form about `common\models\ShopCommissionLog`.
 */
class ShopCommissionLogSearch extends ShopCommissionLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at','type'], 'integer'],
           [['order_no', 'm_id', 'shop_id','desc'], 'safe'],
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
        //$query = ShopCommissionLog::find();
        $query=ShopCommissionLog::find()->alias('c')->joinWith('member as m')->joinWith('shop as s')->joinWith('shopcategory as a');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        if (isset($params['ShopCommissionLogSearch'])){
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
            'type'=>$this->type,
           // 'm_id' => $this->m_id,
           // 'shop_id' => $this->shop_id,
            'money' => $this->money,
            'percentage' => $this->percentage,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
             
        ]);
        if($this->m_id){
            $query->andFilterWhere(['like', 'm.username', $this->m_id]);
        }
        if($this->shop_id){
            $query->andFilterWhere(['like', 's.name', $this->shop_id]);
        }
        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
