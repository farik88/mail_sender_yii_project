<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MailMaster */

$this->title = 'Редактировать SMTP конфиг: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'SMTP конфиги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="mail-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
