<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\TypePlant;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HarvestPlantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Harvest Plants Report');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="harvest-plant-index">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

       <?php echo $this->render('_search', ['model' => $harvestPlantReportSearch]); ?>

      <?php
      //$year =
      foreach ($typePlants as $typePlant) {
          echo '<h3>'.$typePlant->name.'</h3>';
          $plants = \app\models\Plant::find()
            ->where(['type_plant_id'=>$typePlant->id])
            ->all();
          $idx_plant = 0;
          foreach ($plants as $plant) {
              echo '<h4>'.++$idx_plant.'. '.$plant->name.'</h4>';
              $where['plant_id']=$plant->id;
              if(!empty($years)) $where['year']=$years;
              if(!empty($quarters)) $where['quarter']=$quarters;
              if(!empty($harvestPlantReportSearch->stateIds)) $where['state_id']=$harvestPlantReportSearch->stateIds;
              $harvestPlants = \app\models\HarvestPlant::find()
                  ->select("
                    sum(param1) as sparam1,
                    sum(param2) as sparam2,
                    sum(param3) as sparam3,
                    sum(param4) as sparam4,
                    sum(param5) as sparam5,
                    group_concat(note1) as gcnote1,
                    group_concat(note2) as gcnote2,
                    group_concat(note3) as gcnote3,
                    group_concat(note4) as gcnote4,
                    group_concat(note5) as gcnote5,
                    count(state_id) as cstate,
                  ")
                  ->where($where)
                  ->asArray()
                  ->one();
              $params = explode('|', $typePlant->params);
              $units = explode('|', $typePlant->units);
              if(count($params)>0 and count($units)>0 and count($params)==count($units)){
                  $combines = array_combine($params, $units);
                  ?>
                  <table class="table table-condensed table-striped">
                    <tr>
                      <th style="width:20px;" class="text-center">No</th>
                      <th style="width:200px;" class="text-center">Parameter</th>
                      <th style="width:150px;" class="text-center">Nilai</th>
                      <th style="width:50px;" class="text-center">Satuan</th>
                      <th class="text-center">Keterangan</th>
                      <th style="width:100px;" class="text-center">Data Masuk</th>
                    </tr>
                    <tbody>
                    <?php
                    $idx = 1;
                    foreach ($combines as $param => $unit) {
                      echo "<tr>";
                      echo "<td>".$idx."</td>";
                      echo "<td>".$param."</td>";
                      echo "<td>";
                      echo $harvestPlants['sparam'.$idx];
                      echo "</td>";
                      echo "<td>".$unit."</td>";
                      echo "<td>";
                      echo $harvestPlants['gcnote'.$idx];
                      echo "</td>";
                      echo "<td>";
                      $data = number_format(($harvestPlants['cstate']/$stateCount)*100,2);
                      echo $data.'%';
                      echo "</td>";
                      echo "</tr>";
                      $idx++;
                    }
                    ?>
                  </tbody>
                  </table>
              <?php
              }
              //echo "<hr>";
          }
          echo "<hr>";
      }
      ?>
      <?= Html::a('<span class=\'glyphicon glyphicon-export\'></span>  '.Yii::t('app', 'Cetak Excel'), ['export-excel'], ['class' => 'btn btn-success']) ?>
    </div>
  </div>
</div>
