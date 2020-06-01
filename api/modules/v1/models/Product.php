<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 2017/3/8
 * Time: 下午11:21
 */

namespace api\modules\v1\models;


use common\helpers\Util;

class Product extends \common\models\Product
{
    public function afterFind(){
        //处理图片信息
        if (strlen($this->content)>0){            
            $this->content = Util::ImagesAddPrefix($this->content,'img src="/storage/upload');
        }
        return parent::afterFind();
    }
    
}