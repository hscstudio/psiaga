<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Plant */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Plant',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="plant-update">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    </div>
  </div>
</div>
