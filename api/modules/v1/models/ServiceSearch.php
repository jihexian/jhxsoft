<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年5月27日下午5:20:04
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class ServiceSearch extends Service
{
    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['order_id', 'user_id','mid','shop_id'], 'integer'],
                [['amount'], 'number'],
                [['sku_id', 'delivery_no'], 'string', 'max' => 255],
                [['type', 'apply_status', 'receive_status', 'status'], 'integer', 'max' => 3],
                [['company'], 'string', 'max' => 50],
                [['mark'], 'string', 'max' => 500],
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
        $query =Service::find();
        
        
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
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
                                'defaultPageSize' =>$num,
                                'validatePage'=>false,
                        ],
                ]);
                
                $this->load($params,'');
                
                if (!$this->validate()) {   
                    return $dataProvider;
                }
                if(!empty($this->status)){
                    $query->andFilterWhere(['status'=>$this->status]);
                }
               
                $query->andFilterWhere([
                        'id' => $this->id,
                        'mid' => $this->mid,
                        'sku_id' => $this->sku_id,
                        'order_id' => $this->order_id,
                        'shop_id' => $this->shop_id,
                        'apply_status' => $this->apply_status,
                ]);
                
                return $dataProvider;
    }
}
