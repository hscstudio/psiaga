<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\administrator\models\AuthItem */

$this->title = 'Create Role';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> &nbsp; <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
  </div>
</div>
</div>
