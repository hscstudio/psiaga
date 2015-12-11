<?php

namespace app\controllers;

use Yii;
use app\models\Tools;
use app\models\TypeTools;
use app\models\HarvestTools;
use app\models\HarvestToolsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HarvestToolsController implements the CRUD actions for HarvestTools model.
 */
class HarvestToolsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all HarvestTools models.
     * @return mixed
     */
    public function actionIndex()
    {
        $state_id = Yii::$app->user->identity->state_id;
        $searchModel = new HarvestToolsSearch();
        if($state_id>0){
            $searchModel = new HarvestToolsSearch([
              'state_id'=>$state_id
            ]);
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HarvestTools model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HarvestTools model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($tools_id=0)
    {
        $model = new HarvestTools();
        $model->state_id = Yii::$app->user->identity->state_id;
        $model->year = date('Y');
        if(in_array(date('m'),[10,11,12])) $model->quarter = 4;
        if(in_array(date('m'),[ 7, 8, 9])) $model->quarter = 3;
        if(in_array(date('m'),[ 4, 5, 6])) $model->quarter = 2;
        if(in_array(date('m'),[ 1, 2, 1])) $model->quarter = 1;
        if($tools_id>0){
          $tools = Tools::findOne($tools_id);
          $typeTools = TypeTools::findOne($tools->type_tools_id);
          $model->tools_id = $tools_id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'typeTools' => $typeTools,
            ]);
        }
    }

    /**
     * Updates an existing HarvestTools model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $typeTools = null;
        if($model->tools_id>0){
          $tools = Tools::findOne($model->tools_id);
          $typeTools = TypeTools::findOne($tools->type_tools_id);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'typeTools' => $typeTools,
            ]);
        }
    }

    /**
     * Deletes an existing HarvestTools model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HarvestTools model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HarvestTools the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HarvestTools::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
