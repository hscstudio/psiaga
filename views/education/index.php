<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EducationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Educations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="education-index">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '<span class=\'glyphicon glyphicon-plus-sign\'></span>  Create Education'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
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
