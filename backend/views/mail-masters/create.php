<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\MailMaster */

$this->title = 'Создать SMTP конфиг';
$this->params['breadcrumbs'][] = ['label' => 'SMTP конфиги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
