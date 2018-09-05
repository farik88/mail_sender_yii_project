<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Release */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Receivers', 
        'relID' => 'receivers', 
        'value' => \yii\helpers\Json::encode($model->receivers),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="release-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
    
    <?= $form->field($model, 'mail_master_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\backend\models\MailMaster::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите SMTP конфиг'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('SMTP конфиг'); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Назварие релиза']) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true, 'placeholder' => 'Тема письма']) ?>

    <?= $form->field($model, 'from_name')->textInput(['maxlength' => true, 'placeholder' => 'Иван Иванович']) ?>

    <?= $form->field($model, 'from_domain')->textInput(['maxlength' => true, 'placeholder' => 'callback@mail.com']) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 17])->label('Html писмо') ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <div class="test-mail">
        <div class="row">
            <div class="col-sm-12">
                <h4>Тестовая отправка</h4>
            </div>
        </div>
        <div class="row">
            <div id="test-mail-success-message" class="col-sm-12">
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close">×</button>
                    <i class="icon fa fa-check"></i> Тестовое письмо было отправлено
                </div>
            </div>
            <div id="test-mail-error-message" class="col-sm-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close">×</button>
                    <i class="icon fa fa-warning"></i> При отправке тестового сообщения произошла ошибка
                </div>
            </div>
        </div>
        <div class="row form-elements">
            <div class="form-group col-xs-12 col-sm-5 col-lg-3">
                <?php $user_email_val = (isset(Yii::$app->user->identity->email) && !empty(Yii::$app->user->identity->email)) ? Yii::$app->user->identity->email : ''; ?>
                <?= Html::input('email', 'test-mail', $user_email_val, ['class' => 'form-control', 'id' => 'test-mailing-email','placeholder' => 'Ваш e-mail', 'data-release-id' => $model->id]); ?>
            </div>
            <div class="form-group col-xs-12 col-sm-7 col-lg-9">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-success', 'id' => 'test-mailing-submit']) ?>
                <div id="preloader-spiner">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <h4>Настройка получателей</h4>
        </div>
    </div>

    <?php
    $forms = [
        [
            'label' => '<i class="glyphicon glyphicon-user"></i> ' . Html::encode('Получатели'),
            'content' => $this->render('_formReceivers', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->receivers),
                'release_model' => $model,
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Html::encode('Добавить получателей'),
            'content' => $this->render('_formReceiversAdd', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->receivers),
                'release_model' => $model,
            ]),
        ]
    ];
    echo kartik\tabs\TabsX::widget([
        'items' => $forms,
        'position' => kartik\tabs\TabsX::POS_ABOVE,
        'encodeLabels' => false,
        'pluginOptions' => [
            'bordered' => true,
            'sideways' => true,
            'enableCache' => false,
        ],
    ]);
    ActiveForm::end(); ?>

</div>
