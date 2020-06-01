<?php

namespace backend\controllers;

use common\models\Member;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use Distill\Exception\IO\Exception;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use common\models\MemberSearch;
use common\models\TpUsers;

class MemberController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            
            'ajax-update-field' => [
                'class' => 'common\\actions\\AjaxUpdateFieldAction',
                'allowFields' => ['status'],
                'findModel' => [$this, 'findModel']
            ],
            'switcher' => [
                'class' => 'backend\widgets\grid\SwitcherAction'
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
    }
    
    public function actionList()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Member(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

//     public function actionDelete($id)
//     {
//         $this->findModel($id)->delete();

//         return $this->redirect(['index']);
//     }
    
    public function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
//     public function actionQianyi(){
//         $success = array();
//         $errors = array();
//         $users = TpUsers::find()->where(['>','user_id',4499])->andWhere(['<','user_id',7000])->all();
//         foreach ($users as $u)
//         {
//             $member = new Member();
//             $member->loadDefaultValues();
//             $member->id = $u->user_id;
//             $member->username = $u->nickname;            
//             $member->mobile = $u->mobile;
//             $member->mobile_validated = $u->mobile_validated;
//             $member->password = $u->password;
//             //$member->auth_key = $u->;
//             //$member->xcx_openid = $u->xc;            
//             $member->wx_openid = $u->openid;
//             //$member->avatar = $u->head_pic;
//             $member->avatarUrl = $u->head_pic;
//             $member->email = $u->email;
//             $member->email_validated = $u->email_validated;
//             if ($u->sex==1) {
//                 $member->sex = '男';
//             }elseif ($u->sex==2){
//                 $member->sex = '女';
//             }else{
//                 $member->sex = '保密';
//             }            
//             //$member->age = $u->ag;
//             //$member->province = $u->province;            
//             //$member->city = $u->city;            
//             $member->score = $u->pay_points;
//             $member->level = $u->level;
//             $member->status = $u->is_lock;
//             $member->register_time = $u->reg_time;
//             $member->last_login = $u->last_login;
//             //$member->access_token = $u->token;
//             //$member->expire_in = $u->ex;
//             //$member->oauth_id = $u->user_id;
//             //$member->flag = $u->user_id;
//             $member->pay_pwd = $u->paypwd;
//             $member->user_money = $u->user_money;
//             $member->frozen_money = $u->frozen_money;
//             $member->distribut_money = $u->distribut_money;
//             //$member->underling_number = $u->un;
//             $member->total_amount = $u->total_amount;
//             $member->is_distribut = $u->is_distribut;
//             //$member->distribut_level = $u->dis;
//             $member->first_leader = $u->first_leader;
//             $member->second_leader = $u->second_leader;
//             $member->third_leader = $u->third_leader;
//             //$member->message_mask = $u->me;
//             //$member->push_id = $u->user_id;
//             //$member->is_vip = $u->is;
//             //$member->version = $u->user_id;
//             //$member->type = $u->user_id;
//             try {
//                 if (!$member->save()) {
                   
//                     $arr = array();
//                     $arr['id'] = $u->user_id;
//                     $arr['err'] = $member->getErrors();
//                     array_push($errors, $arr);
//                 }  
//             } catch (Exception $e) {
//                 $arr = array();
//                 $arr['msg'] = $u->user_id;
//                 array_push($errors, $e->getMessage());
//             }
                      
//         }
//         var_dump($errors);
//     }

    /**
     * Updates an existing Emp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPwd($id)
    {
        $model = $this->findModel($id);
        $model->setScenario("pwd");
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('pwd', [
                'model' => $model,
            ]);
        }
    }
    
}