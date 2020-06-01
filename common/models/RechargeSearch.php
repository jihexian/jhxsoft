<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Recharge;

/**
 * RechargeSearch represents the model behind the search form about `common\models\Recharge`.
 */
class RechargeSearch extends Recharge
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
                [['order_no', 'pay_amount', 'payment_code', 'payment_name', 'created_at', 'updated_at', 'pay_status','m_id'], 'safe'],
            ['created_at', function($attr, $params) {
                    if ($this->hasErrors()) return false;
                    
                    $datetime = $this->{$attr};
                    
                    $time = strtotime($datetime);
                    // 验证时间格式是否正确
                    if ($time === false) {
                        $this->addError($attr, '时间格式错误.');
                        return false;
                    }
                    // 将转换为时间戳后的时间赋值给time属性
                    $this->{$attr} = $time;
                    return true;
                }],
                
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
        $query = Recharge::find()->alias('r')->joinWith('member m');

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
            'r.id' => $this->id,
           
        ]);
        if($this->m_id){
            $query->andFilterWhere(['like', 'username', $this->m_id]);
        }
        if($this->created_at){
            $query->andFilterWhere(['between','created_at',$this->created_at,$this->created_at+24*3600]);
        }
        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'pay_amount', $this->pay_amount])
            ->andFilterWhere(['like', 'payment_code', $this->payment_code])
            ->andFilterWhere(['like', 'payment_name', $this->payment_name])
    
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'pay_status', $this->pay_status]);

        return $dataProvider;
    }
}
