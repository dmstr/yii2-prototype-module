<?php

namespace dmstr\modules\prototype\models;

use dmstr\modules\prototype\models\base\Twig as BaseTwig;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "app_twig".
 */
class Twig extends BaseTwig
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'bedezign\yii2\audit\AuditTrailBehavior'
            ]
        );
    }
}
