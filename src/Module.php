<?php

namespace dmstr\modules\prototype;

use dmstr\web\traits\AccessBehaviorTrait;
use yii\web\View;

class Module extends \yii\base\Module
{
    use AccessBehaviorTrait;

    public $controllerNamespace = 'dmstr\modules\prototype\controllers';

    private $_view = null;

    public function beforeAction($action)
    {
        \Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => 'Prototype', 'url' => ['/'.$this->id]];

        return parent::beforeAction($action);
    }

    /**
     * @return View returns the view renderer for this module, used in ie. TwigWidget
     *
     * Returns a clone of the application view renderer by default
     */
    public function getView(){
        if ($this->_view === null) {
            $this->_view  = clone(\Yii::$app->getComponents(false)['view']);
        }
        return $this->_view;
    }

    public function setView($view){
        $this->_view = $view;
    }
}
