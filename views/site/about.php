<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = "About";
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>

    <code><?= __FILE__ ?></code>

    <hr>

    <?= Html::img("@web/images/peta.png",["id"=>"kabupaten", "usemap"=>"#kecamatan"]) ?>
    <map name="kecamatan" id="kecamatan">
      
        <area title="Daha Utara" href="#" shape="poly" id="kecamatan1" coords="" />
        <area title="Daha Barat" href="#" shape="poly" id="kecamatan2" coords="" />
        <area title="Daha Selatan" href="#" shape="poly" id="kecamatan3" coords="" />
        <area title="Kalumpang" href="#" shape="poly" id="kecamatan4" coords="" />
        <area title="Simpur" href="#" shape="poly" id="kecamatan5" coords="" />
        <area title="Kandangan" href="#" shape="poly" id="kecamatan6" coords="" />
        <area title="Angkinang" href="#" shape="poly" id="kecamatan7" coords="" />
        <area title="Telaga Langsat" href="#" shape="poly" id="kecamatan8" coords="" />
        <area title="Sungai Raya" href="#" shape="poly" id="kecamatan9" coords="" />
        <area title="Padang Batung" href="#" shape="poly" id="kecamatan10" coords="" />
        <area title="Loksado" href="#" shape="poly" id="kecamatan11" coords="" />
    </map>
CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `coords` varchar(255) NULL,
  `sort` int(5) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

INSERT INTO `state` (`id`, `name`, `coords`, `sort`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Daha Utara', '', NULL, 1449368826, 1, 1449379739, 1),
(2, 'Daha Barat', '', NULL, 1449382632, 1, 1449382632, 1),
(3, 'Daha Selatan', '', NULL, 1449462933, 1, 1449462933, 1),
(4, 'Kalumpang', '', NULL, 1449462945, 1, 1449462945, 1),
(5, 'Simpur', '', NULL, 1449462954, 1, 1449462954, 1),
(6, 'Kandangan', '', NULL, NULL, 1, NULL, 1),
(7, 'Angkinang', '', NULL, NULL, 1, NULL, 1),
(8, 'Telaga Langsat', '', NULL, NULL, 1, NULL, 1),
(9, 'Sungai Raya', '', NULL, NULL, 1, NULL, 1),
(10, 'Padang Batung', '', NULL, NULL, 1, NULL, 1),
(11, 'Loksado', '', NULL, NULL, 1, NULL, 1);
    <?php
    $this->registerJsFile("@web/js/jquery.imagemapster.js", ["depends" => [\yii\web\JqueryAsset::className()]]);
    $this->registerJs('
      $("img#kabupaten").mapster({
        mapKey: "id",
        singleSelect: true,
        areas:  [
          {
             key: "a1",
             toolTip: "Dont mess with Texas"
          }
        ],
        onClick: function(data){
          alert(data.key)
        }
      });

    ')
    ?>
</div>
