<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$this->title = $name;
?>
<style>
html,body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
 }
.site-error{
 width:300px;
 height:300px;
 margin:0 auto;
 position: relative; /*脱离文档流*/
 top:100px;
 text-align:center;
}
.site-error h1{
  font-size:.6rem;
}



</style>
<div class="site-error">

    <h1>404,页面找不到了</h1>
    <div class="error-search">
     <!--<form action="<?= url(['/search']) ?>" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="q" placeholder="全站搜索">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                </span>
            </div>
        </form> -->  
    </div>


</div>
