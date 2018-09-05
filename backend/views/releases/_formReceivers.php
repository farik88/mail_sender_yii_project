<div class="form-group" id="add-receivers">
<?php
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\Pjax;

    $dataProvider = new ArrayDataProvider([
        'allModels' => $row,
        'pagination' => [
            'pageSize' => 70
        ]
    ]);
    
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'id',
            'hidden' => true,
            'contentOptions' => ['class' => 'receiver-id-link']
        ],
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'pageSummary' => true,
        ],
        'email',
        [
            'attribute' => 'status',
            'label' => 'Статус',
            'value' => function($model){
                switch ($model['status']){
                    case 'wait':
                        return '<span style="color:#444444;">' . 
                                    '<i class="fa fa-hourglass" aria-hidden="true"></i>' . '&nbsp;&nbsp;' . 'Ожидает отправки' . 
                                '</span>';
                    case 'sent':
                        return '<span style="color:#079e59;">' . 
                                    '<i class="fa fa-envelope" aria-hidden="true"></i>' . '&nbsp;&nbsp;' . 'Письмо отправлено' . 
                                '</span>';
                    case 'fail':
                        return '<span style="color:#dd4b39;">' . 
                                    '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>' . '&nbsp;&nbsp;' . 'Ошибка при отправке' . 
                                '</span>';
                    case 'read':
                        return '<span style="color:#3c8dbc;">' . 
                                    '<i class="fa fa-eye" aria-hidden="true"></i>' . '&nbsp;&nbsp;' . 'Прочитано' . 
                                '</span>';
                    default :
                        return '(undefined)';
                }
            },
            'format' => 'raw',
        ],
                    
    ];
            
    $action_buttons = '
            <div class="btn-group">
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Статус <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#" class="receivers-bulk-button" data-action="wait">Ожидает отправки</a></li>
                        <li><a href="#" class="receivers-bulk-button" data-action="sent">Письмо отправлено</a></li>
                        <li><a href="#" class="receivers-bulk-button" data-action="fail">Ошибка при отправке</a></li>
                        <li><a href="#" class="receivers-bulk-button" data-action="read">Прочитано</a></li>
                    </ul>
                </div>
                <button type="button" id="kill-changed-receivers" class="btn btn-danger">Delete</button>
            </div>';

    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'release-receivers-grid',
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-release']],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => '<span class="glyphicon glyphicon-send"></span>  ' . Html::encode($this->title),
            'before' => $action_buttons,
            'after' => $action_buttons,
        ],
        'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_PDF => false
                ]
            ]) ,
        ],
    ]); 
    Pjax::end();
    ?>
    <div class="form-group">
        <?= Html::submitButton($release_model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $release_model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
