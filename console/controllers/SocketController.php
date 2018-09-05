<?php

namespace console\controllers;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use console\components\SocketMailer;

class SocketController extends  \yii\console\Controller
{
    public function actionStartMailer($port=8080)
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new SocketMailer()
                )
            ),
            $port
        );
        $server->run();
    }
}