<?php

namespace dmstr\modules\prototype\controllers;

use dmstr\modules\prototype\actions\CloseEntryAction;
use dmstr\modules\prototype\actions\EditorAction;
use dmstr\modules\prototype\actions\NewAction;
use dmstr\modules\prototype\actions\OpenEntryAction;
use dmstr\modules\prototype\models\Less;
use dmstr\modules\prototype\traits\EditorControllerActions;

/**
 * This is the class for controller "LessController".
 */
class LessController extends base\LessController
{
	public $modelClass = Less::class;
	public $mode = 'less';

    use EditorControllerActions;
}
