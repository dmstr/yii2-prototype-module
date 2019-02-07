<?php

namespace dmstr\modules\prototype\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\models\base\Html as BaseHtml;

/**
 * This is the model class for table "app_html".
 */
class Html extends BaseHtml
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['audit-trail'] = AuditTrailBehavior::class;
        return $behaviors;
    }
}
