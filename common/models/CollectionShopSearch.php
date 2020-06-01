<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CollectionShop;

/**
 * CollectionShopSearch represents the model behind the search form about `common\models\CollectionShop`.
 */
class CollectionShopSearch extends CollectionShop
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'member_id', 'created_at'], 'integer'],
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
        $query = CollectionShop::find()->With('shop');
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
            $num=$params['num'];
            else
                $num=10;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ],
            ],
            'pagination' => [
                'pageSize' =>$num,
            ],
        ]);

        if (isset($params['CollectionShopSearch'])){
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
            'shop_id' => $this->shop_id,
            'member_id' => $this->member_id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
