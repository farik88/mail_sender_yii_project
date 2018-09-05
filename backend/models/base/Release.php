<?php

namespace backend\models\base;

use Yii;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "releases".
 *
 * @property integer $id
 * @property string $name
 * @property string $subject
 * @property string $from_name
 * @property string $from_domain
 * @property string $content
 * @property integer $mail_master_id
 *
 * @property \backend\models\Receivers[] $receivers
 * @property \backend\models\MailMaster $mailMaster
 */
class Release extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'receivers',
            'mailMaster'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'subject', 'from_name', 'from_domain'], 'required'],
            [['content'], 'string'],
            [['mail_master_id'], 'integer'],
            [['name', 'subject', 'from_name', 'from_domain'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'releases';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название релиза',
            'subject' => 'Тема письма',
            'from_name' => 'От кого (Имя)',
            'from_domain' => 'От кого (Email)',
            'content' => 'Письмо',
            'mail_master_id' => 'SMTP конфиг',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceivers()
    {
        return $this->hasMany(\backend\models\Receiver::className(), ['release_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMaster()
    {
        return $this->hasOne(\backend\models\MailMaster::className(), ['id' => 'mail_master_id']);
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
     * @return \backend\models\ReleaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\ReleaseQuery(get_called_class());
    }
}
