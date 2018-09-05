<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Release */

$this->title = 'Редактировать релиз: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Релизы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать «' . $model->name . '»';
?>
<div class="release-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
