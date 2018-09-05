<?php

namespace backend\models;

use Yii;
use \backend\models\base\Release as BaseRelease;
use yii\validators\EmailValidator;
use backend\models\Receiver;
use backend\models\MailMaster;
use yii\swiftmailer\Mailer as Swiftmailer;

/**
 * This is the model class for table "releases".
 */
class Release extends BaseRelease
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name', 'subject', 'from_name', 'from_domain'], 'required'],
            [['content'], 'string'],
            [['mail_master_id'], 'integer'],
            [['name', 'subject', 'from_name', 'from_domain'], 'string', 'max' => 255]
        ]);
    }
    
    public function addNewReceivers($new_receivers){
        $email_validator = new EmailValidator();
        $emails_textarea = $new_receivers['emails'];
        $status = $new_receivers['status'];
        $emails_explode = explode(PHP_EOL, $emails_textarea);
        foreach ($emails_explode as $email_row){
            $email = trim($email_row);
            if($email == ''){
                continue;
            }
            if(!$email_validator->validate($email)){
                Yii::$app->session->addFlash('warning', 'Email «' . $email . '» не был добавлен, т.к. он не валидный!');
                continue;
            }
            // Email valid
            $exist_receiver = Receiver::find()->where('email = :email', [':email' => $email])->andWhere('release_id = :release_id', [':release_id' => $this->id])->one();
            if(!$exist_receiver){
                // Create new email for this release
                $new_receiver = new Receiver();
                $new_receiver->email = $email;
                $new_receiver->status = $status;
                $new_receiver->release_id = $this->id;
                $new_receiver->save();
                continue;
            }
            if(!isset($new_receivers['overwrite']) || !$new_receivers['overwrite']){
                // Email already exist and overwrite not activated
                Yii::$app->session->addFlash('warning', 'Email «' . $email . '» не был добавлен, т.к. он уже есть в списке!');
                continue;
            }
            // Overwrite exitsting email for this release
            $exist_receiver->email = $email;
            $exist_receiver->status = $status;
            $exist_receiver->release_id = $this->id;
            $exist_receiver->save();
        }
    }
    
    public function sendTestEmail($email)
    {
        $mail_master = MailMaster::find()->where(['id' => $this->mail_master_id])->limit(1)->one();
        $mailer = new Swiftmailer();
        $mailer->setTransport([
            'class' => 'Swift_SmtpTransport',
            'host' => $mail_master->smtp_host,
            'username' => $mail_master->username,
            'password' => $mail_master->password,
            'port' => $mail_master->smtp_port,
        ]);
        // Set email params
        $message = $mailer->compose()
        ->setFrom([$this->from_domain => $this->from_name])
        ->setSubject($this->subject)
        ->setHtmlBody($this->content)
        ->setTo($email);
        // Email sending
        return $mailer->send($message);
    }
	
}
