<?php

namespace dmstr\modules\prototype;

use dmstr\web\traits\AccessBehaviorTrait;
use Yii;
use yii\base\Action;
use yii\web\View;

/**
 *
 * @property View $view
 */
class Module extends \yii\base\Module
{
    use AccessBehaviorTrait;

    private $_view;

    /**
     * @param Action $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {
        Yii::$app->controller->view->params['breadcrumbs'][] = ['label' => 'Prototype', 'url' => ['/'.$this->id]];

        return parent::beforeAction($action);
    }

    /**
     * @return View returns the view renderer for this module, used in ie. TwigWidget
     *
     * Returns a clone of the application view renderer by default
     */
    public function getView(){
        if ($this->_view === null) {
            $this->_view  = clone Yii::$app->getComponents(false)['view'];
        }
        return $this->_view;
    }

    /**
     * @param $view
     */
    public function setView($view){
        $this->_view = $view;
    }
}
