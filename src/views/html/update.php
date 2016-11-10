<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dmstr\modules\prototype\models\Html $model
 */

$this->title = 'Html '.$model->id.', '.Yii::t('prototype', 'Edit');
$this->params['breadcrumbs'][] = ['label' => 'Htmls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('prototype', 'Edit');
?>
<div class="giiant-crud html-update">

    <h1>
        <?= Yii::t('prototype', 'Html') ?>
        <small>
            <?= $model->id ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a(
            '<span class="glyphicon glyphicon-eye-open"></span> '.Yii::t('prototype', 'View'),
            ['view', 'id' => $model->id],
            ['class' => 'btn btn-default']
        ) ?>
    </div>

    <?php echo $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ); ?>

</div>
