<?php

namespace dmstr\modules\prototype\controllers;

use dmstr\modules\prototype\actions\CloseEntryAction;
use dmstr\modules\prototype\actions\EditorAction;
use dmstr\modules\prototype\actions\NewAction;
use dmstr\modules\prototype\actions\OpenEntryAction;
use dmstr\modules\prototype\models\Html;
use dmstr\modules\prototype\traits\EditorControllerActions;

/**
 * This is the class for controller "HtmlController".
 */
class HtmlController extends base\HtmlController
{
	public $modelClass = Html::class;
    public $mode = 'html';

	use EditorControllerActions;
}
