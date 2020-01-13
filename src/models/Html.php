<?php

namespace dmstr\modules\prototype\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\traits\EditorEntry;

/**
 * This is the model class for table "app_html".
 */
class Html extends BaseModel
{
    use EditorEntry;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%html}}';
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
}
