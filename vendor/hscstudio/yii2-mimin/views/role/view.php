<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use hscstudio\mimin\models\Route;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model hscstudio\mimin\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> &nbsp; <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            //'type',
            //'description:ntext',
            //'rule_name',
            //'data:ntext',
            [
              'attribute' => 'created_at',
              'format' => ['date','php:d M Y H:i:s'],
            ],
            [
              'attribute' => 'updated_at',
              'format' => ['date','php:d M Y H:i:s'],
            ],
        ],
    ]) ?>

    <?php
    $types = Route::find()->where(['status' => 1])->groupBy('type')->all();
    echo "<table class='table table-condensed table-striped table-hover'>";
    echo "<tr>";
      echo "<th>Type</th>";
      echo "<th>Permision</th>";
    echo "<tr>";
    $auth = Yii::$app->authManager;
    $permissions = $auth->getPermissionsByRole($model->name);
    //echo "<tbody>";
    foreach ($types as $type) {
      echo "<tr>";
      echo "<td>".$type->type."</td>";
      echo "<td>";
      $aliass = Route::find()->where([
        'status' => 1,
        'type' => $type->type,
      ])->all();
      foreach ($aliass as $alias) {
        echo "<label style='display:block;width:100px;float:left;overflow:hidden;'>";
		$can = array_key_exists($alias->name,$permissions);
		$checked = false;
		if($can) $checked = true;
		echo Html::checkbox($type->type.'_'.$alias->alias,$checked,[
		  'title' => $alias->name,
		  'class' => 'checkboxPermission',
		]).' '.$alias->alias;
		echo "</label>";
      }
      echo "</td>";
      echo "</tr>";
    }
    //echo "</tbody>";
    echo "</table>";
    ?>
  </div>
</div>
</div>

<?php
$this->registerJs('
  $(".checkboxPermission").bind("click", function(){
      setPermission($(this).attr("title"));
  });

  function setPermission(permissionName){
    $.post( "'.Url::to(['permission','roleName'=>$model->name]).'&permissionName="+permissionName, function( data ) {
        console.log(data.data)
    });
  }
');
