<?php

namespace backend\controllers;

use Yii;
use backend\models\Release;
use backend\models\Receiver;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ReleasesController implements the CRUD actions for Release model.
 */
class ReleasesController extends Controller
{
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'add-receivers', 'bulk-action', 'send-test-email'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => false
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Release models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Release::find(),
            'sort' => ['attributes' => [
                'name',
                'subject',
                'from_name',
                'from_domain',
                'mail_master_id'
            ]],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Release model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerReceivers = new \yii\data\ArrayDataProvider([
            'allModels' => $model->receivers,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerReceivers' => $providerReceivers,
        ]);
    }

    /**
     * Creates a new Release model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Release();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(!empty(Yii::$app->request->post()['new_receivers']['emails'])){
                $model->addNewReceivers(Yii::$app->request->post()['new_receivers']);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Release model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(!empty(Yii::$app->request->post()['new_receivers']['emails'])){
                $model->addNewReceivers(Yii::$app->request->post()['new_receivers']);
            }
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Release model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteWithRelated();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the Release model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Release the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Release::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Receivers
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return mixed
    */
    public function actionAddReceivers()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Receivers');
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formReceivers', ['row' => $row]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionBulkAction()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            switch ($post['action']){
                case 'delete':
                    $rows_changed = Receiver::deleteAll(['in', 'id', $post['ids']]);
                    if($rows_changed || $rows_changed === 0){
                        echo json_encode(['result' => 'ok', 'message' => 'Delete was complete!']);
                        die();
                    }else{
                        echo json_encode(['result' => 'error', 'message' => 'Error with receivers delete!']);
                        die();
                    }
                break;
                case 'set-status-wait':
                    $this->setReceiversStatus('wait', $post['ids']);
                break;
                case 'set-status-sent':
                    $this->setReceiversStatus('sent', $post['ids']);
                break;
                case 'set-status-fail':
                    $this->setReceiversStatus('fail', $post['ids']);
                break;
                case 'set-status-read':
                    $this->setReceiversStatus('read', $post['ids']);
                break;
                default :
                break;
            }
        } else {
            throw new NotFoundHttpException('Olny for ajax!');
        }
    }
    
    public function actionSendTestEmail()
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $release = Release::find()->where(['id' => $post['release_id']])->limit(1)->one();
            $send_result = $release->sendTestEmail($post['email']);
            return json_encode([
                'result' => $send_result
            ]);
        } else {
            throw new NotFoundHttpException('Olny for ajax!');
        }
        die();
    }
    
    /**
     * 
     * @param type $status - string name of receiver status
     * @param type $ids - array with id's of receivers, wich status we change
     * Return void
     */
    public static function setReceiversStatus($status, $ids)
    {
        $rows_changed = Receiver::updateAll(['status' => $status], ['in', 'id', $ids]);
        if($rows_changed || $rows_changed === 0){
            echo json_encode(['result' => 'ok', 'message' => 'Receivers status was changed!']);
            die();
        }else{
            echo json_encode(['result' => 'error', 'message' => 'Error with receivers status change!']);
            die();
        }
    }
}
