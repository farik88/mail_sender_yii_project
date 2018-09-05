<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model MailMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-mail-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Name']) ?>

    <?= $form->field($model, 'smtp_host')->textInput(['maxlength' => true, 'placeholder' => 'Smtp Host']) ?>

    <?= $form->field($model, 'smtp_port')->textInput(['placeholder' => 'Smtp Port']) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'Username']) ?>

    <?php /* echo $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'Password']) */ ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
