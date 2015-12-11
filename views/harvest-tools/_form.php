<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Tools;
use app\models\State;

/* @var $this yii\web\View */
/* @var $model app\models\HarvestTools */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="harvest-tools-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
      <div class="col-md-4">
      <?php
      $data = ArrayHelper::map(
        Tools::find()
          ->select([
            'id','name',
          ])
          ->asArray()
          ->all(), 'id', 'name');

      echo $form->field($model, 'tools_id')->widget(Select2::classname(), [
        'data' => $data,
        'options' => [
          'placeholder' => 'Pilih Tools ...',
          'onchange'=>'
            location.href = "'.Url::to(['create']).'"+"?tools_id="+$(this).val()
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

    <?php
    $params = explode('|', $typeTools->params);
    $units = explode('|', $typeTools->units);
    if(count($params)>0 and count($units)>0 and count($params)==count($units)){
        $combines = array_combine($params, $units);
        ?>
        <table class="table">
          <tr>
            <th style="width:20px;">No</th>
            <th style="width:20%;">Parameter</th>
            <th style="width:15%;">Jumlah</th>
            <th style="width:15%;">Satuan</th>
            <th>Keterangan</th>
          </tr>
          <tbody>
          <?php
          $idx = 1;
          foreach ($combines as $param => $unit) {
            echo "<tr>";
            echo "<td>".$idx."</td>";
            echo "<td>".$param."</td>";
            echo "<td>";
            echo $form->field($model, 'param'.$idx)->textInput()->label(false);
            echo "</td>";
            echo "<td>".$unit."</td>";
            echo "<td>";
            echo $form->field($model, 'note'.$idx)->textInput()->label(false);
            echo "</td>";
            echo "</tr>";
            $idx++;
          }
          ?>
        </tbody>
        </table>
        <?php
    }
    ?>

    <div class="form-group">
        <?= Html::a('<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> Back', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<span class=\'glyphicon glyphicon-plus-sign\'></span> Create') : Yii::t('app', '<span class=\'glyphicon glyphicon-floppy-saved\'></span> Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
