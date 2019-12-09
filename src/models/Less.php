<?php

namespace dmstr\modules\prototype\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\models\base\Less as BaseLess;
use dmstr\modules\prototype\traits\EditorEntry;

/**
 * This is the model class for table "app_less".
 */
class Less extends BaseLess
{
    use EditorEntry;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['audit-trail'] = AuditTrailBehavior::class;
        return $behaviors;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        \Yii::$app->cache->set('prototype.less.changed_at', time());
    }
}
