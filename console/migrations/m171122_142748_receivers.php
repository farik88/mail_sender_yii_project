<?php

use yii\db\Migration;

/**
 * Class m171122_142748_receivers
 */
class m171122_142748_receivers extends Migration
{
    
    public function up()
    {
        $this->createTable('receivers', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('wait'),
            'release_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('release_foreign_key', 'receivers', 'release_id', 'releases', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('receivers');
    }
    
}
