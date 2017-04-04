<?php
/**
 * @link http://www.diemeisterei.de/
 *
 * @copyright Copyright (c) 2015 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace dmstr\modules\prototype\widgets;

use dmstr\db\traits\ActiveRecordAccessTrait;
use rmrevin\yii\fontawesome\FA;
use yii\base\Event;
use yii\base\Widget;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class TwigWidget extends Widget
{
    const SETTINGS_SECTION = 'app.html';
    const ACCESS_ROLE = 'prototype_twig';
    const TEMP_ALIAS = '@runtime/TwigWidget';

    public $queryParam = 'pageId';
    public $key = null;
    public $localized = true;
    public $enableFlash = false;
    public $registerMenuItems = true;
    public $renderEmpty = true;
    public $position = null;

    public $params = [];

    private $_model;

    public function init()
    {
        parent::init();
        FileHelper::createDirectory(\Yii::getAlias(self::TEMP_ALIAS));
        $this->_model = \dmstr\modules\prototype\models\Twig::findOne(['key' => $this->generateKey()]);
        if ($this->registerMenuItems && \Yii::$app->user->can('prototype_twig', ['route' => true])) {
            \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
        }
    }

    public function run()
    {
        Url::remember('', $this->generateKey());

        // create temporary file
        $model = $this->_model;
        $twigCode = ($model ? $model->value : null);
        $tmpFile = \Yii::getAlias(self::TEMP_ALIAS.'/'.md5($twigCode)).'.twig';
        if (!file_exists($tmpFile)) {
            file_put_contents($tmpFile, $twigCode);
        }

        try {
            // TODO: workaround for broken context (runtime/TwigWidget) when having an error
            $view = clone (\Yii::$app->view);
            $html = $view->renderFile($tmpFile, $this->params);
            unset($view);
        } catch (\Twig_Error $e) {
            $msg = "{$e->getMessage()} #{$model->id} Line {$e->getLine()}";
            \Yii::$app->session->addFlash('error', $msg);
            \Yii::error($msg, __METHOD__);
            $html = '';
        }

        if (\Yii::$app->user->can(self::ACCESS_ROLE)) {

            $link = Html::a('prototype module',
                ($model) ? $this->generateEditRoute($model->id) : $this->generateCreateRoute());

            if ($this->enableFlash) {
                \Yii::$app->session->addFlash(
                    ($html) ? 'success' : 'info',
                    "Edit contents in {$link}, key: <code>{$this->generateKey()}</code>"
                );
            }

            if (!$model && $this->renderEmpty) {
                $html = $this->renderEmpty();
            }
        }

        \Yii::trace('Twig widget rendered', __METHOD__);

        return $html;
    }


    public function getMenuItems()
    {
        return [
            [
                'label' => ($this->_model ? FA::icon(FA::_EDIT) :
                        FA::icon(FA::_PLUS_SQUARE)).' <b>'.$this->generateKey().'</b> <span class="label label-warning">Twig</span>',
                'url' => ($this->_model) ? $this->generateEditRoute($this->_model->id) : $this->generateCreateRoute(),
            ],
        ];
    }

    private function generateKey()
    {
        if ($this->key) {
            return $this->key;
        } else {
            $key = \Yii::$app->request->getQueryParam($this->queryParam);
        }
        $language = ($this->localized) ? \Yii::$app->language : ActiveRecordAccessTrait::$_all;
        return $language.'/'.\Yii::$app->controller->route.($key ? '/'.$key : '').($this->position ?
            '#'.$this->position : '');
    }

    private function generateCreateLink()
    {

        return Html::a('<i class="glyphicon glyphicon-plus-sign"></i> '.$this->generateKey().' Twig',
            ['/prototype/twig/create', 'Twig' => ['key' => $this->generateKey()]]);
    }

    private function generateCreateRoute()
    {
        return ['/prototype/twig/create', 'Twig' => ['key' => $this->generateKey()]];
    }

    private function generateEditRoute($id)
    {
        return ['/prototype/twig/update', 'id' => $id];
    }

    private function renderEmpty()
    {
        return '<div class="alert alert-info">'.$this->generateCreateLink().'</div>';
    }

}
