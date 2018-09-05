<?php

use yii\db\Migration;

class m171122_134737_releases extends Migration
{
    public function up()
    {
        $this->createTable('releases', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'subject' => $this->string()->notNull(),
            'from_name' => $this->string()->notNull(),
            'from_domain' => $this->string()->notNull(),
            'content' => $this->text()->null(),
            'mail_master_id' => $this->integer()->null()
        ]);
        $this->addForeignKey('mail_master_key', 'releases', 'mail_master_id', 'mail_masters', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('releases');
    }

}
