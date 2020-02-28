<?php

namespace dmstr\modules\prototype\controllers;

use dmstr\modules\prototype\actions\CloseEntryAction;
use dmstr\modules\prototype\actions\EditorAction;
use dmstr\modules\prototype\actions\NewAction;
use dmstr\modules\prototype\actions\OpenEntryAction;
use dmstr\modules\prototype\models\Twig;
use dmstr\modules\prototype\traits\EditorControllerActions;

/**
 * This is the class for controller "TwigController".
 */
class TwigController extends base\TwigController
{
	public $modelClass = Twig::class;
	public $mode = 'twig';

	use EditorControllerActions;
}
