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

    public function actionBackendTwig()
    {
        $this->layout = '@backend/views/layouts/main';
        return $this->render('twig');
    }

    public function actionBackendHtml()
    {
        $this->layout = '@backend/views/layouts/main';
        return $this->render('html');
    }

}
