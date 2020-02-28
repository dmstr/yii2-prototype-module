<?php


namespace dmstr\modules\prototype\traits;


use dmstr\modules\prototype\actions\CloseEntryAction;
use dmstr\modules\prototype\actions\EditorAction;
use dmstr\modules\prototype\actions\NewAction;
use dmstr\modules\prototype\actions\OpenEntryAction;

trait EditorControllerActions
{
	/**
	 * @return array
	 */
	public function actions()
	{
		$actions = parent::actions();
		$actions['new'] = [
			'class' => NewAction::class,
			'mode' => $this->mode,
			'modelClass' => $this->modelClass
		];
		$actions['editor'] = [
			'class' => EditorAction::class,
			'mode' => $this->mode,
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