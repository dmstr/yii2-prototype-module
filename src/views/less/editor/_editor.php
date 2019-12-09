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
 * @var array $activeEntries
 * @var array $pendingEntries
 * @var Less $currentEntry
 */

use dmstr\modules\prototype\models\Less;
use eluhr\aceeditor\widgets\AceEditor;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$items = [
    [
        'label' => Yii::t('prototype', 'New'),
        'url' => ['editor']
    ]
];
$items = ArrayHelper::merge($items, array_map(function ($entry) {
    return [
        'label' => $entry['name'],
        'url' => ['open-entry', 'entryId' => $entry['id']]
    ];
}, $pendingEntries));
?>
<section class="editor-canvas">
    <?php
    $form = ActiveForm::begin();
    ?>
    <ul class="editor-top-navigation">
        <?php
        foreach ($activeEntries as $activeEntry):
            ?>
            <li class="btn <?= $activeEntry['id'] == $currentEntry->id ? 'active' : '' ?>">
                <?= Html::a($activeEntry['name'], ['', 'entryId' => $activeEntry['id']]) ?>
                <?= Html::a(FA::icon(FA::_TIMES), ['close-entry', 'entryId' => $activeEntry['id']]) ?>
            </li>
        <?php
        endforeach;
        if (!empty($items)):
            ?>
            <li>
                <?= ButtonDropdown::widget([
                    'label' => FA::icon(FA::_PLUS_SQUARE),
                    'dropdown' => [
                        'items' => $items
                    ],
                    'encodeLabel' => false
                ]) ?>
            </li>
        <?php
        endif;
        ?>
        <li class="pull-right">
            <button class="btn btn-block btn-primary"><?= Yii::t('prototype', 'Save changes') ?></button>
        </li>
    </ul>
    <div>
        <?= $form->field($currentEntry, 'key')->textInput([
            'placeholder' => Yii::t('prototype', 'Name')
        ])->label(false); ?>

        <?= $form->field($currentEntry, 'value')->widget(AceEditor::class, [
            'mode' => 'less',
            'container_options' => ['style' => 'height: 80vh']
        ])->label(false) ?>
    </div>
    <div>
        <?= Html::errorSummary($currentEntry) ?>
    </div>
    <?php
    ActiveForm::end();
    ?>
</section>
