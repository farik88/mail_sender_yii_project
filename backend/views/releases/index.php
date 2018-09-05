<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = 'Релизы';
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="release-index">
    <p>
        <?= Html::a('Новый релиз', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'name',
            'value' => function($model){
                return Html::a($model->name, '/admin/releases/update?id=' . $model->id);
            },
            'format' => 'raw',
            'label' => 'Релиз',
        ],
        'subject',
        'from_name',
        'from_domain',
        [
                'attribute' => 'mail_master_id',
                'label' => 'SMTP конфиг',
                'format' => 'raw',
                'value' => function($model){
                    if ($model->mailMaster)
                    {return  Html::a($model->mailMaster->name, '/admin/mail-masters/update?id=' . $model->mail_master_id);}
                    else
                    {return NULL;}
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\backend\models\MailMaster::find()->asArray()->all(), 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Mail masters', 'id' => 'grid--mail_master_id']
            ],
        [
            'class' => 'yii\grid\ActionColumn',
        ],
    ]; 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumn,
        'pjax' => false,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-send"></span>  ' . Html::encode($this->title),
            'before' => false,
            'after' => false,
        ],
        'export' => false,
        'toolbar' => false,
    ]); ?>

</div>
