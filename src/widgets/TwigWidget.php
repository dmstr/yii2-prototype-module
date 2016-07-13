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

use rmrevin\yii\fontawesome\FA;
use yii\base\Event;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\twig\ViewRenderer;

class TwigWidget extends Widget
{
    const SETTINGS_SECTION = 'app.html';
    const ACCESS_ROLE = 'Editor';

    public $key = null;
    public $enableFlash = false;
    public $registerMenuItems = true;
    public $renderEmpty = true;

    private $_model;

    public function init()
    {
        $this->_model = \dmstr\modules\prototype\models\Twig::findOne(['key' => $this->generateKey()]);
        if ($this->registerMenuItems) {
            \Yii::$app->trigger('registerMenuItems', new Event(['sender' => $this]));
        }
    }

    public function run()
    {
        Url::remember('',$this->generateKey());


        // create temporary file
        $model = $this->_model;
        $twigCode = ($model ? $model->value : null);
        $tmpFile = \Yii::getAlias('@runtime').'/'.md5($twigCode);
        file_put_contents($tmpFile, $twigCode);
        $render = new ViewRenderer;

        try {
            $html = $render->render('renderer.twig', $tmpFile, []);
        } catch (\Twig_Error $e) {
            \Yii::$app->session->addFlash('error', $e->getMessage());
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

        return $html;
    }


    public function getMenuItems()
    {
        return [
            [
                'label' => ($this->_model?FA::icon(FA::_EDIT):FA::icon(FA::_PLUS_SQUARE)).' <b>'.$this->generateKey().'</b> <span class="label label-warning">Twig</span>',
                'url' => ($this->_model) ? $this->generateEditRoute($this->_model->id) : $this->generateCreateRoute()
            ]
        ];
    }

    private function generateKey()
    {
        if ($this->key) {
            return $this->key;
        } else {
            $key = \Yii::$app->request->getQueryParam('id');
        }
        return \Yii::$app->language.'/'.\Yii::$app->controller->route.($key ? '/'.$key : '');
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
