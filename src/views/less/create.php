<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dmstr\modules\prototype\models\Less $model
 */

$this->title = Yii::t('prototype', 'Create');
$this->params['breadcrumbs'][] = ['label' => 'Lesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud less-create">

    <h1>
        <?= Yii::t('prototype', 'Less') ?>
        <small>
            <?= $model->id ?>        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a(
                Yii::t('prototype', 'Cancel'),
                \yii\helpers\Url::previous(),
                ['class' => 'btn btn-default']
            ) ?>
        </div>
    </div>

    <?= $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ); ?>

</div>
