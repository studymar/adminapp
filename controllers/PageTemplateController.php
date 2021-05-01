<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\content\Page;
use app\models\user\User;
use app\models\role\Right;

class PageTemplateController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    // allow authenticated users
                    [
                        //Liste anzeigen, ändern nur mit Rechten
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => [Right::PAGE_EDIT],
                    ],
                    [
                        //nur eingeloggt erlaubt
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied by default
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
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
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     *
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Ruft eine beliebige Contentseite auf
     * @param string $p [optional] Urlname der Seite. Wenn leer dann Startseite.
     */
    public function actionIndex($p = false)
    {
        //Seite identifizieren
        $page = $this->findPage($p);

        
        
    }
    
    
    /**
     * Ruft eine beliebige Contentseite auf
     * @param string $p [optional] Urlname der Seite. Wenn leer dann Startseite.
     */
    public function actionEdit($p = false)
    {
        //Seite identifizieren
        $page = $this->findPage($p);
        $page->isEditModus = true;
        
        
    }
    
    
    /**
     * Anhand p (urlame der Page) wird die Seite geladen
     * wenn kein urlame übergeben wurde wird die Homepage gefunden
     * @param string $p Urlname einer Page 
     * @throws NotFoundHttpException
     */
    public function findPage($p = false)
    {
        //anzuzeigende Seite finden
        //wenn urlname der Seite in url übergeben, dann
        if($p){
            //seite mit dem urlname suchen
            $page = Page::find()->where(['urlname'=>$p])->one();
            //wenn nicht gefunden, fehler werfen
            if(!$page){
                Yii::debug('Page not found: '.$p, __METHOD__);
                throw new NotFoundHttpException('Die aufgerufene Seite wurde nicht gefunden.');
            }
        }
        //sonst = kein urlname, dann startseite nehmen
        else {
            $page = Page::getHomepage();
        }
        
        return $page;
        
    }    
    
   
    
}



