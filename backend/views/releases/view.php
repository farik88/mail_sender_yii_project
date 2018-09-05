<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Release */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Релиз', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="release-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
            
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить релизЫ?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="container">
            <?php 
                $gridColumn = [
                    ['attribute' => 'id', 'visible' => false],
                    'name',
                    [
                        'attribute' => 'mailMaster.name',
                        'label' => 'SMTP конфиг',
                    ],
                    'subject',
                    'from_name',
                    'from_domain',
                    'content:ntext',
                    
                ];
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $gridColumn
                ]);
            ?>
        </div>
    </div>
    
    <div class="row">
<?php
if($providerReceivers->totalCount){
    $gridColumnReceivers = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            'email:email',
            'status',
                ];
    echo Gridview::widget([
        'dataProvider' => $providerReceivers,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-receivers']],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => 'Все получтели',
            'header' => false,
            'footer' =>false,
            'before' => false,
            'after' => false
        ],
        'export' => false,
        'columns' => $gridColumnReceivers
    ]);
}
?>

    </div>
</div>
