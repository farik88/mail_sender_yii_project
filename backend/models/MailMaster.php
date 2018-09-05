<?php

namespace backend\models;

use Yii;
use \backend\models\base\MailMaster as BaseMailMaster;

/**
 * This is the model class for table "mail_masters".
 */
class MailMaster extends BaseMailMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name', 'smtp_host', 'username', 'password'], 'required'],
            [['smtp_port'], 'integer'],
            [['name', 'smtp_host', 'username', 'password'], 'string', 'max' => 255]
        ]);
    }
	
}
