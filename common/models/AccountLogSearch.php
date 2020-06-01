<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AccountLog;
use yii\helpers\ArrayHelper;

/**
 * AccountLogSearch represents the model behind the search form about `common\models\AccountLog`.
 */
class AccountLogSearch extends AccountLog
{
	public $change_type;
	public $startTime;
	public $endTime;
	public $money_type;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'member_id', 'score', 'change_score', 'created_at','endTime','startTime', 'type', 'user_id', 'updated_at','change_type','money_type'], 'integer'],
            ['info','string'],
            [['money', 'change_money'], 'number'],
            [['desc'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
    	return ArrayHelper::merge(['change_type'=>'金额类型'], parent::attributeLabels());
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
        $query = AccountLog::find();
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
            $num=$params['num'];
            else
            $num=10;
      
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ],
                'pagination' => [
                        'defaultPageSize' =>$num,           
                        'validatePage'=>false,
                ],
        ]);

    	if (isset($params['AccountLogSearch'])){
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
            'member_id' => $this->member_id,
            //'money' => $this->money,
            //'score' => $this->score,
            //'change_score' => $this->change_score,
            //'change_money' => $this->change_money,
            //'created_at' => $this->created_at,
            'type' => $this->type,
           // 'info' => $this->info,
            'user_id' => $this->user_id,
            //'updated_at' => $this->updated_at,
        ]);
        if ($this->change_type==1){
        	$query->andFilterWhere(['<>', 'change_score', 0]);
        }elseif ($this->change_type==2){
        	$query->andFilterWhere(['<>', 'change_money', 0]);
        }elseif ($this->change_type==3){
            $query->andFilterWhere(['>', 'change_money', 0]);
        }elseif ($this->change_type==4){
            $query->andFilterWhere(['<', 'change_money', 0]);
        }elseif ($this->change_type==5){
            $query->andFilterWhere(['>', 'change_score', 0]);
        }elseif ($this->change_type==6){
            $query->andFilterWhere(['<', 'change_score', 0]);
        }
        if($this->money_type==1){ //分销变化
                $query->andFilterWhere(['like', 'desc', '分销提现']);
        }elseif($this->money_type==2){//正常变化
                $query->andFilterWhere(['like', 'desc', '用户提现']);
        }else{
            
        }
     
        $query->andFilterWhere(['>=', 'created_at', $this->startTime]);
        $query->andFilterWhere(['<=', 'created_at', $this->endTime]);
    

        return $dataProvider;
    }
}
