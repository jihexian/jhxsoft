<?php

namespace backend\controllers;

use common\components\phpexcel\BasePHPExcel;
use common\models\Card;
use common\models\CardItem;
use common\models\CardSearch;
use common\models\UploadForm;
use common\modules\attachment\components\UploadedFile;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CardController implements the CRUD actions for Card model.
 */
class CardController extends Controller
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
    /**
     * Lists all Card models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Card model.
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
     * Creates a new Card model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Card();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Card model.
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
     * Deletes an existing Card model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Card model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Card the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Card::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 导入充值卡
     */
    public function actionExcelImport(){
        $model = new UploadForm();
        if ($model->load(Yii::$app->request->post())) {
            $cardId =  Yii::$app->request->post('card_id');
            $model->file = UploadedFile::getInstance($model, 'file');
            $result = $model->upload();
            
            if ($result['status']) {
                $errors = array();
                $success = array();
                // 文件上传成功
                $filename = $result['data'];
                $objPHPExcel = new BasePHPExcel();
                $fileType   = \PHPExcel_IOFactory::identify($filename); //文件名自动判断文件类型
                $excelReader  = \PHPExcel_IOFactory::createReader($fileType);
                $phpexcel    = $excelReader->load($filename)->getSheet(0);//载入文件并获取第一个sheet
                $total_line  = $phpexcel->getHighestRow();//总行数
                $total_column= $phpexcel->getHighestColumn();//总列数
                $datas = array();
                if($total_line > 1){
                    for($row = 2;$row <= $total_line; $row++){
                        $data = array();
                        for($column = 'A'; $column <= $total_column; $column++){
                            $data[] = trim($phpexcel->getCell($column.$row)->getValue());
                        }
                        $cardItem = new CardItem();
                        $cardItem->loadDefaultValues();
                        $cardItem->card_id = $cardId;
                        $cardItem->card_no = $data[0];
                        $cardItem->password = base64_encode(Yii::$app->security->encryptByPassword($data[1], $data[0]));
                        $result =array();
                        if ($cardItem->save()) {
                            $result['card_no'] = $cardItem->card_no;
                            $result['password'] = $data[1];
                            $result['info'] = '成功';
                            $result['message'] = '';
                        }else{
                            $result['card_no'] = $cardItem->card_no;
                            $result['password'] = $data[1];
                            $result['info'] = '失败';
                            $result['message'] = current($cardItem->getFirstErrors());
                        }
                        array_push($datas, $result);
                        //一行行的插入数据库操作
                    }
                }
                $dataProvider = new ArrayDataProvider();
                $dataProvider->setModels($datas);
                unlink($filename);
                return $this->render('upload-log',['dataProvider'=>$dataProvider]);
            }else{
                var_dump($result);
            }
        }
        
        
        return $this->render('upload', ['model' => $model]);
        
    }
}
