<?php

namespace dmstr\modules\prototype\models;

use dmstr\modules\prototype\models\base\Html as BaseHtml;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "app_html".
 */
class Html extends BaseHtml
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
}
