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
        $rules[] = [
            'value',
            'validateTwigTemplate'
        ];
        return $rules;
    }

    /**
     * @param $attribute
     *
     * @throws \yii\base\InvalidConfigException
     * @return void
     */
    public function validateTwigTemplate($attribute)
    {
        /** @var \yii\twig\ViewRenderer|null $twigRenderer */
        $twigRendererConfig = Yii::$app->view->renderers['twig'] ?? null;


        if (is_null($twigRendererConfig)) {
            throw new InvalidConfigException('TWIG renderer must be defined');
        }

        $twigRenderer = Yii::createObject($twigRendererConfig);
        $environment = $twigRenderer->twig;
        try {
            $template = $environment->createTemplate($this->$attribute);
            $template->render();
        } catch (TwigError $e) {
            $this->addError($attribute, Yii::t('prototype', 'Line {lineNumber}: {message}', [
                'lineNumber' => $e->getTemplateLine(),
                'message' => $e->getRawMessage()
            ]));
        }
    }
}
