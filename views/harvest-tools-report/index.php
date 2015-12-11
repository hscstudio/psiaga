<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\TypeTools;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HarvestToolsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Harvest Toolses Report');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="harvest-tools-index">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

       <?php echo $this->render('_search', ['model' => $harvestToolsReportSearch]); ?>

      <?php
      //$year =
      foreach ($typeToolses as $typeTools) {
          echo '<h3>'.$typeTools->name.'</h3>';
          $toolss = \app\models\Tools::find()
            ->where(['type_tools_id'=>$typeTools->id])
            ->all();
          $idx_tools = 0;
          foreach ($toolss as $tools) {
              echo '<h4>'.++$idx_tools.'. '.$tools->name.'</h4>';
              $where['tools_id']=$tools->id;
              if(!empty($years)) $where['year']=$years;
              if(!empty($quarters)) $where['quarter']=$quarters;
              if(!empty($harvestToolsReportSearch->stateIds)) $where['state_id']=$harvestToolsReportSearch->stateIds;
              $harvestToolses = \app\models\HarvestTools::find()
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
              $params = explode('|', $typeTools->params);
              $units = explode('|', $typeTools->units);
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
                      echo $harvestToolses['sparam'.$idx];
                      echo "</td>";
                      echo "<td>".$unit."</td>";
                      echo "<td>";
                      echo $harvestToolses['gcnote'.$idx];
                      echo "</td>";
                      echo "<td>";
                      $data = number_format(($harvestToolses['cstate']/$stateCount)*100,2);
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
