<?php

namespace dmstr\modules\prototype\controllers\api;

/**
 * This is the class for REST controller "LessController".
 */

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class LessController extends ActiveController
{
    public $modelClass = 'dmstr\modules\prototype\models\Less';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->can(
                                    $this->module->id.'_'.$this->id.'_'.$action->id,
                                    ['route' => true]
                                );
                            },
                        ],
                    ],
                ],
            ]
        );
    }
}
