<?php

use yii\db\Migration;
use yii\db\Schema;

class m160504_032335_add_twig_table extends Migration
{
    public function up()
    {
        $this->createTable(
            '{{%twig}}',
            [
                'id' => Schema::TYPE_PK,
                'key' => Schema::TYPE_STRING.' NOT NULL',
                'value' => Schema::TYPE_TEXT.' NOT NULL',
            ]
        );
        $this->createIndex('twig_key_unique', '{{%twig}}', 'key', true);
    }

    public function down()
    {
        echo "m160504_032335_add_twig_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
