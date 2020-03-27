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
use dmstr\modules\backend\interfaces\ContextMenuItemsInterface;
use dmstr\modules\prototype\models\Twig;
use rmrevin\yii\fontawesome\FA;
use Twig_Error;
use Yii;
use yii\base\Event;
use yii\base\Widget;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @property array $menuItems
 * @property Twig $_model
 * @property string $moduleId
 * @property array $params
 * @property string|null $key
 * @property bool $localized
 * @property string $queryParam
 */
class TwigWidget extends Widget implements ContextMenuItemsInterface
{
    const SETTINGS_SECTION = 'app.html';
    const ACCESS_ROLE = 'prototype_twig';
    const TEMP_ALIAS = '@runtime/TwigWidget';

    public $moduleId = 'prototype';
    public $queryParam = 'pageId';
    public $key;
    public $localized = true;
    public $enableFlash = false;
    public $registerMenuItems = true;
    public $renderEmpty = true;
    public $position;
    public $params = [];

    private $_model;

    public function init()
    {
        parent::init();
        FileHelper::createDirectory(Yii::getAlias(self::TEMP_ALIAS));
        $this->_model = Twig::findOne(['key' => $this->generateKey()]);
        if ($this->registerMenuItems && Yii::$app->user->can('prototype_twig', ['route' => true])) {
            Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
        }
    }

    /**
     * Generates a key based on the current route/queryParam
     *
     * @return null|string
     */
    private function generateKey()
    {
        $key = null;
        if ($this->key) {
            return $this->key;
        }

        if (isset(Yii::$app->controller->actionParams[$this->queryParam])) {
            $key = Yii::$app->controller->actionParams[$this->queryParam];
        }
        $language = $this->localized ? Yii::$app->language : ActiveRecordAccessTrait::$_all;

        return $language . '/' . Yii::$app->controller->route . ($key ? '/' . $key : '') .
            ($this->position ? '#' . $this->position : '');
    }

    /**
     * @return string
     */
    public function run()
    {
        // create temporary file
        $model = $this->_model;
        $twigCode = ($model ? $model->value : null);
        $tmpFilePath = Yii::getAlias(self::TEMP_ALIAS . '/');
        $tmpFileName = md5($twigCode) . '.twig';
        if (!file_exists($tmpFilePath . $tmpFileName)) {
            file_put_contents($tmpFilePath . $tmpFileName, $twigCode);
        }

        $html = '';
        try {
            $html = Yii::$app->getModule($this->moduleId)->view->renderFile($tmpFilePath . $tmpFileName,
                $this->params);
        } catch (Twig_Error $e) {
            $msg = "Twig #{$this->_model->id} {$e->getMessage()} Line {$e->getLine()}";
            Yii::$app->session->addFlash('error', $msg);
            Yii::error($msg, __METHOD__);
        }

        if (Yii::$app->user->can(self::ACCESS_ROLE)) {

            $link = Html::a('prototype module',
                $model ? $this->generateEditRoute($model->id) : $this->generateCreateRoute());

            if ($this->enableFlash) {
                Yii::$app->session->addFlash(
                    $html ? 'success' : 'info',
                    "Edit contents in {$link}, key: <code>{$this->generateKey()}</code>"
                );
            }

            if (!$model && $this->renderEmpty) {
                $html = $this->renderEmpty();
            }
        }

        Yii::trace('Twig widget rendered', __METHOD__);

        return $html;
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function generateEditRoute($id)
    {
        return ['/' . $this->moduleId . '/twig/update', 'id' => $id];
    }

    /**
     * @return array
     */
    private function generateCreateRoute()
    {
        return ['/' . $this->moduleId . '/twig/create', 'Twig' => ['key' => $this->generateKey()]];
    }

    /**
     * @return string
     */
    private function renderEmpty()
    {
        return '<div class="alert alert-info">' . $this->generateCreateLink() . '</div>';
    }

    /**
     * @return string
     */
    private function generateCreateLink()
    {

        return Html::a(FA::icon(FA::_PLUS_SQUARE) . ' ' . $this->generateKey() . ' Twig',
            ['/' . $this->moduleId . '/twig/create', 'Twig' => ['key' => $this->generateKey()]]);
    }

    /**
     * @return array
     */
    public function getMenuItems()
    {
        return [
            [
                'label' => ($this->_model ? FA::icon(FA::_EDIT) :
                        FA::icon(FA::_PLUS_SQUARE)) . ' <b>' . $this->generateKey() . '</b> <span class="label label-warning">Twig</span>',
                'url' => $this->_model ? $this->generateEditRoute($this->_model->id) : $this->generateCreateRoute(),
                'linkOptions' => [
                    'target' => Yii::$app->params['backend.iframe.name'] ?? '_self'
                ]
            ],
        ];
    }

}
