<?php
/**
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-11-07 18:46
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
use frontend\themes\advance\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
AppAsset::register($this);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => isset($this->params['seo_site_keywords']) ? $this->params['seo_site_keywords'] : Yii::$app->config->get('seo_site_keywords')
], 'keywords');
$this->registerMetaTag([
    'name' => 'description',
    'content' => isset($this->params['seo_site_description']) ? $this->params['seo_site_description'] : Yii::$app->config->get('seo_site_description')
], 'description');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="baidu-site-verification" content="MccTnGKbkm" />
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ? Html::encode($this->title) . '-' . Yii::$app->config->get('site_name') : Yii::$app->config->get('site_name') ?></title>
    <link type="image/x-icon" href="<?= Yii::getAlias('@web') ?>favicon.ico" rel="shortcut icon">
    <script>var SITE_URL = '<?= Yii::$app->request->hostInfo . Yii::$app->request->baseUrl ?>';</script>
    <?php $this->head() ?>
</head>
<body>

<div class="wrap" style="margin-bottom: 1.3rem;">

<?php $this->beginBody() ?>
    <?= $content ?>
<?php
$this->registerJs(<<<JS
  var swiper = new Swiper('.index-ban', {
        pagination: '.index-ban .swiper-pagination',
        autoplay: 3000,
        loop: true
    });
JS
);
?>
<?php $this->endBody() ?>
<?php if (isset($this->blocks['js'])): ?>
    <?= $this->blocks['js'] ?>
<?php endif; ?>
<?php 




$arr=['order','user','product','cart','address','shop','coupon'];


if (!in_array($this->context->id,$arr)||$this->context->id=='shop'&&$this->context->action->id=='lists'): ?>

<?= $this->render('../layouts/_footer') ?>

<?php elseif ($this->context->id=='shop'&&$this->context->action->id!='lists'):?>
<?= $this->render('../layouts/shop_footer') ?>
<?php endif;?>
</div>
<?php if(!yii::$app->user->isGuest&&empty(yii::$app->user->identity->mobile)&&strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')):?>
  <?= $this->render('../layouts/_modal') ?>
<?php endif;?>
</body>
<?php $this->beginBlock('toast') ?>
         <?php $flag = Yii::$app->session->getFlash('success');if(!empty($flag)):?>
 			$.toast('<?=$flag?>')
 			<?php endif;?>
 		<?php $flag = Yii::$app->session->getFlash('error');if(!empty($flag)):?>
 			$.toast('<?=$flag?>', "forbidden");
         <?php endif; ?>
   
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['toast'], \yii\web\View::POS_END); ?>  
</html>
<?php $this->endPage() ?>
