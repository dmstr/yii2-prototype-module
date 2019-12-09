<?php

namespace dmstr\modules\prototype\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\traits\EditorEntry;
use Yii;

/**
 * This is the model class for table "app_less".
 */
class Less extends BaseModel
{
    use EditorEntry;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%less}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['audit-trail'] = AuditTrailBehavior::class;
        return $behaviors;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->cache->set('prototype.less.changed_at', time());
    }
}
