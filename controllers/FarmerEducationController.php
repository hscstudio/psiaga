<?php

namespace app\controllers;

use Yii;
use app\models\Education;
use app\models\FarmerEducation;
use app\models\FarmerEducationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FarmerEducationController implements the CRUD actions for FarmerEducation model.
 */
class FarmerEducationController extends Controller
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
     * Lists all FarmerEducation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $state_id = Yii::$app->user->identity->state_id;
        $searchModel = new FarmerEducationSearch();
        if($state_id>0){
            $searchModel = new FarmerEducationSearch([
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
     * Displays a single FarmerEducation model.
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
     * Creates a new FarmerEducation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($education_id=0)
    {
        $model = new FarmerEducation();
        $model->state_id = Yii::$app->user->identity->state_id;
        $model->year = date('Y');
        if(in_array(date('m'),[10,11,12])) $model->quarter = 4;
        if(in_array(date('m'),[ 7, 8, 9])) $model->quarter = 3;
        if(in_array(date('m'),[ 4, 5, 6])) $model->quarter = 2;
        if(in_array(date('m'),[ 1, 2, 1])) $model->quarter = 1;
        if($education_id>0){
          $education = Education::findOne($education_id);
          $model->education_id = $education_id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'education' => $education,
            ]);
        }
    }

    /**
     * Updates an existing FarmerEducation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->education_id>0){
          $education = Education::findOne($model->education_id);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'education' => $education,
            ]);
        }
    }

    /**
     * Deletes an existing FarmerEducation model.
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
     * Finds the FarmerEducation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FarmerEducation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FarmerEducation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
