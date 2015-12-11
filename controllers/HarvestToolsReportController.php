<?php

namespace app\controllers;

use Yii;
use app\models\Tools;
use app\models\State;
use app\models\TypeTools;
use app\models\HarvestTools;
use app\models\HarvestToolsSearch;
use app\models\HarvestToolsReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Helpers;

/**
 * HarvestPlantController implements the CRUD actions for HarvestPlant model.
 */
class HarvestToolsReportController extends Controller
{

    /**
     * Lists all HarvestPlant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $harvestToolsReportSearch = new HarvestToolsReportSearch();
        $harvestToolsReportSearch->yearStart = date('Y');
        $harvestToolsReportSearch->yearEnd = $harvestToolsReportSearch->yearStart;
        $harvestToolsReportSearch->quarterStart = 1;
        if(date('m')>=9){
          $harvestToolsReportSearch->quarterEnd = 4;
        }
        else if(date('m')>=6){
          $harvestToolsReportSearch->quarterEnd = 3;
        }
        else if(date('m')>=3){
          $harvestToolsReportSearch->quarterEnd = 2;
        }
        else{
          $harvestToolsReportSearch->quarterEnd = 1;
        }

        $typeToolses = TypeTools::find()->all();
        $stateCount = State::find()->count();
        $years = [];
        $quarters = [];
        $states = State::find()->select('name')->column();
        $typeToolses2 = TypeTools::find()->select('name')->column();
        $stateIds = [];
        if ($harvestToolsReportSearch->load(Yii::$app->request->post())) {
            $stateIds = $harvestToolsReportSearch->stateIds;
            if(!empty($stateIds)){
                $stateCount = State::find()
                  ->where(['id'=>$stateIds])
                  ->count();
                $states = State::find()
                  ->select('name')
                  ->where(['id'=>$stateIds])
                  ->column();
            }

            $typeToolsIds = $harvestToolsReportSearch->typeToolsIds;
            if(!empty($typeToolsIds)){
                  $typeToolses = TypeTools::find()
                  ->where(['id'=>$typeToolsIds])
                  ->all();
                  $typeToolses2 = TypeTools::find()
                  ->select('name')
                  ->where(['id'=>$typeToolsIds])
                  ->column();
            }

            $yearStart = $harvestToolsReportSearch->yearStart;
            $yearEnd = $harvestToolsReportSearch->yearEnd;
            for($i=$yearStart;$i<=$yearEnd;$i++){
              $years[] =  $i;
            }

            $quarterStart = $harvestToolsReportSearch->quarterStart;
            $quarterEnd = $harvestToolsReportSearch->quarterEnd;
            for($i=$quarterStart;$i<=$quarterEnd;$i++){
              $quarters[] =  $i;
            }
        }

        $renders['typeToolses']= $typeToolses;
        $renders['stateCount']= $stateCount;
        $renders['stateIds']= $stateIds;
        $renders['years']= $years;
        $renders['quarters']= $quarters;
        $renders['states']= $states;
        $renders['typeToolses2']= $typeToolses2;
        $session = Yii::$app->session;
        $session->set('renderHarvestToolsReport',$renders);
        $renders['harvestToolsReportSearch']=$harvestToolsReportSearch;
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
        $renderHarvestToolsReport = $session->get('renderHarvestToolsReport');

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
        if(!empty($renderHarvestToolsReport['years'])){
          $years = implode(', ', $renderHarvestToolsReport['years']);
        }
        $activeSheet->setCellValue('C3', $years);

        $quarters = 'Semua';
        if(!empty($renderHarvestToolsReport['quarters'])){
          $quarters = implode(', ', $renderHarvestToolsReport['quarters']);
        }
        $activeSheet->setCellValue('C4', $quarters);

        $states = 'Semua';
        if(!empty($renderHarvestToolsReport['states'])){
          $states = implode(', ', $renderHarvestToolsReport['states']);
        }
        $activeSheet->setCellValue('C5', $states);

        $typeToolses2 = 'Semua';
        if(!empty($renderHarvestToolsReport['typeToolses2'])){
          $typeToolses2 = implode(', ', $renderHarvestToolsReport['typeToolses2']);
        }
        $activeSheet->setCellValue('C7', $typeToolses2);

        $typeToolses = $renderHarvestToolsReport['typeToolses'];
        $stateCount = $renderHarvestToolsReport['stateCount'];
        $startRow = 11;
        $i=0;
        foreach ($typeToolses as $typeTools) {
          if($i==0) $row = $startRow+$i;
          else $row++;
          //$activeSheet->insertNewRowBefore($row,1);
          $activeSheet->setCellValue('A'.$row, Helpers::columnLetter($i+1));
          $activeSheet->setCellValue('B'.$row, $typeTools->name);
          $activeSheet->getStyle("A".$row.":B".$row)->applyFromArray($font_bold9);
          $activeSheet->getStyle("A".$row.":A".$row)->getAlignment()->applyFromArray($text_center);
          $toolss = Tools::find()
            ->where(['type_tools_id'=>$typeTools->id])
            ->all();
          $idx_tools = 0;
          foreach ($toolss as $tools) {
              //$activeSheet->insertNewRowBefore($row,1);
              $row++;
              $activeSheet->setCellValue('B'.$row, $idx_tools+1);
              $activeSheet->setCellValue('C'.$row, $tools->name);
              $activeSheet->getStyle("B".$row.":C".$row)->applyFromArray($font_bold8);
              $activeSheet->getStyle("B".$row.":B".$row)->getAlignment()->applyFromArray($text_center);

              $where['tools_id']=$tools->id;
              if(!empty($renderHarvestToolsReport['years'])) $where['year']=$renderHarvestToolsReport['years'];
              if(!empty($renderHarvestToolsReport['quarters'])) $where['quarter']=$renderHarvestToolsReport['quarters'];
              if(!empty($renderHarvestToolsReport['stateIds'])) $where['state_id']=$renderHarvestToolsReport['stateIds'];
              $harvestToolses = HarvestTools::find()
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
              $params = explode('|', $typeTools->params);
              $units = explode('|', $typeTools->units);
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
                    $activeSheet->setCellValue('D'.$row, $harvestToolses['sparam'.$idx]);
                    $activeSheet->setCellValue('E'.$row, $unit);
                    //$activeSheet->getStyle("F".$row)->getAlignment()->setWrapText(true);
                    //$activeSheet->getDefaultRowDimension()->setRowHeight(-1);
                    $activeSheet->getRowDimension($row)->setRowHeight(-1);
                    $activeSheet->setCellValue('F'.$row, $harvestToolses['gcnote'.$idx]. ' lorem ipsum sit dolor amet lorem ipsum sit dolor amet lorem ipsum sit dolor amet lorem ipsum sit dolor amet ');
                    $data = number_format(($harvestToolses['cstate']/$stateCount)*100,2);
                    $activeSheet->setCellValue('G'.$row, $data.'%');
                    $idx++;
                  }
                  $activeSheet->getStyle("B".$starRowTable.":G".$row)->applyFromArray($border_style);
                  $activeSheet->getStyle("B".$starRowTable.":G".$row)->getAlignment()->applyFromArray($text_center);
                  $activeSheet->getStyle("F".($starRowTable+1).":F".$row)->getAlignment()->setWrapText(true);

              }
              $row++;
              $idx_tools++;
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
