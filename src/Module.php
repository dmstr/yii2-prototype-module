<?php

namespace dmstr\modules\prototype;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'dmstr\modules\prototype\controllers';

    public function beforeAction($action)
    {
        parent::beforeAction($action);

        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => 'Prototype', 'url' => ['/'.$this->id]];

        return true;
    }
}
