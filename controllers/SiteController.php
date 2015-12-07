<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use app\models\Profile;
use hscstudio\mimin\models\AuthAssignment;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['signup','login', 'error','reset-password','request-password-reset'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
    		/*
        $auth = Yii::$app->authManager;

    		$module = $this->module->id;
    		$controller = $this->id;
    		$action = $this->action->id;

        // add "updatePost" permission
        $updateKomisi = $auth->createPermission($module.'/'.$controller.'/'.$action);
        $updateKomisi->description = 'Update '.$module.'/'.$controller.'/'.$action;
        $auth->add($updateKomisi);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateKomisi);

        $auth->assign($admin, 1);
    		//echo $module.'/'.$controller.'/'.$action;
    		//exit;
    		*/

        /*
        $message = "Halo Apa kabar";
        $key = "abcd1234";
        echo $message;
        echo "<br>";
        $encr = \app\components\AesCtr::encrypt($message, $key, 256);
        echo $encr;
        echo "<br>";
        $decr = \app\components\AesCtr::decrypt($encr, $key, 256);
        echo $decr;
        echo "<br>";
        exit;
        */
        //$decr = \app\components\AesCtr::decrypt($cipher, $pw, 256);
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionProfile()
    {
        $model = $this->findProfile();
        $ajax = Yii::$app->request->isAjax;
        if ($model->load(Yii::$app->request->post())) {
          if(!empty($model->new_password)){
            if($model->validatePassword($model->old_password)){
                $model->setPassword($model->new_password) ;
            }
            else{
                Yii::$app->session->setFlash('warning','Password lama salah');
            }
          }
          if($model->save()){
              Yii::$app->session->setFlash('success','Profile berhasil diupdate');
          }
          else{
              Yii::$app->session->setFlash('error','Profile gagal diupdate');
          }
          //return $this->refresh();
        }

        $authAssignments = AuthAssignment::find()->where([
          'user_id' => Yii::$app->user->id,
        ])->column();

        if($ajax){
          return $this->renderAjax('profile', [
              'model' => $model,
              'authAssignments' => $authAssignments,
          ]);
        }
        else
          return $this->render('profile', [
              'model' => $model,
              'authAssignments' => $authAssignments,
          ]);
    }

    /**
     * Finds the Securitas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Securitas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProfile()
    {
        $id = (int) Yii::$app->user->id;
        if (($model = Profile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
