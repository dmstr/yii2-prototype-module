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

use yii\base\Widget;
use yii\helpers\Html;

class HtmlWidget extends Widget
{
    const SETTINGS_SECTION = 'app.html';
    const ACCESS_ROLE = 'Editor';

    public $key = null;
    public $enableFlash = false;
    public $enableBackendMenuItem = false;
    public $renderEmpty = true;

    public function run()
    {
        $model = \dmstr\modules\prototype\models\Html::findOne(['key' => $this->generateKey()]);
        $html = '';

        if (\Yii::$app->user->can(self::ACCESS_ROLE)) {
            $link = ($model) ? $this->generateEditLink($model->id) : $this->generateCreateLink();
            if ($this->enableFlash) {
                \Yii::$app->session->addFlash(
                    ($model) ? 'success' : 'info',
                    "Edit contents in {$link}, key: <code>{$this->generateKey()}</code>"
                );
            }

            if ($this->enableBackendMenuItem) {
                \Yii::$app->params['backend.menuItems'][] = [
                    'label' => 'Edit HTML',
                    'url' => ($model) ? $this->generateEditRoute($model->id) : $this->generateCreateRoute()
                ];
            }

            if (!$model) {
                if ($this->renderEmpty) {
                    $html = $this->renderEmpty();
                }
            } else {
                $html = $model->value;
            }
        }

        return $html;
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

        return Html::a('<i class="glyphicon glyphicon-plus-sign"></i> HTML',
            ['/prototype/html/create', 'Html' => ['key' => $this->generateKey()]]);
    }

    private function generateEditLink($id)
    {
        return Html::a('prototype module', ['/prototype/html/update', 'id' => $id]);
    }

    private function generateCreateRoute()
    {
        return ['/prototype/html/create', 'Twig' => ['key' => $this->generateKey()]];
    }

    private function generateEditRoute($id)
    {
        return ['/prototype/html/update', 'id' => $id];
    }

    private function renderEmpty()
    {
        return '<div class="alert alert-info">'.$this->generateCreateLink().'</div>';
    }
}
