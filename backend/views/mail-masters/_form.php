<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MailMaster */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="mail-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Название']) ?>

    <?= $form->field($model, 'smtp_host')->textInput(['maxlength' => true, 'placeholder' => 'SMTP Host']) ?>

    <?= $form->field($model, 'smtp_port')->textInput(['placeholder' => 'Smtp Port']) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'Логин']) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true, 'placeholder' => 'Пароль']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
