<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tools */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tools'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tools-view">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'unit',
            'sort',

          [
            'attribute'=>'created_at',
            'format'=>['date','php:d M Y H:i:s'],
          ],
          
          [
            'attribute'=>'created_by',
            'value'=>@\app\models\User::findOne($model->created_by)->username,
          ],
          
          [
            'attribute'=>'updated_at',
            'format'=>['date','php:d M Y H:i:s'],
          ],
          
          [
            'attribute'=>'updated_by',
            'value'=>@\app\models\User::findOne($model->updated_by)->username,
          ],
                  ],
    ]) ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> Back', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a(Yii::t('app', '<span class=\'glyphicon glyphicon-pencil\'></span> Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', '<span class=\'glyphicon glyphicon-trash\'></span> Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    </div>
  </div>
</div>
