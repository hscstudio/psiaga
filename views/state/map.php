<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = "Map";
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="site-about">
  <div class="panel panel-default">
     <div class="panel-heading">
        <h3 class="panel-title">
          <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> &nbsp;
          <?= Html::encode($this->title) ?></h3>
     </div>
     <div class="panel-body">
       <p>
         <?= Html::a('<span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> Back', ['index'], ['class' => 'btn btn-default']) ?>
      </p>

      <div class="row">
        <div class="col-md-6">
          <h3>Peta Kab. Hulu Sungai Selatan</h3>
          <?= Html::img("@web/images/peta.png",["id"=>"kabupaten", "usemap"=>"#kecamatan"]) ?>
          <map name="kecamatan" id="kecamatan">
            <?php
            foreach ($model as $key => $value) {
              echo '<area title="'.$value['name'].'" href="#" shape="poly" id="kec-'.$value['id'].'" coords="'.$value['coords'].'" />';
            }
            ?>
          </map>
          <?= Html::a('<i class="glyphicon glyphicon-zoom-out"></i> zoom out', '#',['id'=>'zoomOut','class'=>"btn btn-md btn-default"]) ?>
          <?= Html::a('<i class="glyphicon glyphicon-zoom-in"></i> zoom in', '#',['id'=>'zoomIn','class'=>"btn btn-md btn-default"]) ?>
        </div>
        <div class="col-md-6">
          <div id="map-info">
            <h3>Daftar Kecamatan</h3>
            <?php
            $idx = 0;
            foreach ($model as $key => $value) {
              echo ++$idx.'.&nbsp;'.$value['name'].'<br>';
            }
            ?>
          </div>
        </div>
      </div>

    <?php
    $this->registerJsFile("@web/js/jquery.imagemapster.js", ["depends" => [\yii\web\JqueryAsset::className()]]);
    $this->registerJs('
      $("img#kabupaten").mapster({
        mapKey: "id",
        singleSelect: true,
        showToolTip: true,
        onClick: function(data){
          //alert(data.key)
        }
      });

      var map_info = $("#map-info").html();

      $("map#kecamatan > area").mouseover(function() {
          var url = $(this).attr("href");
          var id = $(this).attr("id");
          var title = $(this).attr("title");
          var coords = $(this).attr("coords"); //.split(",");
          var ids = id.split("-")
          $.get( "'.\yii\helpers\Url::to(['map-detail']).'?id="+ids[1], function( data ) {
            $("#map-info").html(data)
          });

          // To prevent default action
          return false;
      });

      $("map#kecamatan > area").mouseout(function() {
          $("#map-info").html(map_info)
      })

      $("#zoomOut").bind("click",function() {
        width = $("img#kabupaten").width()-10;
        $("img#kabupaten").mapster("resize", width, 0, 1000);
      });
      $("#zoomIn").bind("click",function() {
        width = $("img#kabupaten").width()+10;
        $("img#kabupaten").mapster("resize", width, 0, 1000);
      });

    ')
    ?>
    </div>
  </div>
</div>
