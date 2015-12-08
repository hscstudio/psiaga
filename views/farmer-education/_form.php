<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Education;
use app\models\State;

/* @var $this yii\web\View */
/* @var $model app\models\FarmerEducation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farmer-education-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
      <div class="col-md-4">
      <?php
      $data = ArrayHelper::map(
        Education::find()
          ->select([
            'id','name',
          ])
          ->asArray()
          ->all(), 'id', 'name');

      echo $form->field($model, 'education_id')->widget(Select2::classname(), [
        'data' => $data,
        'options' => [
          'placeholder' => 'Pilih Education ...',
          'onchange'=>'
            location.href = "'.Url::to(['create']).'"+"?education_id="+$(this).val()
          ',
          'disabled'=>(!$model->isNewRecord)?true:false,
        ],
        'pluginOptions' => [
          'allowClear' => true,
        ],
      ]); ?>


      <?php
      $data = ArrayHelper::map(
        State::find()
          ->select([
            'id','name',
          ])
          ->asArray()
          ->all(), 'id', 'name');

      echo $form->field($model, 'state_id')->widget(Select2::classname(), [
        'data' => $data,
        'options' => [
          'placeholder' => 'Pilih State ...',
          'disabled'=>(Yii::$app->user->identity->state_id>0)?true:false,
        ],
        'pluginOptions' => [
          'allowClear' => true,
        ],
      ]);
      ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-2">
      <?= $form->field($model, 'year')->textInput() ?>
      </div>
      <div class="col-md-2">
      <?= $form->field($model, 'quarter')->dropDownList([
      '1'=>'I','2'=>'II','3'=>'III','4'=>'IV'
      ]) ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-2">
      <?= $form->field($model, 'param')->textInput()->label('Jumlah') ?>
      </div>
      <div class="col-md-2">
        <br>
        <?= $education->unit ?>
      </div>
    </div>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::a('<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> Back', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<span class=\'glyphicon glyphicon-plus-sign\'></span> Create') : Yii::t('app', '<span class=\'glyphicon glyphicon-floppy-saved\'></span> Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
