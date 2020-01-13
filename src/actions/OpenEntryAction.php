<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\actions;


use dmstr\modules\prototype\traits\EditorEntry;
use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @package dmstr\modules\prototype\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 *  --- PROPERTIES --
 *
 * @property EditorEntry|ActiveRecord $modelClass;
 */
class OpenEntryAction extends Action
{
    public $modelClass;
    public $returnAction = 'editor';


    /**
     * @param $entryId
     * @param null $term
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function run($entryId, $term = null)
    {
        $modelClass = $this->modelClass;
        $allEntries = $modelClass::activeEntries();

        if (!isset($allEntries[$entryId]) && $modelClass::addEntry($entryId) === false) {
            throw new NotFoundHttpException(Yii::t('prototype', 'Entry not found'));
        }

        return $this->controller->redirect([$this->returnAction, '#' => 'tab-' . $entryId, 'term' => $term]);
    }
}
