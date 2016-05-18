<?php

use dmstr\bootstrap\Tabs;
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
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    );
    ?>

    <div class="">
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'value')->widget(\trntv\aceeditor\AceEditor::className()) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => $model->getAliasModel(),
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
            '<span class="glyphicon glyphicon-check"></span> '.
            ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')),
            [
                'id' => 'save-'.$model->formName(),
                'class' => 'btn btn-success'
            ]
        );
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>

