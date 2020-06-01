<?php

namespace common\modules\config\backend\controllers;

use common\modules\config\models\Config;
use common\modules\config\models\DatabaseConfigForm;
use common\modules\config\models\MailConfigForm;
use Yii;
use yii\web\Controller;
use yii\base\Model;
use yii\caching\TagDependency;
use backend\widgets\ActiveField;
use backend\widgets\ActiveForm;
use common\modules\config\models\SmsConfigForm;
use common\modules\config\models\BaseSmsConfigForm;
use common\modules\config\models\WxConfigForm;
use common\modules\config\models\XcxConfigForm;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
        $groups = Yii::$app->config->get('config_group');
        $group = Yii::$app->request->get('group', current(array_keys($groups)));
        $configModels = Config::find()->where(['group' => $group])->all();
        return $this->render('index', [
            'groups' => $groups,
            'group' => $group,
            'configModels' => $configModels
        ]);
    }
    public function actionStore()
    {
        $groups = Yii::$app->config->get('config_group');
        $group = Yii::$app->request->get('group', current(array_keys($groups)));
        $configModels = Config::find()->where(['group' => $group])->all();
        $flag = Model::loadMultiple($configModels, \Yii::$app->request->post());
        $errors = ActiveForm::validateMultiple($configModels);
        if ($flag && empty($errors)) {
            foreach ($configModels as $configModel) {
                /* @var $config Config */
                $configModel->save(false);
            }
            TagDependency::invalidate(\Yii::$app->cache,  Yii::$app->config->cacheTag);
            Yii::$app->session->setFlash('success', '保存成功');
            return $this->redirect(['index', 'group' => $group]);
        } else {
            Yii::$app->session->setFlash('error', current($errors));
            return $this->redirect(['index', 'group' => $group]);
        }
    }

    public function actionDatabase()
    {
        $model = new DatabaseConfigForm();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('database');
        }
        return $this->render('database', [
            'model' => $model
        ]);
    }

    public function actionMail()
    {
        $model = new MailConfigForm();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('mail');
        }
        return $this->render('mail', [
            'model' => $model
        ]);
    }
    
    public function actionBaseSms()
    {
        $model = new BaseSmsConfigForm();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '操作成功');
            return $this->redirect('base-sms');
        }
        return $this->render('base-sms', [
            'model' => $model
        ]);
    }
    
    public function actionWxConfig()
    {
        $model = new WxConfigForm();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '操作成功');
            return $this->redirect('wx-config');
        }
        return $this->render('wx-config', [
            'model' => $model
        ]);
    }
    
    public function actionXcxConfig()
    {
        $model = new XcxConfigForm();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '操作成功');
            return $this->redirect('xcx-config');
        }
        return $this->render('xcx-config', [
            'model' => $model
        ]);
    }
    
    
    public function actionSmsCommonTmp()
    {
        
        $model = new SmsConfigForm();
        $model->type = 1;
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '操作成功');
            return $this->redirect('sms-common-tmp');
        }
        return $this->render('sms-common-tmp', [
            'model' => $model
        ]);
    }
    
    public function actionSmsOrderTmp()
    {
        
        $model = new SmsConfigForm();
        $model->type = 2;
        $model->loadDefaultValues();
 
        if ($model->load(Yii::$app->request->post())&&$model->save()) {
            Yii::$app->session->setFlash('success', '操作成功');
            return $this->redirect('sms-order-tmp');
        }
        return $this->render('sms-common-tmp', [
            'model' => $model
        ]);
    }
    public function actionSmsDeliveryTmp()
    {
        
        $model = new SmsConfigForm();
        $model->type = 3;
        $model->loadDefaultValues();
        $model->load(Yii::$app->request->post());
        if ($model->save()) {
            Yii::$app->session->setFlash('success', '操作成功');
            return $this->redirect('sms-delivery-tmp');
        }
        return $this->render('sms-common-tmp', [
            'model' => $model
        ]);
    }
}
