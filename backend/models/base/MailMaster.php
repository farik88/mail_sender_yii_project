<?php

namespace backend\models\base;

use Yii;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "mail_masters".
 *
 * @property integer $id
 * @property string $name
 * @property string $smtp_host
 * @property integer $smtp_port
 * @property string $username
 * @property string $password
 */
class MailMaster extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'smtp_host', 'username', 'password'], 'required'],
            [['smtp_port'], 'integer'],
            [['name', 'smtp_host', 'username', 'password'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_masters';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'smtp_host' => 'SMTP Host',
            'smtp_port' => 'SMTP Port',
            'username' => 'Логин',
            'password' => 'Пароль',
        ];
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'uuid' => [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }


    /**
     * @inheritdoc
     * @return \backend\models\MailMasterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\MailMasterQuery(get_called_class());
    }
}
