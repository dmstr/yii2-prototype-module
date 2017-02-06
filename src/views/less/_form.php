<?php

use dmstr\bootstrap\Tabs;
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
            <?= $form->field($model, 'value')->widget(\trntv\aceeditor\AceEditor::className(), ['mode' => 'less', 'containerOptions' => ['style' => 'height: 50vh']]) ?>
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
                ]
            ]
        );
        ?>
        <hr/>

        <?php echo $form->errorSummary($model); ?>

        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-save"></span> '.
            ($model->isNewRecord ? Yii::t('prototype', 'Create') : Yii::t('prototype', 'Save')),
            [
                'id' => 'save-'.$model->formName(),
                'class' => 'btn btn-success'
            ]
        );
        ?>

        <?php if (!$model->isNewRecord): ?>
        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-saved"></span> '.
            ($model->isNewRecord ? Yii::t('prototype', 'Apply') : Yii::t('prototype', 'Apply')),
            [
                'id' => 'apply-'.$model->formName(),
                'name' => 'subaction',
                'value' => 'apply',
                'class' => 'btn btn-success'
            ]
        );
        ?>
        <?php endif ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>

