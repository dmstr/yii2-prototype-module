<?php

?>

<h2>Test</h2>

<?php \dmstr\modules\prototype\assets\DbAsset::register($this) ?>



<?= \dmstr\modules\prototype\widgets\TwigWidget::widget([
    'id' => 'test',
    'enableFlash' => true
]) ?>
