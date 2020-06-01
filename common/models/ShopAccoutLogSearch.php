<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ShopAccoutLog;

/**
 * ShopAccoutLogSearch represents the model behind the search form about `common\models\ShopAccoutLog`.
 */
class ShopAccoutLogSearch extends ShopAccoutLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'shop_id', 'updated_at', 'created_at'], 'integer'],
            [['money', 'type', 'comment','order_no'], 'safe'],
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
        $query = ShopAccoutLog::find();
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50){
            $num=$params['num'];
        }else{
            $num=10;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'defaultPageSize' =>$num,
                'validatePage'=>false,
            ],
        ]);
        if(isset($params['ShopAccoutLogSearch'])){
            $this->load($params);
        }else{
            $this->load($params,'');
        }
       

        if (!$this->validate()) {
            return $dataProvider;
        } 

        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'order_no' => $this->order_no,
            'type'=>$this->type,
            'updated_at' => $this->updated_at,
       
        ]);
        if (!empty($this->created_at)) {
            $query->andFilterCompare('created_at', strtotime(explode(' - ', $this->created_at)[0]), '>=');//起始时间
            $query->andFilterCompare('created_at', (strtotime(explode(' - ', $this->created_at)[1]) + 86400), '<');//结束时间
        } 

        $query->andFilterWhere(['like', 'money', $this->money])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
