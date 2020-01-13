<?php

use dmstr\bootstrap\Tabs;
use eluhr\aceeditor\widgets\AceEditor;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dmstr\modules\prototype\models\Twig $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="twig-form">

    <?php $form = ActiveForm::begin([
            'id' => 'Twig',
            'layout' => 'horizontal',
            'enableClientValidation' => false,
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
            <?= $form->field($model, 'value')->widget(AceEditor::class,
                    ['mode' => 'twig', 'container_options' => ['style' => 'height: 50vh']]) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => Yii::t('prototype', 'Twig'),
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
                'class' => 'btn btn-success',
            ]
        );
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>

