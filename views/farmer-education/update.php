<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FarmerEducation */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Farmer Education',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Farmer Educations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="farmer-education-update">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

    <?= $this->render('_form', [
        'model' => $model,
        'education' => $education,
    ]) ?>

    </div>
  </div>
</div>
