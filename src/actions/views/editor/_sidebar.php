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
 * @var array $allEntries
 * @var \dmstr\modules\prototype\models\Search $searchModel
 */

use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<aside class="editor-sidebar">
    <?php
    $form = ActiveForm::begin([
        'action' => [
            'editor'
        ],
        'method' => 'get',
        'fieldConfig' => [
            'options' => [
                'tag' => false,
            ],
        ]
    ])
    ?>
    <div class="form-group">
        <div class="input-group">
            <?= $form->field($searchModel, 'term')->textInput([
                'placeholder' => Yii::t('prototype', 'Search...')
            ])->label(false) ?>
            <span class="input-group-btn">
                <?= Html::submitButton(FA::icon(FA::_SEARCH), ['class' => 'btn btn-default']) ?>
            </span>
        </div>
    </div>
    <?php
    ActiveForm::end();
    ?>
    <div class="list-group">
        <?php
        foreach ($allEntries as $entry):
            ?>
        <div class="list-group-item">
            <?php
            echo Html::a($entry['name'], ['open-entry', 'entryId' => $entry['id'], 'term' => $searchModel->term]);
            echo Html::a(FA::icon(FA::_TRASH), ['delete-entry', 'entryId' => $entry['id']], ['class' => 'btn-danger', 'data-confirm' => Yii::t('prototype', 'Are you sure that you want to delete this record?')]);
            ?>
        </div>
        <?php
        endforeach;
        ?>
    </div>
    <?=
    Html::a(Yii::t('prototype', 'New'), ['new'], [
        'class' => 'btn btn-success btn-block'
    ])
    ?>
</aside>
