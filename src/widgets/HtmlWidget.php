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

use dmstr\modules\backend\interfaces\ContextMenuItemsInterface;
use rmrevin\yii\fontawesome\FA;
use yii\base\Event;
use yii\base\Widget;
use yii\helpers\Html;
use dmstr\modules\prototype\models\Html as HtmlModel;

/**
 *
 * @property array $menuItems
 * @property string|null $key
 * @property string $moduleId
 * @property bool $enableFlash
 * @property bool $registerMenuItems
 * @property bool $renderEmpty
 * @property Html $_model
 */
class HtmlWidget extends Widget implements ContextMenuItemsInterface
{
    const SETTINGS_SECTION = 'app.html';
    const ACCESS_ROLE = 'Editor';

    public $key;
    public $enableFlash = false;
    public $moduleId = 'prototype';
    public $registerMenuItems = true;
    public $renderEmpty = true;

    private $_model;

    public function init()
    {
        parent::init();
        $this->_model = HtmlModel::findOne(['key' => $this->generateKey()]);
        if ($this->registerMenuItems) {
            \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->_model = $model = HtmlModel::findOne(['key' => $this->generateKey()]);
        $html = '';

        if (\Yii::$app->user->can(self::ACCESS_ROLE)) {
            $link = $model ? $this->generateEditLink($model->id) : $this->generateCreateLink();
            if ($this->enableFlash) {
                \Yii::$app->session->addFlash(
                    $model ? 'success' : 'info',
                    "Edit contents in {$link}, key: <code>{$this->generateKey()}</code>"
                );
            }

            if (!$model && $this->renderEmpty) {
                $html = $this->renderEmpty();
            }
        }

        if ($model) {
            $html = $model->value;
        }

        return $html;
    }

    /**
     * @return array
     */
    public function getMenuItems()
    {
        return [
            [
                'label' => ($this->_model ? FA::icon(FA::_EDIT) :
                        FA::icon(FA::_PLUS_SQUARE)).' <b>'.$this->generateKey().'</b> <span class="label label-danger">HTML</span>',
                'url' => $this->_model ? $this->generateEditRoute($this->_model->id) : $this->generateCreateRoute(),
            ],
        ];
    }

    /**
     * @return null|string
     */
    private function generateKey()
    {
        if ($this->key) {
            return $this->key;
        }

        $key = \Yii::$app->request->getQueryParam('id');
        return \Yii::$app->language.'/'.\Yii::$app->controller->route.($key ? '/'.$key : '');
    }

    /**
     * @return string
     */
    private function generateCreateLink()
    {

        return Html::a(FA::icon(FA::_PLUS_SQUARE) . ' HTML',
            ['/' . $this->moduleId . '/html/create', 'Html' => ['key' => $this->generateKey()]]);
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function generateEditLink($id)
    {
        return Html::a($this->moduleId . ' module', ['/' . $this->moduleId . '/html/update', 'id' => $id]);
    }

    /**
     * @return array
     */
    private function generateCreateRoute()
    {
        return ['/' . $this->moduleId . '/html/create', 'Html' => ['key' => $this->generateKey()]];
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function generateEditRoute($id)
    {
        return ['/' . $this->moduleId . '/html/update', 'id' => $id];
    }

    /**
     * @return string
     */
    private function renderEmpty()
    {
        return '<div class="alert alert-info">'.$this->generateCreateLink().'</div>';
    }
}
