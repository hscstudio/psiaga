<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HarvestPlantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Farmer Education Report');
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

       <?php echo $this->render('_search', ['model' => $farmerEducationReportSearch]); ?>

      <?php
      //$year =
      $educations = \app\models\Education::find()
        ->all();
      $idx_education = 0;
      ?>
      <table class="table table-condensed table-striped">
        <tr>
          <th style="width:20px;" class="text-center">No</th>
          <th style="width:200px;" class="text-center">Level</th>
          <th style="width:150px;" class="text-center">Jumlah</th>
          <th style="width:50px;" class="text-center">Satuan</th>
          <th class="text-center">Keterangan</th>
          <th style="width:100px;" class="text-center">Data Masuk</th>
        </tr>
        <tbody>
      <?php
      foreach ($educations as $education) {
        echo "<tr>";
        echo "<td>".++$idx_education."</td>";
        echo "<td>".$education->name."</td>";
        $where['education_id']=$education->id;
        if(!empty($years)) $where['year']=$years;
        if(!empty($quarters)) $where['quarter']=$quarters;
        if(!empty($stateIds)) $where['state_id']=$stateIds;
        $farmerEducation = \app\models\FarmerEducation::find()
            ->select("
              sum(param) as sparam,
              group_concat(note) as gcnote,
              count(state_id) as cstate,
            ")
            ->where($where)
            ->asArray()
            ->one();
        echo "<td>";
        echo $farmerEducation['sparam'];
        echo "</td>";
        echo "<td>".$education->unit."</td>";
        echo "<td>";
        echo $farmerEducation['gcnote'];
        echo "</td>";
        echo "<td>";
        $data = number_format(($farmerEducation['cstate']/$stateCount)*100,2);
        echo $data.'%';
        echo "</td>";
        echo "</tr>";
      }
      ?>
    </table>
    <?= Html::a('<span class=\'glyphicon glyphicon-export\'></span>  '.Yii::t('app', 'Cetak Excel'), ['export-excel'], ['class' => 'btn btn-success']) ?>
    </div>
  </div>
</div>
