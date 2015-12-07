<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HarvestPlant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="harvest-plant-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'plant_id')->textInput() ?>

    <?= $form->field($model, 'state_id')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'quarter')->textInput() ?>

    <?= $form->field($model, 'param1')->textInput() ?>

    <?= $form->field($model, 'param2')->textInput() ?>

    <?= $form->field($model, 'param3')->textInput() ?>

    <?= $form->field($model, 'param4')->textInput() ?>

    <?= $form->field($model, 'param5')->textInput() ?>

    <?= $form->field($model, 'note1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note5')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::a('<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> Back', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<span class=\'glyphicon glyphicon-plus-sign\'></span> Create') : Yii::t('app', '<span class=\'glyphicon glyphicon-floppy-saved\'></span> Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
