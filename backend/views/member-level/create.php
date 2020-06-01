<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MemberLevel */

$this->title = '添加会员等级';
$this->params['breadcrumbs'][] = ['label' => 'Member Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-level-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
