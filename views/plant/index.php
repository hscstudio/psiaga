<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Plants');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plant-index">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class=\'glyphicon glyphicon-plus-sign\'></span>  '.Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
              'attribute' => 'type_plant_id',
              'format' => 'raw',
              'value' => function($data){
                if($data->type_plant_id>0)
                  return \app\models\TypePlant::findOne($data->type_plant_id)->name;
                else
                  return "-";
              }
            ],
            'name',
            'sort',
            // 'created_at',
            // 'created_by',

            [
              'attribute'=>'updated_at',
              'headerOptions' => [
                  'style' => 'width:125px;',
                  'class' => 'text-center',
              ],
              'contentOptions' => [
                  'class' => 'text-center',
              ],
              'filter'=>false,
              'format'=>['date','php:d/m/y H:i'],
            ],
            [
              'attribute'=>'updated_by',
              'headerOptions' => [
                  'style' => 'width:100px;',
                  'class' => 'text-center',
              ],
              'contentOptions' => [
                  'class' => 'text-center',
              ],
              'filter'=>false,
              'value'=>function($data){
                  return @\app\models\User::findOne($data->updated_by)->username;
              }
            ],
            [
              'class' => 'yii\grid\ActionColumn',
              'header' => 'Actions',
              'headerOptions' => [
                  'style' => 'width:100px;',
                  'class' => 'text-center',
              ],
              'contentOptions' => [
                  'class' => 'text-center',
              ],
            ],
        ],
    ]); ?>

    </div>
  </div>
</div>
