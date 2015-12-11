<?php

namespace app\controllers;

use Yii;
use app\models\State;
use app\models\Education;
use app\models\FarmerEducation;
use app\models\FarmerEducationSearch;
use app\models\FarmerEducationReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\Helpers;

/**
 * FarmerEducationController implements the CRUD actions for FarmerEducation model.
 */
class FarmerEducationReportController extends Controller
{

    /**
     * Lists all FarmerEducation models.
     * @return mixed
     */
     public function actionIndex()
     {
         $farmerEducationReportSearch = new FarmerEducationReportSearch();
         $farmerEducationReportSearch->yearStart = date('Y');
         $farmerEducationReportSearch->yearEnd = $farmerEducationReportSearch->yearStart;
         $farmerEducationReportSearch->quarterStart = 1;
         if(date('m')>=9){
           $farmerEducationReportSearch->quarterEnd = 4;
         }
         else if(date('m')>=6){
           $farmerEducationReportSearch->quarterEnd = 3;
         }
         else if(date('m')>=3){
           $farmerEducationReportSearch->quarterEnd = 2;
         }
         else{
           $farmerEducationReportSearch->quarterEnd = 1;
         }

         $stateCount = State::find()->count();
         $years = [];
         $quarters = [];
         $states = State::find()->select('name')->column();
         $stateIds = [];
         if ($farmerEducationReportSearch->load(Yii::$app->request->post())) {
             $stateIds = $farmerEducationReportSearch->stateIds;
             if(!empty($stateIds)){
                 $stateCount = State::find()
                   ->where(['id'=>$stateIds])
                   ->count();
                 $states = State::find()
                   ->select('name')
                   ->where(['id'=>$stateIds])
                   ->column();
             }

             $yearStart = $farmerEducationReportSearch->yearStart;
             $yearEnd = $farmerEducationReportSearch->yearEnd;
             for($i=$yearStart;$i<=$yearEnd;$i++){
               $years[] =  $i;
             }

             $quarterStart = $farmerEducationReportSearch->quarterStart;
             $quarterEnd = $farmerEducationReportSearch->quarterEnd;
             for($i=$quarterStart;$i<=$quarterEnd;$i++){
               $quarters[] =  $i;
             }
         }

         $renders['stateCount']= $stateCount;
         $renders['stateIds']= $stateIds;
         $renders['years']= $years;
         $renders['quarters']= $quarters;
         $renders['states']= $states;
         $session = Yii::$app->session;
         $session->set('renderFarmerEducationReport',$renders);
         $renders['farmerEducationReportSearch']=$farmerEducationReportSearch;
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
         $renderFarmerEducationReport = $session->get('renderFarmerEducationReport');

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
         if(!empty($renderFarmerEducationReport['years'])){
           $years = implode(', ', $renderFarmerEducationReport['years']);
         }
         $activeSheet->setCellValue('C3', $years);

         $quarters = 'Semua';
         if(!empty($renderFarmerEducationReport['quarters'])){
           $quarters = implode(', ', $renderFarmerEducationReport['quarters']);
         }
         $activeSheet->setCellValue('C4', $quarters);

         $states = 'Semua';
         if(!empty($renderFarmerEducationReport['states'])){
           $states = implode(', ', $renderFarmerEducationReport['states']);
         }
         $activeSheet->setCellValue('C5', $states);

         $stateCount = $renderFarmerEducationReport['stateCount'];
         $startRow = 9;

         //$year =
         $educations = \app\models\Education::find()
           ->all();
         $idx_education = 0;
         $row = $startRow;
         $starRowTable = $row;
         $activeSheet->setCellValue('B'.$row, 'NO');
         $activeSheet->setCellValue('C'.$row, 'LEVEL');
         $activeSheet->setCellValue('D'.$row, 'JUMLAH');
         $activeSheet->setCellValue('E'.$row, 'SATUAN');
         $activeSheet->setCellValue('F'.$row, 'KETERANGAN');
         $activeSheet->setCellValue('G'.$row, 'DATA MASUK');
         $activeSheet->getStyle('B'.$row.':G'.$row)->applyFromArray($bg_gray);
         foreach ($educations as $education) {
           $row++;
           $activeSheet->setCellValue('B'.$row, ++$idx_education);
           $activeSheet->setCellValue('C'.$row, $education->name);
           $where['education_id']=$education->id;
           if(!empty($renderFarmerEducationReport['years'])) $where['year']=$renderFarmerEducationReport['years'];
           if(!empty($renderFarmerEducationReport['quarters'])) $where['quarter']=$renderFarmerEducationReport['quarters'];
           if(!empty($renderFarmerEducationReport['stateIds'])) $where['state_id']=$renderFarmerEducationReport['stateIds'];
           $farmerEducation = \app\models\FarmerEducation::find()
               ->select("
                 sum(param) as sparam,
                 group_concat(note) as gcnote,
                 count(state_id) as cstate,
               ")
               ->where($where)
               ->asArray()
               ->one();
           $activeSheet->setCellValue('D'.$row, $farmerEducation['sparam']);
           $activeSheet->setCellValue('E'.$row, $education->unit);
           $activeSheet->setCellValue('F'.$row, $farmerEducation['gcnote']);
           $data = number_format(($farmerEducation['cstate']/$stateCount)*100,2);
           $activeSheet->setCellValue('G'.$row, $data.'%');
         }
         $activeSheet->getStyle("B".$starRowTable.":G".$row)->applyFromArray($border_style);
         $activeSheet->getStyle("B".$starRowTable.":G".$row)->getAlignment()->applyFromArray($text_center);
         $activeSheet->getStyle("F".($starRowTable+1).":F".$row)->getAlignment()->setWrapText(true);

         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
         header('Content-Disposition: attachment;filename="'.$this->id.'_'.date('YmdHis').'.xlsx"');
         header('Cache-Control: max-age=0');
         $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
         $objWriter->save('php://output');
         exit;
     }
}
