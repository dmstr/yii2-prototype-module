<?php

use dmstr\bootstrap\Tabs;
use dmstr\modules\prototype\models\BaseModel;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var dmstr\modules\prototype\models\Twig $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('prototype', 'Twig') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('prototype', 'Twigs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('prototype', 'View');
?>
<div class="giiant-crud twig-view">

    <!-- flash message -->
    <?php if (Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= Yii::t('prototype', 'Twig') ?>
        <small>
            <?= $model->id ?>        </small>
    </h1>


    <div class="clearfix crud-navigation">
        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a(FA::icon(FA::_PENCIL) . ' ' . Yii::t('prototype', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-info']) ?>
            <?= Html::a(FA::icon(FA::_COPY) . ' ' . Yii::t('prototype', 'Copy'),
                ['create', 'id' => $model->id, 'Twig' => $copyParams],
                ['class' => 'btn btn-success']) ?>
            <?= Html::a(FA::icon(FA::_PLUS) . ' ' . Yii::t('prototype', 'New'),
                ['create'],
                ['class' => 'btn btn-success']) ?>
            <?= Html::a(
                FA::icon(FA::_EDIT) . ' ' . Yii::t('prototype', 'Editor'),
                ['editor','#' => 'tab-' . $model->id],
                ['class' => 'btn btn-success']
            ) ?>
        </div>
        <div class="pull-right">
            <?= Html::a(FA::icon(FA::_LIST) . ' ' . Yii::t('prototype', 'Full list'),
                ['index'],
                ['class' => 'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('dmstr\modules\prototype\models\Twig'); ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'key',
            [
                'attribute' => 'value',
                'format' => 'raw',
                'value' => "<pre>" . htmlspecialchars($model->value) . "</pre>",
            ],
            BaseModel::ATTR_UPDATED_AT,
            BaseModel::ATTR_CREATED_AT,
        ],
    ]); ?>


    <hr/>

    <?= Html::a(FA::icon(FA::_TRASH) . ' ' . Yii::t('prototype', 'Delete'),
        ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data-confirm' => '' . Yii::t('prototype', 'Are you sure to delete this item?') . '',
            'data-method' => 'post',
        ]); ?>
    <?php $this->endBlock(); ?>



    <?= Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># ' . $model->id . '</b>',
                    'content' => $this->blocks['dmstr\modules\prototype\models\Twig'],
                    'active' => true,
                ],
            ],
        ]
    );
    ?>
</div>
