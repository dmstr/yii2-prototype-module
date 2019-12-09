<?php

namespace dmstr\modules\prototype\controllers;

use dmstr\modules\prototype\models\Edit;
use dmstr\modules\prototype\models\Less;
use dmstr\modules\prototype\models\Search;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the class for controller "LessController".
 */
class LessController extends \dmstr\modules\prototype\controllers\base\LessController
{
    /**
     * @param null $entryId
     *
     * @return string
     */
    public function actionEditor()
    {

        $activeEntries = Less::activeEntries();

        $currentEntries = new Edit([
            'models' => $activeEntries,
            'modelClass' => Less::class
        ]);

        if ($currentEntries->load(Yii::$app->request->post()) && $currentEntries->save()) {
            return $this->refresh();
        }

        $searchModel = new Search([
            'tableName' => Less::tableName()
        ]);

        $searchModel->load(Yii::$app->request->get());

        $allEntries = $searchModel->allEntries();

        return $this->render('editor',
            [
                'activeEntries' => Less::activeEntries(),
                'allEntries' => $allEntries,
                'pendingEntries' => array_filter($allEntries, function ($entry) {
                    return $entry['opened'] === false;
                }),
                'currentEntries' => $currentEntries,
                'searchModel' => $searchModel
            ]);
    }

    /**
     * @param $entryId
     *
     * @param null $term
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionOpenEntry($entryId, $term = null)
    {
        $allEntries = Less::activeEntries();

        if (!isset($allEntries[$entryId]) && Less::addEntry($entryId) === false) {
            throw new NotFoundHttpException(Yii::t('prototype', 'Entry not found'));
        }

        return $this->redirect(['editor', '#' => 'tab-' . $entryId, 'term' => $term]);
    }

    /**
     * @param $entryId
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionCloseEntry($entryId)
    {
        if (Less::removeEntry($entryId) === false) {
            throw new NotFoundHttpException(Yii::t('prototype', 'Entry not found'));
        }

        return $this->redirect(['editor']);
    }
}
