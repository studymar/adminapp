<?php

 namespace app\controllers;

use Yii;
use app\models\filters\MyAccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\content\Page;
use app\models\user\User;
use app\models\role\Right;
use app\models\content\Template;
use app\models\forms\AddTemplateForm;
use yii\web\ServerErrorHttpException;

class PageController extends Controller
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
                        'actions' => ['index','logout','add-template','edit-form','save-edit-form','remove-template-form','remove-template','sort-templates'],
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

        
        //pruefen, ob Seite freigegeben und aktiv ist
        if($page->release->isVisible() || User::checkRight(Right::PAGE_EDIT)){
            //Breadcrumb
            $breadcrumb = $page->getBreadcrumb($page);
            $this->view->params['breadcrumbs'] = $breadcrumb;
            $this->view->title = $page->headline;

            //seite fürs layout verfügbar machen
            $this->view->params['page'] = $page;// fuer submenu und Edit-Button

            return $this->render('index', [
                'page'              => $page,
                'pageHasTemplates'  => $page->getPageHasTemplates()->all(),
            ]);
            
        }
        else {
            //wenn nein (nicht freigegeben), Fehler werfen
            Yii::debug('Page not visible: '.$p, __METHOD__);
            throw new NotFoundHttpException('Seite nicht gefunden');
        }
        
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
        
        //pruefen, ob Seite freigegeben und aktiv ist
        if($page->release->isVisible() || User::checkRight(Right::PAGE_EDIT)){

            //Breadcrumb
            $breadcrumb = $page->getBreadcrumb($page);
            $this->view->params['breadcrumbs'] = $breadcrumb;
            $this->view->title = $page->headline;

            //seite fürs layout verfügbar machen
            $this->view->params['page'] = $page;// fuer submenu und Edit-Button

            return $this->render('edit', [
                'page'              => $page,
                'pageHasTemplates'  => $page->getPageHasTemplatesWithTemplates()->all(),
            ]);
            
        }
        else {
            //wenn nein (nicht freigegeben), Fehler werfen
            Yii::debug('Page not visible: '.$p, __METHOD__);
            throw new ForbiddenHttpException('Fehlende Berechtigung');
            //throw new NotFoundHttpException('Seite nicht gefunden');
        }
        
    }
    

    /**
     * Stellt die Form dar, mit welcher die Inhalte der Seite geändert werden können
     * @param string $p [optional] Urlname der Seite. Wenn leer dann Startseite.
     */
    public function actionEditForm($p = false)
    {
        //Seite identifizieren
        $page = $this->findPage($p);
        $page->isEditModus = true;
        
        //pruefen, ob Seite freigegeben und aktiv ist
        if($page){

            return $this->render('editForm', [
                'page'              => $page,
                'pageHasTemplates'  => json_encode($page->getPageHasTemplatesWithInstances()->asArray()->all()),
            ]);
            
        }
        else {
            //wenn nein (nicht freigegeben), Fehler werfen
            Yii::debug('Page not visible: '.$p, __METHOD__);
            throw new ForbiddenHttpException('Fehlende Berechtigung');
            //throw new NotFoundHttpException('Seite nicht gefunden');
        }
        
    }

    /**
     * Speichern eines Items
     * @return string
     */
    public function actionSaveEditForm($p)
    {
        $this->layout = 'nolayout';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            
            /******* Begin Speichern ************/
            // speichern, wenn daten übergeben
            $model      = Page::find()->where('id = '.$p)->one();
            if($model){
                $pageHasTemplatesReq = Yii::$app->request->post('PageHasTemplate', []);
                foreach($pageHasTemplatesReq as $pageHasTemplateReq){
                    $objectname = $pageHasTemplateReq['template']['objectname'];
                    $objClass = "\\app\\models\\content\\". ucfirst($objectname);
                    //$instance   = new $objClass;
                    $instance = $objClass::find()->where(['id'=>$pageHasTemplateReq[$objectname.'s'][0]['id']])->one();
                    $instance->updateFromRequest($pageHasTemplateReq[$objectname.'s'][0]);
                    if($instance->hasErrors())
                        $ret['errormessages'][$objectname] = $instance->getErrors();
                }
                $model->release->updateFromRequest();
                //put validationerrors in response
                if(!$model->release->hasErrors()
                    && !$model->hasErrors()
                    ){
                        $ret['saved'] = true;
                        $transaction->commit();
                    }
                else {
                    Yii::error('Got Request/not savable: '.json_encode(Yii::$app->request->post()),__METHOD__);
                    throw new Exception(Errormessages::$errors['MISSINGREQUESTDATAERROR']['message'], Errormessages::$errors['MISSINGREQUESTDATAERROR']['id']);
                }
            }
            else {
                Yii::error('Text ID: '.$p, __METHOD__);
                throw new Exception(Errormessages::$errors['DATANOTFOUNDERROR']['message'], Errormessages::$errors['DATANOTFOUNDERROR']['id']);
            }
            /******* End Speichern ************/
            
            // errormessages setzen
            if($model->hasErrors()) 
                $ret['errormessages']['item'] = $model->getErrors();
            if($model->release->hasErrors())
                $ret['errormessages']['release'] = $model->release->getErrors();

            
            
        } catch (\yii\db\Exception | \yii\base\Exception $e){
            Yii::error($e->getMessage(), __METHOD__);
            $transaction->rollBack();
            throw new ServerErrorHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        }
        return $this->asJson($ret);
    }
    

    
    /**
     * Auswahl eines Templates, dass auf einer Seite hinzugefügt werden soll
     * @param string $p ID der Page
     * @param string $p2 Gewünschte TemplateID
     */
    public function actionAddTemplate($p,$p2)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            //Seite identifizieren
            //$page = Page::find()->where(['id'=>$p])->one();
            $page = $this->findPage($p);
            $model= new AddTemplateForm();
            if($p2){
                $page->addTemplate($p2);
                $transaction->commit();
                $this->redirect(['page/edit','p'=>$page->urlname]);
            }

        }
        catch (\yii\base\Exception $e){
            $transaction->rollBack();
            Yii::error($e, __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'SERVERERROR'));
        }
    }

    /**
     * Auswahl eines Templates, dass auf einer Seite entfernt werden soll
     * @param string $p Urlname der Page
     */
    public function actionRemoveTemplateForm($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $page = $this->findPage($p);
            $form = new \app\models\forms\RemoveTemplateForm();
            if($form->load(Yii::$app->request->post()) && $form->validate()){
                $item = \app\models\content\PageHasTemplate::find()->where('id = '.$form->page_has_template_id)->one();                    
                if($item->deletePageHasTemplate()){
                    Yii::debug('Item deleted');
                    $transaction->commit();
                    $this->redirect(['page/edit','p'=>$page->urlname]);
                }
            }
            
            return $this->render('removeTemplate', [
                 'model'             => $form,
                 'page'              => $page,
                 'pageHasTemplates'  => $page->getPageHasTemplates()->all(),
            ]);

        }
        catch (\yii\base\Exception | \yii\db\Exception | \Exception $e){
            $transaction->rollBack();
            Yii::debug($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('errors', 'SERVERERROR'));
        }
    }

    /**
     * Sortieren der Templates
     * @param string $p Urlname der Page
     */
    public function actionSortTemplates($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $page = $this->findPage($p);
            Yii::debug('Start SortTemplates on Page '.$page->urlname.'('.$page->id.')');
            $form = new \app\models\forms\SortPageHasTemplateForm();
            return $this->render('sortTemplates', [
                 'model'             => $form,
                 'page'              => $page,
                 'pageHasTemplates'  => $page->getPageHasTemplatesWithTemplates()->all(),
            ]);

        }
        catch (\yii\base\Exception | \yii\db\Exception | \Exception $e){
            $transaction->rollBack();
            Yii::debug($e->getMessage().' on '.$e->getFile().'('.$e->getLine().')');
            //throw new ServerErrorHttpException(Yii::t('errors', 'SERVERERROR'));
        }
    }
    
    /**
     * Speichern nach Sortieren der Templates
     * @param string $p Urlname der Page
     */
    public function actionSaveSortTemplates($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $page = $this->findPage($p);
            Yii::debug('Start SortTemplates on Page '.$page->urlname.'('.$page->id.')');
            $form = new \app\models\forms\SortPageHasTemplateForm();
            if($form->load(Yii::$app->request->post()) && $form->validate()){
                if(\app\models\content\PageHasTemplate::sortTemplates($form->ids)){
                    Yii::debug('End Templates sorted');
                    $transaction->commit();
                    return $this->asJson(['saved'=>true,'errors'=>$form->getErrors()]);
                }
            }
            return $this->asJson(['saved'=>false,'errors'=>$form->getErrors()]);
        }
        catch (\yii\base\Exception | \yii\db\Exception | \Exception $e){
            $transaction->rollBack();
            Yii::debug($e->getMessage().' on '.$e->getFile().'('.$e->getLine().')');
            throw new ServerErrorHttpException(Yii::t('errors', 'SERVERERROR'));
        }
    }    
    
    /**
     * Konfiguration der Seite
     * @param ID $p Urlname der Page
     */
    public function actionConfig($p = false)
    {
        //Seite identifizieren
        $model = $this->findPage($p);
        
        try {
            //Seite identifizieren
            //$model = Page::find()->where(['id'=>$p])->one();
            if($model->load(Yii::$app->request->post())){
                //checkboxen von true auf 1 ändern
                ( in_array($model->details_admin_only, ["true", 1]) )? $model->details_admin_only = 1 : $model->details_admin_only = null;
                ( in_array($model->content_admin_only, ["true", 1]) )? $model->content_admin_only = 1 : $model->content_admin_only = null;
                
                
                if($model->validate() 
                        && $model->save()){
                    return $this->asJson(['saved'=>true]);
                }
                else {
                    //dürfte nie vorkommen, da validierung bereits durch Frontend abgesichert
                    Yii::error(json_encode($model->getErrors()), __METHOD__); 
                    return $this->asJson(['saved'=>false,'errors'=>$model->getErrors()]);
                }
            }
        
        
            return $this->render('config', [
                'model'             => $model,
            ]); 
        }
        catch (\yii\base\Exception $e){
            Yii::error($e, __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'SERVERERROR'));
        }
    }


    /**
     * Fügt eine Unterseite hinzu
     * @param ID $p Urlname der Parentseite, unter den die Seite angelegt werden soll
     * @param ID $p ID des Pagetypes, für den die Seite angelegt werden soll
     */
    public function actionAddSubPage($p)
    {
        //Seite identifizieren
        $model = $this->findPage($p);
        
        try {
            if($model->canHaveSubpages()){
                $subpage = new Page();
                //headline laden
                if($model->load(Yii::$app->request->post())){
                    // $subpage->create();
                    // TODO
                }
                $model->addSubpage($subpage);
            }
       
        }
        catch (\yii\base\Exception $e){
            Yii::error($e, __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'SERVERERROR'));
        }

        
    }


     /**
     * Delete der Seite
     * @param ID $p Urlname der Page
     */
    public function actionDelete($p)
    {
        //Seite identifizieren
        $model = $this->findPage($p);
        
        try {
            if($model && $model->deletePage()){
                return $this->redirect(['page/index']);

            }
        
        
            return $this->render('config', [
                'model'             => $model,
            ]); 
        }
        catch (\yii\base\Exception $e){
            Yii::error($e, __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'SERVERERROR'));
        }
        
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



