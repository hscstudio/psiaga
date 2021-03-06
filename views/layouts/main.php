<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use Zelenin\yii\SemanticUI\collections\Breadcrumb;
use app\assets\AppAsset;
//use app\widgets\Alert;
use kartik\widgets\AlertBlock;
use yii\bootstrap\Modal;
use hscstudio\mimin\components\Mimin;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Siaga',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
        'innerContainerOptions' => [
            'class' => 'container-fluid'
        ]
    ]);

    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        //['label' => 'About', 'url' => ['/site/about']],
        //['label' => 'Contact', 'url' => ['/site/contact']],
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $items=[
            ['label' => 'User', 'url' => ['/mimin/user/index']],
            ['label' => 'Role', 'url' => ['/mimin/role/index']],
            ['label' => 'Route', 'url' => ['/mimin/route/index']],
        ];
        $items = Mimin::filterRouteMenu($items);
        if(count($items)>0){
            $menuItems[] = ['label' => 'Administrator', 'items' => $items];
        }

        $items = [
            ['label' => 'Kecamatan', 'url' => ['/state/index']],
            ['label' => 'Jenis Tanaman', 'url' => ['/type-plant/index']],
            ['label' => 'Tanaman', 'url' => ['/plant/index']],
            ['label' => 'Jenis Alat Pertanian', 'url' => ['/type-tools/index']],
            ['label' => 'Alat Pertanian', 'url' => ['/tools/index']],
            ['label' => 'Pendidikan', 'url' => ['/education/index']],
            //'<hr>',
        ];
        $items = Mimin::filterRouteMenu($items);
        if(count($items)>0){
            $menuItems[] = ['label' => 'Master', 'items' => $items];
        }

        $items = [
            ['label' => 'Data Panen', 'url' => ['/harvest-plant/index']],
            ['label' => 'Data Alat Pertanian', 'url' => ['/harvest-tools/index']],
            ['label' => 'Data Pendidikan', 'url' => ['/farmer-education/index']],
        ];
        $items = Mimin::filterRouteMenu($items);
        if(count($items)>0){
            $menuItems[] = ['label' => 'Transaksi', 'items' => $items];
        }

        $items = [
            ['label' => 'Laporan Hasil Panen', 'url' => ['/harvest-plant-report/index']],
            ['label' => 'Laporan Alat Pertanian', 'url' => ['/harvest-tools-report/index']],
            ['label' => 'Laporan Pendidikan Petani', 'url' => ['/farmer-education-report/index']],
        ];
        $items = Mimin::filterRouteMenu($items);
        if(count($items)>0){
            $menuItems[] = ['label' => 'Laporan', 'items' => $items];
        }

        $menuItems[] = ['label' => substr(Yii::$app->user->identity->username,0,10), 'items' => [
              ['label' => 'Profile', 'url' => ['/site/profile']],
              [
                  'label' => 'Logout',
                  'url' => ['/site/logout'],
                  'linkOptions' => ['data-method' => 'post']
              ],
          ]];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);

    NavBar::end();
    ?>

    <div class="ui container-fluid">
      <div style="margin:65px 0 10px 0;">
        <div style="">
          <?= Breadcrumb::widget([
              'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
          ]) ?>
        </div>
        <div class="ui blue piled segment">
        <?= AlertBlock::widget(Yii::$app->params['alertBlockConfig']); ?>
        <?= $content ?>
        </div>
      </div>
    </div>
</div>

<?php
Modal::begin([
    'id' => 'myModal',
    'header' => '<h4 class="modal-title">...</h4>',
]);
echo '...';
Modal::end();
?>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left">&copy; Pemerintah Kabupaten Hulu Sungai Selatan <?= date('Y') ?></p>

        <!-- <p class="pull-right"><?= Yii::powered() ?></p> -->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
