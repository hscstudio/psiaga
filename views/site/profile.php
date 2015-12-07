<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\GrowlLoad;
use kartik\widgets\AlertBlock;
use yii\widgets\Pjax;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-profile">
    <h1 class="ui header"><?= Html::encode($this->title) ?></h1>

    <div class="ui attached message">
      <div class="header">
        Keterangan:
      </div>
      <p>Update data diri anda disini! </p>
      <p>
        <?php
        echo "Hak akses anda adalah sebagai " .implode(",", $authAssignments);
        ?>
      </p>
    </div>
    <div class="ui divider"></div>

    <?php Pjax::begin([
      'id' => 'site-profile-pjax',
      'enablePushState' => false,
    ]); ?>

    <div class="row">
      <div class="col-lg-5">

      <?php $form = ActiveForm::begin([
        'id' => 'site-profile-form',
        'options' => ['data-pjax' => true ]
      ]); ?>

      <?= $form->field($model, 'username') ?>

      <?= $form->field($model, 'email') ?>

      <strong> Biarkan kosong jika tidak ingin mengubah password</strong>
      <div class="ui divider"></div>

      <?= $form->field($model, 'new_password')->passwordInput(); ?>

      <?= $form->field($model, 'repeat_password')->passwordInput() ?>

      <?= $form->field($model, 'old_password')->passwordInput() ?>

      <div class="ui divider"></div>

      <div class="form-group">
          <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary',
          'data-confirm'=>"Apakah anda yakin akan menyimpan data ini?"]) ?>
      </div>

      <?php ActiveForm::end(); ?>

      </div>
    </div>
    <?php
    $this->registerJs('
    $("#profile-new_password,#profile-repeat_password,#profile-old_password").focus(function(){
      this.type = "text";
    }).blur(function(){
      this.type = "password";
    })
    ');
    if(Yii::$app->request->isAjax){
      GrowlLoad::reload($this);
      AlertBlock::widget(Yii::$app->params['alertBlockConfig']);
    }
    ?>
    <?php Pjax::end(); ?>
</div>
