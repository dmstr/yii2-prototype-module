<?php

use dmstr\modules\prototype\assets\DbAsset;
use dmstr\modules\prototype\widgets\TwigWidget;

?>

<h2>Test</h2>

<?php DbAsset::register($this) ?>



<?= TwigWidget::widget([
    'id' => 'test',
    'enableFlash' => true,
]) ?>
