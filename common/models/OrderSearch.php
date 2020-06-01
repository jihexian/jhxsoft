<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'm_id',  'payment_status', 'delivery_id', 'delivery_status', 'shop_id', 'is_shop_checkout', 'status', 'coupons_id', 'integral', 'paytime', 'sendtime', 'completetime', 'is_del', 'is_distribut', 'update_time', 'prom_type', 'prom_id','province_id', 'city_id', 'region_id'], 'integer'],
            [['order_no','payment_code','payment_name', 'payment_no', 'delivery_name', 'delivery_time', 'full_name', 'tel',  'address', 'm_desc', 'admin_desc', 'invoice_title', 'taxpayer', 'parent_sn','create_time'], 'safe'],
            //['string'],
            [['sku_price', 'sku_price_real', 'delivery_price', 'delivery_price_real', 'discount_price', 'order_price', 'pay_amount', 'coupons_price','integral_money', 'user_money', 'paid_money'], 'number'] 
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
    public function search($params,$act='')
    {
        $query = Order::find();
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
            $num=$params['num'];
            else
                $num=10;
        switch ($act){
            case 'refuse': $query = Order::find()->notTrashed()->refuseing();break;
            case 'shipping': $query = Order::find()->shipping();break;
            default:$query = Order::find();break;
            
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                    'status' => SORT_ASC
                 
                ]
            ],
                'pagination' => [
                        'defaultPageSize' =>$num,
                        'validatePage'=>false,
                ],
        ]);

        if (isset($params['OrderSearch'])){
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
            'm_id' => $this->m_id,
            'payment_code' => $this->payment_code,
            'payment_status' => $this->payment_status,
            'delivery_id' => $this->delivery_id,
            'delivery_status' => $this->delivery_status,
            'shop_id' => $this->shop_id,
            'is_shop_checkout' => $this->is_shop_checkout,
            'status' => $this->status,
            'sku_price' => $this->sku_price,
            'sku_price_real' => $this->sku_price_real,
            'delivery_price' => $this->delivery_price,
            'delivery_price_real' => $this->delivery_price_real,
            'discount_price' => $this->discount_price,
            'order_price' => $this->order_price,
            'pay_amount' => $this->pay_amount,
            'coupons_id' => $this->coupons_id,
            'coupons_price' => $this->coupons_price,
            'integral' => $this->integral,
            'integral_money' => $this->integral_money,
            'user_money' => $this->user_money,
            //'create_time' => $this->create_time,
            'paytime' => $this->paytime,
            'sendtime' => $this->sendtime,
            'completetime' => $this->completetime,
            'is_del' => $this->is_del,
            'is_distribut' => $this->is_distribut,
            'paid_money' => $this->paid_money,
            'update_time' => $this->update_time,
            'prom_type' => $this->prom_type,
            'prom_id' => $this->prom_id,
            'province_id'=>$this->province_id,
            'city_id'=>$this->city_id,
            'region_id'=>$this->region_id
        ]);
        
        if (!empty($this->create_time)&&strpos($this->create_time,' - ') !== false ) {
            $query->andFilterCompare('create_time', strtotime(explode(' - ', $this->create_time)[0]), '>=');//起始时间
            $query->andFilterCompare('create_time', (strtotime(explode(' - ', $this->create_time)[1]) + 86400), '<');//结束时间
        } 
   
        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'payment_name', $this->payment_name])
            ->andFilterWhere(['like', 'payment_no', $this->payment_no])
            ->andFilterWhere(['like', 'delivery_name', $this->delivery_name])
            ->andFilterWhere(['like', 'delivery_time', $this->delivery_time])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'tel', $this->tel])

            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'm_desc', $this->m_desc])
            ->andFilterWhere(['like', 'admin_desc', $this->admin_desc])
            ->andFilterWhere(['like', 'invoice_title', $this->invoice_title])
            ->andFilterWhere(['like', 'taxpayer', $this->taxpayer])
            ->andFilterWhere(['like', 'parent_sn', $this->parent_sn]);

        return $dataProvider;
    }
}
