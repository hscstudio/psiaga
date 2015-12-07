<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\administrator\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Roles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> &nbsp; <?= Html::encode($this->title) ?></h3>
   </div>
   <div class="panel-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Role', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            /*
            'type',
            'description:ntext',
            'rule_name',
            'data:ntext',
            // 'created_at',
            // 'updated_at',
            */
            [
              'header' => 'Actions',
              'options' => [
                'width' => '80px',
              ],
              'class' => 'yii\grid\ActionColumn'
            ],
        ],
    ]); ?>

	</div>
</div>

</div>
