<?php

namespace app\controllers;

use Yii;
use app\models\Plant;
use app\models\State;
use app\models\TypePlant;
use app\models\HarvestPlant;
use app\models\HarvestPlantSearch;
use app\models\HarvestPlantReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Helpers;

/**
 * HarvestPlantController implements the CRUD actions for HarvestPlant model.
 */
class HarvestPlantReportController extends Controller
{

    /**
     * Lists all HarvestPlant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $harvestPlantReportSearch = new HarvestPlantReportSearch();
        $harvestPlantReportSearch->yearStart = date('Y');
        $harvestPlantReportSearch->yearEnd = $harvestPlantReportSearch->yearStart;
        $harvestPlantReportSearch->quarterStart = 1;
        if(date('m')>=9){
          $harvestPlantReportSearch->quarterEnd = 4;
        }
        else if(date('m')>=6){
          $harvestPlantReportSearch->quarterEnd = 3;
        }
        else if(date('m')>=3){
          $harvestPlantReportSearch->quarterEnd = 2;
        }
        else{
          $harvestPlantReportSearch->quarterEnd = 1;
        }

        $typePlants = TypePlant::find()->all();
        $stateCount = State::find()->count();
        $years = [];
        $quarters = [];
        $states = State::find()->select('name')->column();
        $typePlants2 = TypePlant::find()->select('name')->column();
        $stateIds = [];
        if ($harvestPlantReportSearch->load(Yii::$app->request->post())) {
            $stateIds = $harvestPlantReportSearch->stateIds;
            if(!empty($stateIds)){
                $stateCount = State::find()
                  ->where(['id'=>$stateIds])
                  ->count();
                $states = State::find()
                  ->select('name')
                  ->where(['id'=>$stateIds])
                  ->column();
            }

            $typePlantIds = $harvestPlantReportSearch->typePlantIds;
            if(!empty($typePlantIds)){
                $typePlants = TypePlant::find()
                  ->where(['id'=>$typePlantIds])
                  ->all();
                $typePlants2 = TypePlant::find()
                ->select('name')
                ->where(['id'=>$typePlantIds])
                ->column();
            }

            $yearStart = $harvestPlantReportSearch->yearStart;
            $yearEnd = $harvestPlantReportSearch->yearEnd;
            for($i=$yearStart;$i<=$yearEnd;$i++){
              $years[] =  $i;
            }

            $quarterStart = $harvestPlantReportSearch->quarterStart;
            $quarterEnd = $harvestPlantReportSearch->quarterEnd;
            for($i=$quarterStart;$i<=$quarterEnd;$i++){
              $quarters[] =  $i;
            }
        }

        $renders['typePlants']= $typePlants;
        $renders['stateCount']= $stateCount;
        $renders['stateIds']= $stateIds;
        $renders['years']= $years;
        $renders['quarters']= $quarters;
        $renders['states']= $states;
        $renders['typePlants2']= $typePlants2;
        $session = Yii::$app->session;
        $session->set('renderHarvestPlantReport',$renders);
        $renders['harvestPlantReportSearch']=$harvestPlantReportSearch;
        return $this->render('index',$renders);
    }

    /*
  	EXPORT WITH PHPEXCEL
  	*/
  	public function actionExportExcel()
    {
        //$searchModel = new SecuritasSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $session = Yii::$app->session;
        $renderHarvestPlantReport = $session->get('renderHarvestPlantReport');

        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $template = Yii::getAlias('@app/views/'.$this->id).'/_export.xlsx';
        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();
        $border_style= [
            'borders' => [
              'allborders' => [
                'style' =>\PHPExcel_Style_Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
              ]
            ]
          ];
        $text_center = ['horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER];
        $font_bold9 = ['font' => [
          'bold'  => true,
          'color' => ['rgb' => '000000'],
          'size'  => 9,
          'name'  => 'Calibri',
          ]];
        $font_bold8 = ['font' => [
          'bold'  => true,
          'color' => ['rgb' => '000000'],
          'size'  => 8,
          'name'  => 'Calibri',
          ]];
        $bg_gray =  [
              'fill' => [
                  'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => ['rgb' => 'dddddd']
              ]
          ];

        $activeSheet->setCellValue('G2', 'print at '.date('d-M-Y H:i:s'));

        $years = 'Semua';
        if(!empty($renderHarvestPlantReport['years'])){
          $years = implode(', ', $renderHarvestPlantReport['years']);
        }
        $activeSheet->setCellValue('C3', $years);

        $quarters = 'Semua';
        if(!empty($renderHarvestPlantReport['quarters'])){
          $quarters = implode(', ', $renderHarvestPlantReport['quarters']);
        }
        $activeSheet->setCellValue('C4', $quarters);

        $states = 'Semua';
        if(!empty($renderHarvestPlantReport['states'])){
          $states = implode(', ', $renderHarvestPlantReport['states']);
        }
        $activeSheet->setCellValue('C5', $states);

        $typePlants2 = 'Semua';
        if(!empty($renderHarvestPlantReport['typePlants2'])){
          $typePlants2 = implode(', ', $renderHarvestPlantReport['typePlants2']);
        }
        $activeSheet->setCellValue('C7', $typePlants2);

        $typePlants = $renderHarvestPlantReport['typePlants'];
        $stateCount = $renderHarvestPlantReport['stateCount'];
        $startRow = 11;
        $i=0;
        foreach ($typePlants as $typePlant) {
          if($i==0) $row = $startRow+$i;
          else $row++;
          //$activeSheet->insertNewRowBefore($row,1);
          $activeSheet->setCellValue('A'.$row, Helpers::columnLetter($i+1));
          $activeSheet->setCellValue('B'.$row, $typePlant->name);
          $activeSheet->getStyle("A".$row.":B".$row)->applyFromArray($font_bold9);
          $activeSheet->getStyle("A".$row.":A".$row)->getAlignment()->applyFromArray($text_center);
          $plants = Plant::find()
            ->where(['type_plant_id'=>$typePlant->id])
            ->all();
          $idx_plant = 0;
          foreach ($plants as $plant) {
              //$activeSheet->insertNewRowBefore($row,1);
              $row++;
              $activeSheet->setCellValue('B'.$row, $idx_plant+1);
              $activeSheet->setCellValue('C'.$row, $plant->name);
              $activeSheet->getStyle("B".$row.":C".$row)->applyFromArray($font_bold8);
              $activeSheet->getStyle("B".$row.":B".$row)->getAlignment()->applyFromArray($text_center);

              $where['plant_id']=$plant->id;
              if(!empty($renderHarvestPlantReport['years'])) $where['year']=$renderHarvestPlantReport['years'];
              if(!empty($renderHarvestPlantReport['quarters'])) $where['quarter']=$renderHarvestPlantReport['quarters'];
              if(!empty($renderHarvestPlantReport['stateIds'])) $where['state_id']=$renderHarvestPlantReport['stateIds'];
              $harvestPlants = HarvestPlant::find()
                  ->select("
                    sum(param1) as sparam1,
                    sum(param2) as sparam2,
                    sum(param3) as sparam3,
                    sum(param4) as sparam4,
                    sum(param5) as sparam5,
                    group_concat(note1) as gcnote1,
                    group_concat(note2) as gcnote2,
                    group_concat(note3) as gcnote3,
                    group_concat(note4) as gcnote4,
                    group_concat(note5) as gcnote5,
                    count(state_id) as cstate
                  ")
                  ->where($where)
                  ->asArray()
                  ->one();
              $params = explode('|', $typePlant->params);
              $units = explode('|', $typePlant->units);
              if(count($params)>0 and count($units)>0 and count($params)==count($units)){
                  $combines = array_combine($params, $units);
                  $idx = 1;
                  $row++;
                  $starRowTable = $row;
                  $activeSheet->setCellValue('B'.$row, 'NO');
                  $activeSheet->setCellValue('C'.$row, 'PARAMETER');
                  $activeSheet->setCellValue('D'.$row, 'NILAI');
                  $activeSheet->setCellValue('E'.$row, 'SATUAN');
                  $activeSheet->setCellValue('F'.$row, 'KETERANGAN');
                  $activeSheet->setCellValue('G'.$row, 'DATA MASUK');
                  $activeSheet->getStyle('B'.$row.':G'.$row)->applyFromArray($bg_gray);
                  foreach ($combines as $param => $unit) {
                    $row++;
                    $activeSheet->setCellValue('B'.$row, $idx);
                    $activeSheet->setCellValue('C'.$row, $param);
                    $activeSheet->setCellValue('D'.$row, $harvestPlants['sparam'.$idx]);
                    $activeSheet->setCellValue('E'.$row, $unit);
                    //$activeSheet->getStyle("F".$row)->getAlignment()->setWrapText(true);
                    //$activeSheet->getDefaultRowDimension()->setRowHeight(-1);
                    $activeSheet->getRowDimension($row)->setRowHeight(-1);
                    $activeSheet->setCellValue('F'.$row, $harvestPlants['gcnote'.$idx]. ' lorem ipsum sit dolor amet lorem ipsum sit dolor amet lorem ipsum sit dolor amet lorem ipsum sit dolor amet ');
                    $data = number_format(($harvestPlants['cstate']/$stateCount)*100,2);
                    $activeSheet->setCellValue('G'.$row, $data.'%');
                    $idx++;
                  }
                  $activeSheet->getStyle("B".$starRowTable.":G".$row)->applyFromArray($border_style);
                  $activeSheet->getStyle("B".$starRowTable.":G".$row)->getAlignment()->applyFromArray($text_center);
                  $activeSheet->getStyle("F".($starRowTable+1).":F".$row)->getAlignment()->setWrapText(true);

              }
              $row++;
              $idx_plant++;
          }
          $i++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$this->id.'_'.date('YmdHis').'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
}
