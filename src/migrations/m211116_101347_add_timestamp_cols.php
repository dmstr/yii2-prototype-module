<?php

use yii\db\Migration;

class m211116_101347_add_timestamp_cols extends Migration
{
    public function up()
    {
        $this->addColumn('{{%twig}}', 'created_at', $this->dateTime()->null());
        $this->addColumn('{{%twig}}', 'updated_at', $this->dateTime()->null());
        $this->addColumn('{{%less}}', 'created_at', $this->dateTime()->null());
        $this->addColumn('{{%less}}', 'updated_at', $this->dateTime()->null());
        $this->addColumn('{{%html}}', 'created_at', $this->dateTime()->null());
        $this->addColumn('{{%html}}', 'updated_at', $this->dateTime()->null());
    }

    public function down()
    {
        echo "m211116_101347_add_timestamp_cols cannot be reverted.\n";
        return false;
    }
}
