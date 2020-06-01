<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 2017/4/14
 * Time: 下午10:49
 */

namespace api\modules\v1\controllers;


use api\common\controllers\Controller;

class SmsController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            
            'sms'=>[
                'class' => 'common\\actions\\SmsAction'
            ]
            
        ];
    }

}