<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TypeTools */

$this->title = Yii::t('app', 'Create Type Tools');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Type Tools'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-tools-create">

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
