<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderRefundDoc;

/**
 * OrderRefundDocSearch represents the model behind the search form about `common\models\OrderRefundDoc`.
 */
class OrderRefundDocSearch extends OrderRefundDoc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'm_id',  'addtime', 'status', 'dispose_time', 'shop_id','check_status'], 'integer'],
            [['note','message', 'admin_user','out_refund_no'], 'safe'],
            [['amount'], 'number'],
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
        $query = OrderRefundDoc::find();

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
            'order_id' => $this->order_id,
            'm_id' => $this->m_id,
            'addtime' => $this->addtime,
            'status' => $this->status,
            'dispose_time' => $this->dispose_time,
            'shop_id' => $this->shop_id,
            'amount' => $this->amount,
                'out_refund_no'=>$this->out_refund_no,
        ]);

        //$query->andFilterWhere(['like', 'note', $this->note])
          //  ->andFilterWhere(['like', 'admin_user', $this->admin_user]);

        return $dataProvider;
    }
}
