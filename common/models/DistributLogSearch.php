<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DistributLog;

/**
 * DistributLogSearch represents the model behind the search form about `common\models\DistributLog`.
 */
class DistributLogSearch extends DistributLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'cid', 'level', 'goods_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['change_money'], 'number'],
            [['order_no'], 'string', 'max' => 32],
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
        $query = DistributLog::find()->alias('d')->joinWith('member as m')->joinWith('ordersku as o');
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<100) //如果请求参数包含条数，则限制返回数量。单次查询最大100条
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

        if (isset($params['DistributLogSearch'])){
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
            'pid' => $this->pid,
            'cid' => $this->cid,
            'level' => $this->level,
            'goods_id' => $this->goods_id,
            'change_money' => $this->change_money,
            'd.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order_no'=>$this->order_no,
        ]);

        return $dataProvider;
    }
}
