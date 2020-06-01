<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CarouselItem;

/**
 * CarouselItemSearch represents the model behind the search form about `common\models\CarouselItem`.
 */
class CarouselItemSearch extends CarouselItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'carousel_id', 'status', 'sort', 'created_at', 'updated_at', 'shop_id'], 'integer'],
            [['url', 'caption', 'image', 'thumbImg'], 'safe'],
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
        $query = CarouselItem::find();
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
            $num=$params['num'];
            else
            $num=6;
 
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

  
        if (isset($params['CarouselItem'])){
            $this->load($params);
        }else{
            $this->load($params,'');
        }
        $this->load($params);
     
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'carousel_id' => $this->carousel_id,
            'status' => $this->status,
            'sort' => $this->sort,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if($this->shop_id){
            $query->andFilterWhere(['shop_id'=>$this->shop_id]);
        }

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'caption', $this->caption])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'thumbImg', $this->thumbImg]);
        return $dataProvider;
    }
}
