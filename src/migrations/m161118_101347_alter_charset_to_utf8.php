<?php

use yii\db\Migration;

class m161118_101347_alter_charset_to_utf8 extends Migration
{
    public function up()
    {
        Yii::$app->db->createCommand("ALTER TABLE {{%html}} CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci ;")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%less}} CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci ;")->execute();
        Yii::$app->db->createCommand("ALTER TABLE {{%twig}} CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci ;")->execute();
    }

    public function down()
    {
        echo "m160721_101347_alter_charset_to_utf8 cannot be reverted.\n";

        return false;
    }
}
