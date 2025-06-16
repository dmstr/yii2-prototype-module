<?php

namespace dmstr\modules\prototype\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\traits\EditorEntry;
use Twig\Error\Error as TwigError;
use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "app_twig".
 */
class Twig extends BaseModel
{
    use EditorEntry;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%twig}}';
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
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [
            'value',
            'string'
        ];
        return $rules;
    }
}
