<?php

namespace backend\controllers;

use yii\web\Controller;
use backend\models\Release;
use yii\filters\AccessControl;

class MailingController extends Controller{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \Exception('У вас нет доступа к этой странице!');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $releases = Release::find()->all();
        $select_data = [];
        foreach ($releases as $release){
            $select_data[$release->id] = $release->name;
        }
        return $this->render('index', [
            'select_data' => $select_data
        ]);
    }
}
