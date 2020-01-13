<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\actions;


use dmstr\modules\prototype\assets\EditorAsset;
use dmstr\modules\prototype\models\Edit;
use dmstr\modules\prototype\models\Less;
use dmstr\modules\prototype\models\Search;
use dmstr\modules\prototype\traits\EditorEntry;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\Response;

/**
 * @package dmstr\modules\prototype\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 *  --- PROPERTIES --
 *
 * @property EditorEntry|ActiveRecord $modelClass
 * @property string $mode
 * @property string $openEntryUrl
 * @property bool $newEntry
 */
class EditorAction extends Action
{
    public $modelClass;
    public $newEntry = false;
    public $openEntryUrl = 'open-entry';
    public $mode;

    public function init()
    {
        parent::init();
        EditorAsset::register($this->controller->view);
    }

    /**
     * @return string|Response
     * @throws Exception
     */
    public function run()
    {
        Yii::$app->session['__crudReturnUrl'] = [$this->id];

        $modelClass = $this->modelClass;
        $activeEntries = $modelClass::activeEntries();

        $currentEntries = new Edit([
            'models' => $activeEntries,
            'modelClass' => $modelClass,
            'initNew' => $this->newEntry
        ]);

        if ($currentEntries->load(Yii::$app->request->post()) && $currentEntries->save()) {
            if ($this->newEntry) {
                /** @var Less $lastAdded */
                $lastAdded = $modelClass::find()->orderBy(['id' => SORT_DESC])->one();
                if ($lastAdded) {
                    return $this->controller->redirect([$this->openEntryUrl,'entryId' => $lastAdded->id]);
                }
            }
            return $this->controller->refresh();
        }

        $searchModel = new Search([
            'tableName' => $modelClass::tableName()
        ]);

        $searchModel->load(Yii::$app->request->get());

        $allEntries = $searchModel->allEntries();

        return $this->controller->render('@vendor/dmstr/yii2-prototype-module/src/actions/views/editor/editor',
            [
                'activeEntries' => $activeEntries,
                'allEntries' => $allEntries,
                'currentEntries' => $currentEntries,
                'searchModel' => $searchModel,
                'mode' => $this->mode
            ]);
    }
}
