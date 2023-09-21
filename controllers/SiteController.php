<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\TemperatureMonthly;
use app\commands\TodayController;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionTemperaturaVodyOdessa()
    {
        return $this->render('temperatura-vody-odessa', [
            'detail' => TemperatureMonthly::getDetails(),
            'dataProvider' => TemperatureMonthly::getData()
        ]);
    }
    
    /**
     * Displays pogoda-v-odesse page.
     *
     * @return string
     */
    public function actionPogodaVOdesse()
    {
        if (!$data = Yii::$app->cache->get('pogoda-v-odesse')) {
            if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'wb'));
            if(!defined('STDERR')) define('STDERR', fopen('php://stderr', 'wb'));

            $consoleController = new TodayController('today', Yii::$app);
            $consoleController->runAction('index');
            $data = Yii::$app->cache->get('pogoda-v-odesse');
        }
        return $this->render('pogoda-v-odesse', ['data' => $data]);
    }        

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
