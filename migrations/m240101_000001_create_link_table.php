<?php

use yii\db\Migration;

class m240101_000001_create_link_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%link}}', [
            'id' => $this->primaryKey(),
            'original_url' => $this->text()->notNull(),
            'code' => $this->string(10)->notNull()->unique(),
            'clicks' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->createIndex('idx-link-code', '{{%link}}', 'code', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%link}}');
    }
}
