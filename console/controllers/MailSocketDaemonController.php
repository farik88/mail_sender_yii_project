<?php

namespace console\controllers;

use \vyants\daemon\DaemonController;
use Yii;

class MailSocketDaemonController extends DaemonController
{
    public function init(){
        //$this->demonize = true;
        parent::init();
    }
    /**
     * @return array
     */
    protected function defineJobs()
    {
        /*
        TODO: return task list, extracted from DB, queue managers and so on.
        Extract tasks in small portions, to reduce memory usage.
        */
        return ['start_mailing_socket'];
    }
    /**
     * @return jobtype
     */
    protected function doJob($job)
    {
        /*
        TODO: implement you logic
        Don't forget to mark task as completed in your task source
        */
        if(method_exists($this, $job)){
            call_user_func([$this,$job]);
        }
    }

    public function start_mailing_socket(){
        $out = null;
        exec('cd ' . Yii::$app->basePath . '/../' . PHP_EOL .
               'php -q yii socket/start-mailer &', $out);
    }
}