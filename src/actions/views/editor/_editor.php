<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * --- VARIABLES ---
 *
 * @var View $this
 * @var array $activeEntries
 * @var string $mode
 * @var \dmstr\modules\prototype\models\Search $searchModel
 * @var Less[] $currentEntries
 */

use dmstr\modules\prototype\models\Edit;
use dmstr\modules\prototype\models\Less;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$models = $currentEntries->models();
?>
<section class="editor-canvas">
    <?php
    $form = ActiveForm::begin();
    ?>
    <ul class="editor-top-navigation">
        <?php
        foreach ($models as $activeEntry):
            ?>
            <li class="btn">
                <a href="javascript:void(0)" data-target="#<?= 'tab-' . $activeEntry['id'] ?>" data-toggle="tab"
                   role="tab"><?= $activeEntry['name'] ?></a>
                <?php
                if ($activeEntry['id'] !== Edit::NEW_MODEL_ID) {
                    echo Html::a(FA::icon(FA::_TIMES), ['close-entry', 'entryId' => $activeEntry['id']]);
                }
                ?>
            </li>
        <?php
        endforeach;
        ?>
        <li class="pull-right">
            <button class="btn btn-block btn-primary"><?= Yii::t('prototype', 'Save changes') ?></button>
        </li>
    </ul>
    <div class="tab-content">
        <?php
        foreach ($models as $model) {
            echo $this->render('_work_area',
                ['currentEntries' => $currentEntries, 'form' => $form, 'id' => $model['id'],'mode' => $mode]);
        }
        if (empty($models)) {
            echo Html::a(Yii::t('prototype', 'Open new'), ['new'], ['class' => 'big-center-link']);
        }
        ?>
    </div>
    <?php
    ActiveForm::end();
    ?>
</section>
<?php
if (!empty($searchModel->term)) {
    foreach ($models as $model) {
        $this->registerJs(<<<JS
setTimeout(function() {
  ace.edit('editor{$model['id']}').findAll("{$searchModel->term}");
}, 0)
JS
        );
    }
}
?>
