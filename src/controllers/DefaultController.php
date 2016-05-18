<?php

namespace dmstr\modules\prototype\controllers;

use dmstr\web\traits\AccessBehaviorTrait;
use yii\web\Controller;

class DefaultController extends Controller
{
    use AccessBehaviorTrait;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest()
    {
        return $this->render('test');
    }
}
