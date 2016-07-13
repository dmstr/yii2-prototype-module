<?php

use dmstr\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var dmstr\modules\prototype\models\Twig $model
 */
$copyParams = $model->attributes;

$this->title = $model->getAliasModel().$model->id;
$this->params['breadcrumbs'][] = ['label' => $model->getAliasModel(true), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<div class="giiant-crud twig-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?= $model->getAliasModel() ?>
        <small>
            <?= $model->id ?>        </small>
    </h1>


    <div class="clearfix crud-navigation">
        <!-- menu buttons -->
        <div class='pull-left'>
            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Edit'),
                ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-copy"></span> '.Yii::t('app', 'Copy'),
                ['create', 'id' => $model->id, 'Twig' => $copyParams], ['class' => 'btn btn-success']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> '.Yii::t('app', 'New'), ['create'],
                ['class' => 'btn btn-success']) ?>
        </div>
        <div class="pull-right">
            <?= Html::a('<span class="glyphicon glyphicon-list"></span> '.Yii::t('app', 'Full list'), ['index'],
                ['class' => 'btn btn-default']) ?>
        </div>

    </div>

    <hr />

    <?php $this->beginBlock('dmstr\modules\prototype\models\Twig'); ?>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'key',
            [
                'attribute' => 'value',
                'format' => 'raw',
                'value' => "<pre>{$model->value}</pre>",
            ]
        ],
    ]); ?>


    <hr/>

    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'),
        ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data-confirm' => ''.Yii::t('app', 'Are you sure to delete this item?').'',
            'data-method' => 'post',
        ]); ?>
    <?php $this->endBlock(); ?>



    <?= Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># '.$model->id.'</b>',
                    'content' => $this->blocks['dmstr\modules\prototype\models\Twig'],
                    'active' => true,
                ],
            ]
        ]
    );
    ?>
</div>
