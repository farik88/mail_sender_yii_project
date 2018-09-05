<?php

use yii\db\Migration;

/**
 * Class m171122_085148_mail_masters
 */
class m171122_085148_mail_masters extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mail_masters', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'smtp_host' => $this->string()->notNull(),
            'smtp_port' => $this->integer()->defaultValue(25),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%mail_masters}}');
    }

}
