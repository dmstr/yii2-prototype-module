<?php

use dmstr\modules\prototype\models\BaseModel;
use rmrevin\yii\fontawesome\FA;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var dmstr\modules\prototype\models\query\Twig $searchModel
 */

$this->title = Yii::t('prototype', 'Twigs');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="giiant-crud twig-index">


    <?php Pjax::begin([
        'id' => 'pjax-main',
        'enableReplaceState' => false,
        'linkSelector' => '#pjax-main ul.pagination a, th a',
        'clientOptions' => ['pjax:success' => 'function(){alert("yo")}'],
    ]) ?>

    <h1>
        <?= Yii::t('prototype', 'Twigs') ?>
        <small>
            List
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?= Html::a(FA::icon(FA::_PLUS) . ' '.Yii::t('prototype', 'New'),
                ['create'],
                ['class' => 'btn btn-success']) ?>
            <?= Html::a(
                FA::icon(FA::_EDIT) . ' ' . Yii::t('prototype', 'Editor'),
                ['editor'],
                ['class' => 'btn btn-success']
            ) ?>
        </div>
    </div>

    <hr/>

    <div class="table-responsive">
        <?= GridView::widget([
            'layout' => '{summary}{pager}{items}{pager}',
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'firstPageLabel' => Yii::t('prototype', 'First'),
                'lastPageLabel' => Yii::t('prototype', 'Last'),
            ],
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'headerRowOptions' => ['class' => 'x'],
            'columns' => [

                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string)$key];
                        $params[0] = Yii::$app->controller->id ? Yii::$app->controller->id.'/'.$action : $action;
                        return Url::toRoute($params);
                    },
                    'contentOptions' => ['nowrap' => 'nowrap'],
                ],
                'key',
                BaseModel::ATTR_UPDATED_AT,
                BaseModel::ATTR_CREATED_AT,
            ],
        ]); ?>
    </div>

</div>


<?php Pjax::end() ?>



