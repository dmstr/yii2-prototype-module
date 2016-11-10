<?php

use yii\db\Migration;

class m161013_011345_settings extends Migration
{
    public function up()
    {
        if (Yii::$app->has('settings') && !Yii::$app->settings->get('registerPrototypeAssetKey', 'app.assets', false)) {
            Yii::$app->settings->set('registerPrototypeAssetKey', 'default', 'app.assets', 'string');
            Yii::$app->settings->deactivate('registerPrototypeAssetKey', 'app.assets');
        }
        return true;
    }

    public function down()
    {
        if (Yii::$app->has('settings')) {
            Yii::$app->settings->delete('registerPrototypeAssetKey', 'app.assets');
        }

        return true;
    }

}
