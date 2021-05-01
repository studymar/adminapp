<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\filters\MyAccessControl;
use app\models\role\Right;
use app\models\content\Page;
use app\models\forms\SearchFilterForm;
use app\models\content\DocumentList;
use app\models\content\Document;
use app\models\forms\DocumentEditForm;
use app\models\forms\DocumentUploadForm;
use yii\web\UploadedFile;
use yii\db\Exception;
use yii\web\ServerErrorHttpException;

class DocumentmanagerController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => MyAccessControl::className(),
                //'only' => ['create', 'update'],
                'except' => ['index'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['get-items','save-item','get-using-pages','delete-item','upload'],
                        'roles' => [Right::PAGE_EDIT],
                    ],
                    [
                        'allow' => true,
                        //'actions' => [],
                        'roles' => ['@'],
                    ],
                    // everything else is denied by default
                ],
            ],            
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);        
    }
    
    /**
     * GetItems
     *
     * @return string
     */
    public function actionGetItems()
    {
        $this->layout = 'nolayout';
        try {
            $filterform = new SearchFilterForm();
            $filterform->load(Yii::$app->request->post());
            $filterform->validate();
            
            $query = Document::find()
                    //->select(['page.id as id','page.created as created','user.lastname as lastname'])
                    ->filterWhere( (!$filterform->hasErrors())? ['like', 'filename', $filterform->searchstring] : [])
                    ->orFilterWhere( (!$filterform->hasErrors())? ['like', 'name', $filterform->searchstring] : [])
                    ->joinWith(['createdBy'])
                    ->orderBy('created desc');
            //exclude
            if( count($filterform->exclude)>0)
                $query->andWhere( "document.id not in (".implode(',',$filterform->exclude).")");

            
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $filterform->pageSize,
                    'page'     => $filterform->pageno,
                ],
            ]);
            
            foreach($dataProvider->getModels() as &$model){
                if($model->created_by)
                    $model->created_by = $model->createdBy->getName();
            }
            return $this->asJson([
                'items' => $dataProvider->getModels(),
                'pagination' => [
                    'activePage'    => $dataProvider->getPagination()->getPage(),//aktuelle Seite
                    'lastPage'      => $dataProvider->getPagination()->getPageCount()-1,//Anzahl Seiten Gesamt
                    'count'         => $dataProvider->getCount(), //Anzahl Items auf aktueller Seite
                    'totalCount'    => $dataProvider->getTotalCount(),//Anzahl Items Gesamt
                    'pageSize'      => $dataProvider->getPagination()->getPageSize(),
                ],
                'errormessages'=> $filterform->getErrors()
            ]);

        } catch (Exception $e){
            Yii::error("Documentmanager Items konnten nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Documentmanager Items konnten nicht geladen werden");
        }        
        
    }

    /**
     * Upload
     *
     * @return string
     */
    public function actionUpload()
    {
        $this->layout = 'nolayout';
        try {
        $messageerrors = [];
        $model = new DocumentUploadForm();
        if (Yii::$app->request->isPost) {
            $model->documentFiles = UploadedFile::getInstances($model, 'documentFiles');
            if ($model->upload()) {
            }
            else $messageerrors[] = $model->getErrors (); 

        } 
        return $this->asJson([
            'messageerrors' => $messageerrors
        ]);
            
            
        } catch (Exception $e){
            Yii::error("Documentmanager - Upload error: ".$e,__METHOD__);
            throw new ServerErrorHttpException("Documentmanager - Upload error");
        }        
    }    
    
    /**
     * SaveItem
     *
     * @return string
     */
    public function actionSaveItem($p)
    {
        $this->layout = 'nolayout';
        try {
            $saved = false;
            $messageerrors = [];
            $model = new DocumentEditForm();
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $item = Document::find()->where('id = '.$p)->one();
                    if($item){
                        $item = $model->map($item);
                        $item->save();
                        if($item->hasErrors()){
                            $messageerrors[] = array_merge($model->getErrors(), $item->getErrors());
                        }
                        else $saved = true;
                    } else {
                        throw new \yii\web\NotFoundHttpException("Uploadedimage: ".$p);                        
                    }
                }
                else $messageerrors[] = $model->getErrors (); 

            }
            return $this->asJson([
                'saved' => $saved,
                'messageerrors' => $messageerrors
            ]);
            
            
        } catch (\yii\db\Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Documenteditor - Save error");
        } catch (\Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Documenteditor - Save error");
        } catch (Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Documenteditor - Save error");
        }        
    }    
    
    /**
     * GetUsingItems
     *
     * @return string
     */
    public function actionGetUsingPages($p)
    {
        $this->layout = 'nolayout';
        try {
            $pages = [];
            $item = Document::find()->where('id = '.$p)->one();
            $items = $item->getDocumentItems()->all();
            //zu jedem imageItem die page finden
            foreach($items as $documentItem){
                $documentList  = $documentItem->getDocumentList()->all();
                $teasers    = $documentList[0]->getTeasers()->all(); 
                if(!empty($teasers)) {
                    $teaserlist = $teasers[0]->getTeaserlist()->one();
                    $pageHasTemplate = $teaserlist->getPageHasTemplate()->one();
                    $page = $pageHasTemplate->getPage()->asArray()->one();
                    //page in array ablegen
                    $pages[] = $page;
                }
            }
            
            //array ausgeben
            return $this->asJson(
                    $pages
            );
            
        } catch (\yii\db\Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Documenteditor - Save error");
        } catch (\Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Documenteditor - Save error");
        } catch (Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Documenteditor - Save error");
        }        
    }

    /**
     * DeleteItem
     *
     * @return string
     */
    public function actionDeleteItem($p)
    {
        $this->layout = 'nolayout';
        try {
            $item = Document::find()
                    ->where('document.id='.$p)
                    ->one();
            if( $item->deleteItem() )
                $success = true;
            else $success = false;
                        
            return $this->asJson([
                'success' => $success,
                'errormessages' => $item->getErrors()
            ]);

        } catch (Exception $e){
            Yii::error("Documentmanager - Item konnte nicht gelöscht werden: ".$e,__METHOD__);
            throw new ServerErrorHttpException("Documentmanager - Item konnte nicht gelöscht werden");
        }        
        
    }
    
    
}
