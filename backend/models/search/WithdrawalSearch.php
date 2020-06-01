<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Withdrawal;

/**
 * WithdrawalSearch represents the model behind the search form about `common\models\Withdrawal`.
 */
class WithdrawalSearch extends Withdrawal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['id',  'updated_at', 'pay_time', 'status'], 'integer'],
                [['pay_amount', 'taxfee'], 'number'],
                [['bank_name', 'bank_card', 'realname', 'remark', 'transaction_id','m_id', 'error_code'], 'safe'],
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
        $query = Withdrawal::find()->alias('w')->joinWith('member m');
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
        {
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
        
        // $this->load($params);
        
        if (isset($params['WithdrawalSearch'])){
            $this->load($params);
        }else{
            $this->load($params,'');
        }
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if($this->created_at){
            $query->andFilterWhere(['between','created_at',$this->created_at,$this->created_at+24*3600]);
        }
        if($this->m_id){
            $query->andFilterWhere(['like', 'username',$this->m_id]);
        }
        $query->andFilterWhere([
                'w.id' => $this->id,
                'pay_amount' => $this->pay_amount,
                
                'updated_at' => $this->updated_at,
                'pay_time' => $this->pay_time,
                'taxfee' => $this->taxfee,
                'status' => $this->status,
        ]);
        
        $query->andFilterWhere(['like', 'bank_name', $this->bank_name])
        ->andFilterWhere(['like', 'bank_card', $this->bank_card])
        ->andFilterWhere(['like', 'realname', $this->realname])
        ->andFilterWhere(['like', 'remark', $this->remark])
        ->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
        ->andFilterWhere(['like', 'error_code', $this->error_code]);
        
        return $dataProvider;
    }
}
