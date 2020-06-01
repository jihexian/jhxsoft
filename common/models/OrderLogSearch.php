<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderLog;

/**
 * OrderLogSearch represents the model behind the search form about `common\models\OrderLog`.
 */
class OrderLogSearch extends OrderLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'order_no', 'user_id', 'order_status', 'shipping_status', 'pay_status','shop_id'], 'integer'],
            [['action_note', 'status_desc','create_time','action_user'], 'safe'],
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
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50){
            $num=$params['num'];
        }else{
            $num=10;
        }
        $query = OrderLog::find();

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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_no' => $this->order_no,
            'action_user' => $this->action_user,
            'order_status' => $this->order_status,
            'shipping_status' => $this->shipping_status,
            'pay_status' => $this->pay_status,
            'create_time' => $this->create_time,
            'shop_id'=>$this->shop_id
        ]);

        $query->andFilterWhere(['like', 'action_note', $this->action_note])
            ->andFilterWhere(['like', 'status_desc', $this->status_desc]);

        return $dataProvider;
    }
}
