<?php

namespace console\controllers;

class WatcherDaemonController extends \vyants\daemon\controllers\WatcherDaemonController
{
    /**
     * @return array
     */
    protected function defineJobs()
    {
        sleep($this->sleep);
        //TODO: modify list, or get it from config, it does not matter
        $daemons = [
            ['className' => 'MailSocketDaemonController', 'enabled' => true],
//            ['className' => 'AnotherDaemonController', 'enabled' => false]
        ];
        return $daemons;
    }
}