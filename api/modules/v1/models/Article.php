<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2020年1月3日上午10:34:06
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;
use yii\helpers\ArrayHelper;
use yii;
use api\modules\v1\models\Category;
use common\behaviors\CommentBehavior;
class Article extends \common\models\Article
{
    public function extraFields()
    {
        return [
        	
            'data',        	
        ];
    }

}