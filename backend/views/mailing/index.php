<?php
use kartik\helpers\Html;
use kartik\widgets\Select2;


/* @var $this yii\web\View */

$this->title = 'Рассылка';
?>
<div class="site-index">

    <?= Html::beginForm(); ?>
    
    <div class="row">
        <div class="col-sm-8 col-xs-12">
            <?php
            echo Select2::widget([
                'name' => 'release-change-to-start-select2',
                'id' => 'release-to-send-select',
                'data' => $select_data,
                'options' => [
                    'placeholder' => 'Выберите релиз ...',
                    'multiple' => false
                ],
            ]);
            ?>
        </div>
        <div class="col-sm-4 col-xs-12">
            <button type="button" id="start-sending-btn" class="btn btn-success pull-right">Запустить рассылку</button>
        </div>
    </div>
    <div class="row" id="send-mail-progress-zone">
        <div class="col-sm-12">
            <h4>Прогресс</h4>
            <div class="progress" id="send-mail-progressbar">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                  Загрузка...
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div id="mail-send-console-wrap">
                <h4>Статистика отправки</h4>
                <div id="mail-send-console">
                    <table>
                        <thead>
                            <tr>
                                <th class="col-xs-8 col-sm-8 col-md-8 col-lg-10">E-mail</th>
                                <th class="col-xs-4 col-sm-4 col-md-4 col-lg-2">Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <?= Html::endForm(); ?>
    
</div>
