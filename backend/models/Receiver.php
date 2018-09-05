<?php

namespace backend\models;

use Yii;
use \backend\models\base\Receiver as BaseReceiver;

/**
 * This is the model class for table "receivers".
 */
class Receiver extends BaseReceiver
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['email', 'release_id'], 'required'],
            [['release_id'], 'integer'],
            [['email', 'status'], 'string', 'max' => 255],
            [['email'], 'email']
        ]);
    }
    
    /**
     * @param type $id - id of Receiver
     * @param type $result - SwiftMailer send function result
     */
    public static function updateAfterMailing($id, $result){
        $model = static::find()->where(['id' => $id])->limit(1)->one();
        $new_status = $result ? 'sent' : 'fail';
        $model->status = $new_status;
        $model->save();
    }
	
}
