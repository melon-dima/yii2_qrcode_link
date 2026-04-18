<?php

use yii\db\Migration;

class m240101_000002_create_link_log_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%link_log}}', [
            'id' => $this->primaryKey(),
            'link_id' => $this->integer()->notNull(),
            'ip' => $this->string(45)->notNull(),
            'user_agent' => $this->text()->null(),
            'visited_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->addForeignKey(
            'fk-link_log-link_id',
            '{{%link_log}}',
            'link_id',
            '{{%link}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-link_log-link_id', '{{%link_log}}', 'link_id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%link_log}}');
    }
}
