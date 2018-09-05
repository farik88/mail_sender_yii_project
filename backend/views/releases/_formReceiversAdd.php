<?php

use kartik\helpers\Html;
?>
    <div class="form-group" id="add-receivers-textarea">
        <?= Html::label('Укажите email новых получателей, каждый с новой строки'); ?>
        <br>
        <?= Html::textarea('new_receivers[emails]', '', ['rows' => 10, 'class' => 'form-control']); ?>
    </div>
    <div class="form-group" id="add-receivers-textarea">
        <?= Html::label('Статус новых получателей'); ?>
        <br>
        <?= Html::radioButtonGroup('new_receivers[status]', 'wait', ['wait' => 'Ожидает отправки', 'sent' =>'Письмо отправлено', 'fail' => 'Ошибка при отправке']); ?>
    </div>
    <div class="form-group" id="add-receivers-textarea">
        <?= Html::label('Перезаписать уже существующие email', 'new-receivers-overwrite'); ?>
        <br>
        <?= Html::checkbox('new_receivers[overwrite]', true, ['id' => 'new-receivers-overwrite']); ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton($release_model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $release_model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    