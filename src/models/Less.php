<?php

namespace dmstr\modules\prototype\models;

use dmstr\modules\prototype\models\base\Less as BaseLess;
use Yii;

/**
 * This is the model class for table "app_less".
 */
class Less extends BaseLess
{
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        \Yii::$app->cache->set('prototype.less.changed_at', time());
    }
}
