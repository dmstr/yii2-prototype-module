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
 * @var string $mode
 * @var array $activeEntries
 * @var array $pendingEntries
 * @var \dmstr\modules\prototype\models\Search $searchModel
 * @var Edit $currentEntries
 */

use dmstr\modules\prototype\models\Edit;
use eluhr\aceeditor\widgets\AceEditor;

?>
<div role="tabpanel" class="tab-pane" id="tab-<?= $id ?>">
    <?php
    echo $form->field($currentEntries, 'keys[' . $id . ']')->textInput([
        'placeholder' => Yii::t('prototype', 'Name')
    ])->label(false);

    echo $form->field($currentEntries, 'values[' . $id . ']')->widget(AceEditor::class, [
        'id' => 'editor' . $id,
        'mode' => $mode,
        'container_options' => ['style' => 'height: 80vh']
    ])->label(false);
    ?>
</div>

