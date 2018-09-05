<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Release */

$this->title = 'Create Release';
$this->params['breadcrumbs'][] = ['label' => 'Release', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="release-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
