<?php

namespace backend\controllers;
use common\logic\RegionLogic;
use Yii;
use common\models\Pick;
use common\models\PickSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;

/**
 * PickController implements the CRUD actions for Pick model.
 */
class PickController extends Controller
{
    /**
     * @inheritdoc
     */
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
            'switcher' => [
                'class' => 'backend\widgets\grid\SwitcherAction'
            ]
        ];
    }
    

    /**
     * Lists all Pick models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PickSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pick model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pick model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pick();
        $model->load(Yii::$app->request->post());
     
        if (  $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pick model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pick model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pick model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pick the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
   public  function findModel($id)
    {
        if (($model = Pick::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionRegion($id=null, $parent_id=null,$level=null)
    {
        $regionLogic = new RegionLogic();
        $model = $regionLogic->getRegions($id,$parent_id,$level);
        
        if($level == 1){$aa="--请选择省--";}else if($level == 2){$aa="--请选择市--";}else if($level == 3){$aa="--请选择地区--";}
        
        echo Html::tag('option',$aa, ['value'=>'']) ;
        
        foreach($model as $value=>$name)
        {
            echo Html::tag('option',Html::encode($name),array('value'=>$value));
        }
    }
}
