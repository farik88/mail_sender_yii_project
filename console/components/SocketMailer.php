<?php
namespace console\components;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use backend\models\Receiver;
use backend\models\Release;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\swiftmailer\Mailer as Swiftmailer;

class SocketMailer implements MessageComponentInterface
{
    protected $clients;
    protected $mailers;
    
    public function __construct()
    {
        // Для хранения технической информации об присоединившихся клиентах используется технология SplObjectStorage, встроенная в PHP
        $this->clients = new \SplObjectStorage;
        $this->mailers = [];
    }
   
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (is_null($data)){
            echo "invalid data\n";
            return $from->close();
        }
        switch ($data['action']){
            case 'get_emails_for_sending':
                $this->startSendingEmails($from, $data['release_id']);
                break;
            case 'send_one_email':
                if(is_array($data['mails_list']) && !empty($data['mails_list'])){
                    $this->sendOneEmail($data, $from);
                }else{
                    $data['action'] = 'sending_finished';
                    $from->send(json_encode($data));
                    $from->close();
                }
                break;
            case 'sending_finished':
                $data['action'] = 'sending_finished';
                $from->send(json_encode($data));
                $from->close();
                break;
            default :
                $from->send('Error! Undifined action ' . $data['action']);
                break;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $this->mailers[$conn->resourceId] = null;
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $response = [];
        $response['result'] = 'error';
        $response['err_message'] = "Socket error has occurred: {$e->getMessage()}";
        $conn->send(json_encode($response));
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
    
    private function startSendingEmails($client=null, $release_id=null)
    {
        $response = [];
        $response['action'] = 'get_emails_for_sending';
        if(!$client || !$release_id){
            $response['result'] = 'error';
            $response['message'] = 'Wrong client or release id';
        }else{
            $mails_list = Receiver::find()->asArray()->where(['release_id' => $release_id])->andWhere(['status' => 'wait'])->all();
            $tolal_mails_in_release = count($mails_list);
            $release_obj = Release::find()->where(['id' => $release_id])->limit(1)->one();
            $mail_master_obj = $release_obj->mailMaster;
            $release = ArrayHelper::toArray($release_obj);
            $mail_master = ArrayHelper::toArray($mail_master_obj);
            if(!empty($mails_list)){
                $response['result'] = 'success';
                $response['mails_list'] = $mails_list;
                $response['tolal_mails_in_release'] = $tolal_mails_in_release;
                $response['tolal_mails_already_procces'] = 0;
                $response['release'] = $release;
                $response['mail_master'] = $mail_master;
            }else{
                $response['result'] = 'error';
                $response['err_message'] = 'No emails for sending in release id='.$release_id;
            }
        }
        $client->send(json_encode($response));
    }
    
    private function sendOneEmail($data, $client=null)
    {
        if($data && $client){
            $one_email = array_shift($data['mails_list']);
            if(!isset($this->mailers[$client->resourceId]) || empty($this->mailers[$client->resourceId])){
                $this->mailers[$client->resourceId] = $this->createMailer($data['mail_master']);
            }
            // Set email params
            $message = $this->mailers[$client->resourceId]->compose()
            ->setFrom([$data['release']['from_domain'] => $data['release']['from_name']])
            ->setSubject($data['release']['subject'])
            ->setHtmlBody($this->appendTracker($one_email['id'], $data['release']['content']))
            ->setTo($one_email['email']);
            // Email sending
            $result = $this->mailers[$client->resourceId]->send($message);
            // Update receiver status
            Receiver::updateAfterMailing($one_email['id'], $result);
            $data['action'] = 'send_one_email';
            $data['result'] = 'success';
            $data['tolal_mails_already_procces']++;
            $data['send_log']['email'] = $one_email['email'];
            $data['send_log']['result'] = $result ? 'success' : 'fail';
            $data['send_log']['result_message'] = $result ? 'Отправлено' : 'Ошибка';
        }else{
            $data['result'] = 'error';
            $data['err_message'] = 'Wrong client or $data in sendOneEmail function!';
        }
        $client->send(json_encode($data));
    }
    
    private function createMailer($mail_master)
    {
        // Create Swiftmailer
        $mailer = new Swiftmailer();
        $mailer->setTransport([
            'class' => 'Swift_SmtpTransport',
            'host' => $mail_master['smtp_host'],
            'username' => $mail_master['username'],
            'password' => $mail_master['password'],
            'port' => $mail_master['smtp_port'],
        ]);
        return $mailer;
    }
    
    private function appendTracker($receiver_id, $html)
    {
        $tracker_url = Url::to(['admin/mail-tracker/track', 'receiver_id' => $receiver_id], true);
        $tracker_img = '<img src="' . $tracker_url . '">';
        $insert_variants = ['</td>', '</p>', '</div>', '</body>'];
        $insert_position = false;
        $html_with_tracker = '';
        foreach ($insert_variants as $variant){
            $find = strripos($html, $variant);
            if($find){
                $insert_position = $find;
                break;
            }
        }
        if($insert_position){
            $html_with_tracker = substr_replace($html, $tracker_img, $insert_position, 0);
        }else{
            $html_with_tracker = $html . $tracker_img;
        }
        return $html_with_tracker;
    }
}