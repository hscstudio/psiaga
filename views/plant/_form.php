<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use app\models\TypePlant;

/* @var $this yii\web\View */
/* @var $model app\models\Plant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plant-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $data = ArrayHelper::map(
      TypePlant::find()
        ->select([
          'id','name',
        ])
        ->asArray()
        ->all(), 'id', 'name');

    echo $form->field($model, 'type_plant_id')->widget(Select2::classname(), [
      'data' => $data,
      'options' => [
        'placeholder' => 'Pilih Type Plant ...',
      ],
      'pluginOptions' => [
        'allowClear' => true,
      ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::a('<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> Back', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<span class=\'glyphicon glyphicon-plus-sign\'></span> Create') : Yii::t('app', '<span class=\'glyphicon glyphicon-floppy-saved\'></span> Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
