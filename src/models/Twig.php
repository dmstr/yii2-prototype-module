<?php

namespace dmstr\modules\prototype\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\models\base\Twig as BaseTwig;

/**
 * This is the model class for table "app_twig".
 */
class Twig extends BaseTwig
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['audit-trail'] = AuditTrailBehavior::class;
        return $behaviors;
    }
}
