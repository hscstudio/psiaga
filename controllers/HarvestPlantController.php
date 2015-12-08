<?php

namespace app\controllers;

use Yii;
use app\models\Plant;
use app\models\TypePlant;
use app\models\HarvestPlant;
use app\models\HarvestPlantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HarvestPlantController implements the CRUD actions for HarvestPlant model.
 */
class HarvestPlantController extends Controller
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
     * Lists all HarvestPlant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $state_id = Yii::$app->user->identity->state_id;
        $searchModel = new HarvestPlantSearch();
        if($state_id>0){
            $searchModel = new HarvestPlantSearch([
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
     * Displays a single HarvestPlant model.
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
     * Creates a new HarvestPlant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($plant_id=0)
    {
        $model = new HarvestPlant();
        $model->state_id = Yii::$app->user->identity->state_id;
        $model->year = date('Y');
        if(in_array(date('m'),[10,11,12])) $model->quarter = 4;
        if(in_array(date('m'),[ 7, 8, 9])) $model->quarter = 3;
        if(in_array(date('m'),[ 4, 5, 6])) $model->quarter = 2;
        if(in_array(date('m'),[ 1, 2, 1])) $model->quarter = 1;
        $typePlant = null;
        if($plant_id>0){
          $plant = Plant::findOne($plant_id);
          $typePlant = TypePlant::findOne($plant->type_plant_id);
          $model->plant_id = $plant_id;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'typePlant' => $typePlant,
            ]);
        }
    }

    /**
     * Updates an existing HarvestPlant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $typePlant = null;
        if($model->plant_id>0){
          $plant = Plant::findOne($model->plant_id);
          $typePlant = TypePlant::findOne($plant->type_plant_id);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'typePlant' => $typePlant,
            ]);
        }
    }

    /**
     * Deletes an existing HarvestPlant model.
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
     * Finds the HarvestPlant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HarvestPlant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HarvestPlant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
