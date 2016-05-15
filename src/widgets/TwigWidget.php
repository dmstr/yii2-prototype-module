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
use yii\twig\ViewRenderer;

class TwigWidget extends Widget
{
    const SETTINGS_SECTION = 'app.html';
    const ACCESS_ROLE = 'Editor';

    public $key = null;
    public $enableFlash = false;
    public $enableBackendMenuItem = false;

    public function run()
    {
        $model = \dmstr\modules\prototype\models\Twig::findOne(['key' => $this->generateKey()]);

        $tmpFile = \Yii::getAlias('@runtime').'/'.uniqid('twig_');
        file_put_contents($tmpFile, ($model?$model->value:null));
        $render = new ViewRenderer;
        $html = $render->render('renderer.twig', $tmpFile, []);

        if (\Yii::$app->user->can(self::ACCESS_ROLE)) {

            $link = Html::a('prototype module', ($html) ? $this->generateEditRoute($model->id) : $this->generateCreateRoute());

            if ($this->enableFlash) {
                \Yii::$app->session->addFlash(
                    ($html) ? 'success' : 'info',
                    "Edit contents in {$link}, key: <code>{$this->generateKey()}</code>"
                );
            }

            if ($this->enableBackendMenuItem) {
                \Yii::$app->params['backend.menuItems'][] = [
                    'label' => 'Edit Twig',
                    'url' => ($html) ? $this->generateEditRoute($model->id) : $this->generateCreateRoute()
                ];
            }

            if (!$html) {
                $html = $this->renderEmpty();
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

        return Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Twig', ['/prototype/twig/create', 'Twig' => ['key' => $this->generateKey()]]);
    }

    private function generateEditLink($id)
    {
        return Html::a('prototype module', ['/prototype/html/update', 'id' => $id]);
    }

    private function generateCreateRoute()
    {
        return ['/prototype/twig/create', 'Twig' => ['key' => $this->generateKey()]];
    }

    private function generateEditRoute($id)
    {
        return ['/prototype/twig/update', 'id' => $id];
    }

    private function renderEmpty(){
        return '<div class="alert alert-info">'.$this->generateCreateLink().'</div>';
    }

}
