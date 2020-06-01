<?php

namespace common\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * DistributSearch represents the model behind the search form about `common\models\Distribut`.
 */
class DistributSearch extends Distribut
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['id', 'level', 'pid', 'cid','created_at', 'updated_at'], 'integer'],
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
        $query = Distribut::find();
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<100) //如果请求参数包含条数，则限制返回数量。单次查询最大100条
            $num=$params['num'];
            else
                $num=10;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
                'pagination' => [
                        'pageSize' =>$num,
                ],
        ]);

        $this->load($params,'');

   if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
 
        $query->andFilterWhere([
            'id' => $this->id,
            'level' => $this->level,
            'pid' => $this->pid,
            'cid' => $this->cid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
