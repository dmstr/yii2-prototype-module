<?php

namespace dmstr\modules\prototype\controllers;

use dmstr\web\traits\AccessBehaviorTrait;
use yii\web\Controller;

class RenderController extends Controller
{
    use AccessBehaviorTrait;

    public function actionTwig()
    {
        $this->layout = '//main';
        return $this->render('twig');
    }

    public function actionHtml()
    {
        $this->layout = '//main';
        return $this->render('html');
    }

}
