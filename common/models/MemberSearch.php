<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Member;

/**
 * MemberSearch represents the model behind the search form about `common\models\Member`.
 */
class MemberSearch extends Member
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'mobile_validated', 'email_validated', 'score', 'level', 'status', 'register_time', 'last_login', 'expire_in', 'flag', 'is_distribut', 'is_vip', 'version'], 'integer'],
            [['username', 'mobile', 'password', 'auth_key', 'xcx_openid', 'wx_openid', 'avatar', 'avatarUrl', 'email', 'sex', 'province', 'city', 'access_token', 'oauth_id', 'pay_pwd', 'underling_number', 'message_mask', 'push_id'], 'safe'],
            [['age', 'user_money', 'frozen_money', 'distribut_money', 'total_amount'], 'number'],
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
        $query = Member::find();
        
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
                        'pageSize' =>$num,
             ],
        ]);

        if (isset($params['MemberSearch'])){
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
            'mobile_validated' => $this->mobile_validated,
            'email_validated' => $this->email_validated,
            'age' => $this->age,
            'score' => $this->score,
            'level' => $this->level,
            'type' => $this->type,
            'status' => $this->status,
            'register_time' => $this->register_time,
            'last_login' => $this->last_login,
            'expire_in' => $this->expire_in,
            'flag' => $this->flag,
            'user_money' => $this->user_money,
            'frozen_money' => $this->frozen_money,
            'distribut_money' => $this->distribut_money,
            'total_amount' => $this->total_amount,
            'is_distribut' => $this->is_distribut,  
            'is_vip' => $this->is_vip,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'xcx_openid', $this->xcx_openid])
            ->andFilterWhere(['like', 'wx_openid', $this->wx_openid])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'avatarUrl', $this->avatarUrl])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'oauth_id', $this->oauth_id])
            ->andFilterWhere(['like', 'pay_pwd', $this->pay_pwd])
            ->andFilterWhere(['like', 'underling_number', $this->underling_number])
            ->andFilterWhere(['like', 'message_mask', $this->message_mask])
            ->andFilterWhere(['like', 'push_id', $this->push_id]);

        return $dataProvider;
    }
}
