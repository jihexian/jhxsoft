<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Shop;

/**
 * ShopSearch represents the model behind the search form about `common\models\Shop`.
 */
class ShopSearch extends Shop
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id',  'created_at', 'updated_at', 'status', 'type','village_id','is_village','category_id','version','apply_status'], 'integer'],
            [['name', 'logo', 'image', 'address', 'description', 'license', 'idcard'], 'safe'],
            [['lng', 'lat','total_amount','percent','money'], 'number'],

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
        $query = Shop::find();

        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
            $num=$params['num'];
            else
                $num=10;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort'=>SORT_ASC,
                    'id' => SORT_DESC,
                    'money'=>SORT_DESC,
                    'is_village'=>SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' =>$num,
                'validatePage'=>false,
            ],
        ]);
        if(isset($params['ShopSearch'])){
            $this->load($params);
        }else{
            $this->load($params,'');
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'lng' => $this->lng,
            'lat' => $this->lat,
            'type' => $this->type,
            'village_id'=>$this->village_id,
            'is_village'=>$this->is_village,
            'category_id'=>$this->category_id,
            'percent'=>$this->percent,
            'total_amount'=>$this->total_amount,
            'money'=>$this->money,
            'version' => $this->version,
            'apply_status'=>$this->apply_status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'logo', $this->logo])
            //->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'license', $this->license])
            ->andFilterWhere(['like', 'idcard', $this->idcard]);
        return $dataProvider;
    }
    
}
