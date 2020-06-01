<?php

namespace backend\controllers;
use backend\common\controllers\Controller;
class FileManagerController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
