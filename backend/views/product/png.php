<?php
/**
 *
 *
 * Author wsyone wsyone@faxmail.com
 * Time:2019年5月20日上午11:40:18
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */

use yii\helpers\Url;
?>
<style>
.center{
 margin:0px auto;
}
.center img{
width:500px;
height:500px;
}
</style>
<div class="box box-primary">
    <div class="box-body">
<div class="center">
<img alt="二维码" src="<?php echo Url::to(['product/qrcode','id'=>$id]);?>">
</div>
</div>
</div>