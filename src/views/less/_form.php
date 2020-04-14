<?php

use dmstr\bootstrap\Tabs;
use eluhr\aceeditor\widgets\AceEditor;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dmstr\modules\prototype\models\Less $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="less-form">

    <?php $form = ActiveForm::begin(
        [
            'id' => 'Less',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-1',
                    'wrapper' => 'col-sm-11',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]
    );
    ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'value')
                ->widget(AceEditor::class,
                    ['mode' => 'less', 'plugin_options' => ['tabSize' => 2], 'container_options' => ['style' => 'height: 50vh']]) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => 'Less',
                        'content' => $this->blocks['main'],
                        'active' => true,
                    ],
                ],
            ]
        );
        ?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?= Html::submitButton(
            FA::icon(FA::_SAVE) . ' '.
            ($model->isNewRecord ? Yii::t('prototype', 'Create') : Yii::t('prototype', 'Save')),
            [
                'id' => 'save-'.$model->formName(),
                'name' => 'subaction',
                'value' => 'save',
                'class' => 'btn btn-success',
            ]
        );
        ?>

        <?php if (!$model->isNewRecord): ?>
            <?= Html::submitButton(
                FA::icon(FA::_SAVE) . ' '.
                Yii::t('prototype', 'Apply'),
                [
                    'id' => 'apply-'.$model->formName(),
                    'name' => 'subaction',
                    'value' => 'apply',
                    'class' => 'btn btn-success',
                ]
            );
            ?>

            <?php if ($this->context->module->enableLessLinting): ?>
                <?= Html::submitButton(
                    FA::icon(FA::_SAVE) . ' '.
                    Yii::t('prototype', 'Lint'),
                    [
                        'id' => 'apply-'.$model->formName(),
                        'name' => 'subaction',
                        'value' => 'lint',
                        'class' => 'btn btn-success',
                    ]
                );
                ?>

                <?= Html::submitButton(
                    FA::icon(FA::_SAVE) . ' '.
                    Yii::t('prototype', 'Fix'),
                    [
                        'id' => 'apply-'.$model->formName(),
                        'name' => 'subaction',
                        'value' => 'fix',
                        'class' => 'btn btn-success',
                    ]
                );
                ?>
            <?php endif ?>

        <?php endif ?>

        <?php ActiveForm::end(); ?>

        <?php if (!empty($model->lintErrors) && $this->context->module->enableLessLinting): ?>
            <h2><?= Yii::t('prototype', 'Lint errors')?></h2>
            <pre><?= $model->lintErrors; ?></pre>
        <?php endif ?>

    </div>

</div>

