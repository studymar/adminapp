<?php

 namespace app\controllers;

use Yii;
use app\models\filters\MyAccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\user\User;
use app\models\role\Right;
use yii\web\ServerErrorHttpException;

class MainController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => MyAccessControl::className(),
                //'only' => [],
                //'except' => [],
                'rules' => [
                    //wenn action angegeben, dann werden die angegebenen roles(=rights) für diese action benötigt
                    //sonst werden die roles(=rights) für alle actions benötigt (nur roles angegeben)
                    //gar nichts angegeben = immer erlaubt
                    [
                        'actions' => ['index','logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['config'],
                        'allow' => true,
                        'roles' => [Right::PAGE_ADMIN_MENU],
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

    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }    
    
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            /*
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
             */
        ];
    }

    public function actionError()
    {
        $this->layout = 'center';
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['message' => $exception->getMessage()]);
        }
    }    
    
    
    /**
     * Ruft die Startseite auf
     */
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }    
   
    
}



