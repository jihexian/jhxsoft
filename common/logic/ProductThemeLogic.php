<?php
/**
 * Author: vamper  
 * Time: 2018-10-25
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\models\ProductTheme;
use common\models\Product;

class ProductThemeLogic{
    /**
     * 
     * @param  $pageNum
     * @return 
     */
    public function getThemeList($pageNum){
        $lists = ProductTheme::find()->where(['status'=>1])->orderBy("sort ASC")->asArray()->all();
        foreach ($lists as &$v){
            if (!empty($v['village_id'])) {
                $v['products'] = Product::find()->where(['status'=>1,'village_id'=>$v['village_id'],'is_top'=>1])->limit($pageNum)->all();
            }elseif (!empty($v['town_id'])) {
                $v['products'] = Product::find()->where(['status'=>1,'town_id'=>$v['town_id'],'is_top'=>1])->limit($pageNum)->all();
            }elseif (!empty($v['district_id'])) {
                $v['products'] = Product::find()->where(['status'=>1,'district_id'=>$v['district_id'],'is_top'=>1])->limit($pageNum)->all();
            }elseif (!empty($v['city_id'])) {
                $v['products'] = Product::find()->where(['status'=>1,'city_id'=>$v['city_id'],'is_top'=>1])->limit($pageNum)->all();
            }elseif (!empty($v['province_id'])) {
                $v['products'] = Product::find()->where(['status'=>1,'province_id'=>$v['province_id'],'is_top'=>1])->limit($pageNum)->all();
            }
            
        }
        return $lists;
    }
   
}
