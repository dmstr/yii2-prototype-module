<?php

namespace dmstr\modules\prototype\models;

use dmstr\modules\prototype\models\base\Less as BaseLess;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "app_less".
 */
class Less extends BaseLess
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'bedezign\yii2\audit\AuditTrailBehavior',
            ]
        );
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        \Yii::$app->cache->set('prototype.less.changed_at', time());
    }
}
