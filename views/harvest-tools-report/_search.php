<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\TypeTools;
use app\models\State;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paramfund-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
    ]); ?>

    <div class="row">
        <div class="col-xs-5 col-sm-3 col-md-2">
          <?= $form->field($model, 'yearStart') ?>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-1">
          <div class="form-group text-center">
          <label class="control-label"><hr></label>
          s/d
          </div>
        </div>
        <div class="col-xs-5 col-sm-3 col-md-2">
          <?= $form->field($model, 'yearEnd') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-5 col-sm-3 col-md-2">
          <?= $form->field($model, 'quarterStart')->dropDownList([
            '1'=>'I','2'=>'II','3'=>'III','4'=>'IV'
            ]) ?>
        </div>
        <div class="col-xs-2 col-sm-2 col-md-1">
          <div class="form-group text-center">
          <label class="control-label"><hr></label>
          s/d
          </div>
        </div>
        <div class="col-xs-5 col-sm-3 col-md-2">
          <?= $form->field($model, 'quarterEnd')->dropDownList([
            '1'=>'I','2'=>'II','3'=>'III','4'=>'IV'
            ]) ?>
        </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-sm-8 col-md-5">
        <?php
        $data = ArrayHelper::map(
          State::find()
            ->select([
              'id','name',
            ])
            ->asArray()
            ->all(), 'id', 'name');

        echo $form->field($model, 'stateIds')->widget(Select2::classname(), [
          'data' => $data,
          'options' => [
            'placeholder' => 'Pilih Kecamatan ...',
          ],
          'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
          ],
        ])->label('Kecamatan'); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-md-5">
        <?php
        $data = ArrayHelper::map(
          TypeTools::find()
            ->select([
              'id','name',
            ])
            ->asArray()
            ->all(), 'id', 'name');

        echo $form->field($model, 'typeToolsIds')->widget(Select2::classname(), [
          'data' => $data,
          'options' => [
            'placeholder' => 'Pilih Jenis Alat ...',
          ],
          'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
          ],
        ])->label('Jenis Alat'); ?>
      </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Tampilkan', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
