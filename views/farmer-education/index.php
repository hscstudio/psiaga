<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FarmerEducationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Farmer Educations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farmer-education-index">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '<span class=\'glyphicon glyphicon-plus-sign\'></span>  Create Farmer Education'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $state_id = Yii::$app->user->identity->state_id;
    $columns[] = ['class' => 'yii\grid\SerialColumn'];

    $columns[] = [
      'attribute' => 'education_id',
      'format' => 'raw',
      'value' => function($data){
        if($data->education_id>0)
          return \app\models\Education::findOne($data->education_id)->name;
        else
          return "-";
      }
    ];

    if($state_id>0){

    }
    else{
      $columns[] = [
        'attribute' => 'state_id',
        'format' => 'raw',
        'value' => function($data){
          if($data->state_id>0)
            return \app\models\State::findOne($data->state_id)->name;
          else
            return "-";
        }
      ];
    }

    $columns[] = [
      'attribute' => 'year',
      'headerOptions' => [
          'style' => 'width:75px;',
          'class' => 'text-center',
      ],
      'contentOptions' => [
          'class' => 'text-center',
      ],
    ];

    $columns[] = [
      'attribute' => 'quarter',
      'headerOptions' => [
          'style' => 'width:75px;',
          'class' => 'text-center',
      ],
      'contentOptions' => [
          'class' => 'text-center',
      ],
    ];

    $columns[] = [
      'attribute' => 'param',
      'headerOptions' => [
          'style' => 'width:75px;',
          'class' => 'text-center',
      ],
      'contentOptions' => [
          'class' => 'text-center',
      ],
    ];

    /*
    $columns[] = [
      'attribute' => 'note',
      'headerOptions' => [
          'style' => 'width:75px;',
          'class' => 'text-center',
      ],
      'contentOptions' => [
          'class' => 'text-center',
      ],
    ];
    */

    $columns[] = [
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
    ];

    $columns[] = [
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
    ];

    $columns[] =[
      'class' => 'yii\grid\ActionColumn',
      'header' => 'Actions',
      'headerOptions' => [
          'style' => 'width:100px;',
          'class' => 'text-center',
      ],
      'contentOptions' => [
          'class' => 'text-center',
      ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

    </div>
  </div>
</div>
