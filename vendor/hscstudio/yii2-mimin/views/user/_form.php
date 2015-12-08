<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\administrator\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
  		'pluginOptions' => [
  			'onText' => 'Aktif',
  			'offText' => 'Banned',
  		]
  	]) ?>

    <?php
    $data = \yii\helpers\ArrayHelper::map(
      \app\models\State::find()
        ->select([
          'id','name'
        ])
        ->asArray()
        ->all(), 'id', 'name');

    echo $form->field($model, 'state_id')->widget(Select2::classname(), [
      'data' => $data,
      'options' => [
        'placeholder' => 'Pilih State ...',
        //'disabled'=>(!$model->isNewRecord)?true:false,
      ],
      'pluginOptions' => [
        'allowClear' => true,
      ],
    ]); ?>

    <?php if (!$model->isNewRecord){ ?>
      <strong> Biarkan kosong jika tidak ingin mengubah password</strong>
      <div class="ui divider"></div>
      <?= $form->field($model, 'new_password') ?>
      <?= $form->field($model, 'repeat_password') ?>
      <?= $form->field($model, 'old_password') ?>
    <?php } ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
