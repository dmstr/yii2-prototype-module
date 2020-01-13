<?php

namespace dmstr\modules\prototype\controllers;

use dmstr\modules\prototype\actions\CloseEntryAction;
use dmstr\modules\prototype\actions\EditorAction;
use dmstr\modules\prototype\actions\NewAction;
use dmstr\modules\prototype\actions\OpenEntryAction;
use dmstr\modules\prototype\models\Html;

/**
 * This is the class for controller "HtmlController".
 */
class HtmlController extends base\HtmlController
{
    protected $modelClass = Html::class;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['new'] = [
            'class' => NewAction::class,
            'mode' => 'html',
            'modelClass' => $this->modelClass
        ];
        $actions['editor'] = [
            'class' => EditorAction::class,
            'mode' => 'html',
            'modelClass' => $this->modelClass
        ];
        $actions['open-entry'] = [
            'class' => OpenEntryAction::class,
            'modelClass' => $this->modelClass
        ];
        $actions['close-entry'] = [
            'class' => CloseEntryAction::class,
            'modelClass' => $this->modelClass
        ];
        $actions['delete-entry'] = [
            'class' => CloseEntryAction::class,
            'modelClass' => $this->modelClass,
            'delete' => true
        ];
        return $actions;
    }
}
