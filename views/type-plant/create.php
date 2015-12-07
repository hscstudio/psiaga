<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TypePlant */

$this->title = Yii::t('app', 'Create Type Plant');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Type Plants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-plant-create">

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
