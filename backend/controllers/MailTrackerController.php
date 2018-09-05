<?php

namespace backend\controllers;

use yii\web\Controller;
use backend\models\Receiver;

class MailTrackerController extends Controller{
    
    public function actionTrack($receiver_id)
    {
        $receiver = Receiver::find()->where(['id' => intval($receiver_id)])->limit(1)->one();
        if($receiver){
            $receiver->status = 'read';
            $receiver->save();
        }
    }
}
