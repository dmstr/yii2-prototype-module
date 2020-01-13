<?php

use dmstr\bootstrap\Tabs;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var dmstr\modules\prototype\models\Html $model
 */
$copyParams = $model->attributes;

$this->title = 'Html '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Htmls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('prototype', 'View');
?>
<div class="giiant-crud html-view">

    <!-- flash message -->
    <?php if (Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <?= Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Yii::t('prototype', 'Html') ?>
        <small>
            <?= $model->id ?>        </small>
    </h1>


    <div class="clearfix crud-navigation">
        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a(
                FA::icon(FA::_pencil) . ' '.Yii::t('prototype', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-info']
            ) ?>
            <?= Html::a(
                FA::icon(FA::_COPY) . ' '.Yii::t('prototype', 'Copy'),
                ['create', 'id' => $model->id, 'Html' => $copyParams],
                ['class' => 'btn btn-success']
            ) ?>
            <?= Html::a(
                FA::icon(FA::_PLUS) . ' '.Yii::t('prototype', 'New'),
                ['create'],
                ['class' => 'btn btn-success']
            ) ?>
            <?= Html::a(
                FA::icon(FA::_PLUS) . ' '.Yii::t('prototype', 'Editor'),
                ['editor','#' => 'tab-' . $model->id],
                ['class' => 'btn btn-success']
            ) ?>
        </div>
        <div class="pull-right">
            <?= Html::a(
                FA::icon(FA::_LIST) . ' '.Yii::t('prototype', 'List Htmls'),
                ['index'],
                ['class' => 'btn btn-default']
            ) ?>
        </div>

    </div>


    <?php $this->beginBlock('dmstr\modules\prototype\models\Html'); ?>


    <?= DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'id',
                'key',
                'value:html',
            ],
        ]
    ); ?>


    <hr/>

    <?= Html::a(
        FA::icon(FA::_TRASH) . ' '.Yii::t('prototype', 'Delete'),
        ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data-confirm' => ''.Yii::t('prototype', 'Are you sure to delete this item?').'',
            'data-method' => 'post',
        ]
    ); ?>
    <?php $this->endBlock(); ?>



    <?= Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># '.$model->id.'</b>',
                    'content' => $this->blocks['dmstr\modules\prototype\models\Html'],
                    'active' => true,
                ],
            ],
        ]
    );
    ?>
</div>
