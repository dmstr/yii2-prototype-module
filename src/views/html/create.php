<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var dmstr\modules\prototype\models\Html $model
 */

$this->title = Yii::t('prototype', 'Create');
$this->params['breadcrumbs'][] = ['label' => 'Htmls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud html-create">

    <h1>
        <?= Yii::t('prototype', 'Html') ?>
        <small>
            <?= $model->id ?>        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a(
                Yii::t('prototype', 'Cancel'),
                'index',
                ['class' => 'btn btn-default']
            ) ?>
        </div>
    </div>
    <hr/>
    <?= $this->render(
        '_form',
        [
            'model' => $model,
        ]
    ); ?>

</div>
