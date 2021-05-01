<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\filters\MyAccessControl;
use app\models\role\Right;
use app\models\forms\SearchFilterForm;
use app\models\content\Uploadedimage;
use app\models\forms\UploadImageForm;
use app\models\forms\UploadedimageEditForm;
use app\models\forms\UploadedimageCropForm;
use yii\web\UploadedFile;
use yii\db\Exception;
use yii\web\ServerErrorHttpException;

class ImagemanagerController extends Controller
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
                        'actions' => [
                            'get-items',
                            'get-item',
                            'upload',
                            'save-item',
                            'get-using-pages',
                            'crop-image',
                            'delete-item',
                        ],
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
            
            $query = Uploadedimage::find()
                    //->select(['page.id as id','page.created as created','user.lastname as lastname'])
                    ->filterWhere( (!$filterform->hasErrors())? ['like', 'filename', $filterform->searchstring] : [])
                    ->orFilterWhere( (!$filterform->hasErrors())? ['like', 'name', $filterform->searchstring] : [])
                    ->joinWith(['createdBy'])
                    ->orderBy('created desc');
            //exclude
            if( count($filterform->exclude)>0)
                $query->andWhere( "uploadedimage.id not in (".implode(',',$filterform->exclude).")");

            
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
            Yii::error("Imagemanager Items konnten nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Imagemanager Items konnten nicht geladen werden");
        }        
        
    }

    /**
     * GetItem
     *
     * @return string
     */
    public function actionGetItem($p)
    {
        $this->layout = 'nolayout';
        try {
            $item = Uploadedimage::find()
                    ->where('uploadedimage.id='.$p)
                    ->joinWith(['createdBy'])
                    ->one();
                        
            if($item->created_by)
                $item->created_by = $item->createdBy->getName();
            return $this->asJson([
                'item' => $item,
            ]);

        } catch (Exception $e){
            Yii::error("Imagemanager Item konnte nicht geladen werden: ".$e,__METHOD__);
            throw new ServerErrorHttpException("Imagemanager Item konnte nicht geladen werden");
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
        $model = new UploadImageForm();
        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload()) {
                // file is uploaded successfully
                /*
                foreach($model->imageFiles as $image){
                    $item = Uploadedimage::create($image);
                    if($item->hasErrors())
                        $messageerrors[] = $item->getErrors ();
                }
                 * 
                 */
            }
            else $messageerrors[] = $model->getErrors (); 

        } 
        return $this->asJson([
            'messageerrors' => $messageerrors
        ]);
            
            
        } catch (Exception $e){
            Yii::error("Imagemanager - Upload error: ".$e,__METHOD__);
            throw new ServerErrorHttpException("Imagemanager - Upload error");
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
            $model = new UploadedimageEditForm();
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $item = Uploadedimage::find()->where('id = '.$p)->one();
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
            throw new ServerErrorHttpException("Imageeditor - Save error");
        } catch (\Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Imageeditor - Save error");
        } catch (Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Imageeditor - Save error");
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
            $item = Uploadedimage::find()->where('id = '.$p)->one();
            $items = $item->getImageItems()->all();
            //zu jedem imageItem die page finden
            foreach($items as $imageItem){
                $imageList  = $imageItem->getImageList()->all();
                if(count($imageList)>0){
                    $teasers    = $imageList[0]->getTeasers()->all(); 
                    //Page über verlinkten Teaser auslesen
                    if(!empty($teasers)) {
                        $teaserlist = $teasers[0]->getTeaserlist()->one();
                        $pageHasTemplate = $teaserlist->getPageHasTemplate()->one();
                        $page = $pageHasTemplate->getPage()->asArray()->one();
                        //page in array ablegen
                        $pages[] = $page;
                    }

                    //Seiten mit Listen mit diesem ImageItem auslesen
                    foreach($imageList as $list){
                        //wenn als Seitentemplate eingebunden
                        if($list->page_has_template_id){
                            $pageHasTemplate    = $list->getPageHasTemplate()->one();
                            //Seite herausfinden und in Array ergaenzen
                            $page = $pageHasTemplate->getPage()->asArray()->one();
                            //page in array ablegen
                            $pages[] = $page;
                        }
                    }
                }
            }
            
            //array ausgeben
            return $this->asJson(
                    $pages
            );
            
        } catch (\yii\db\Exception | \Exception | Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Imageeditor - UsingPages error");
        }        
    }

    /**
     * Crop
     *
     * @return string
     */
    public function actionCropImage($p)
    {
        $this->layout = 'nolayout';
        try {
            $model = new UploadedimageCropForm();
            if (Yii::$app->request->isPost) {
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $item  = Uploadedimage::find()->where('id = '.$p)->one();
                    $item->crop($model);
                    //array ausgeben
                    return $this->asJson([
                        'saved'=>true,
                        'item'=>$item
                    ]);
                }
                return $this->asJson(['errormessages'=> $model->getErrors(),'saved'=>false]);
            }
            throw new \yii\web\MethodNotAllowedHttpException("Not allowed.");
            
            
        } catch (\yii\db\Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Imageeditor - Crop error");
        } catch (\Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Imageeditor - Crop error".$e);
        } catch (Exception $e){
            Yii::error($e,__METHOD__);
            throw new ServerErrorHttpException("Imageeditor - Crop error");
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
            $item = Uploadedimage::find()
                    ->where('uploadedimage.id='.$p)
                    ->one();
            if( $item->deleteItem() )
                $success = true;
            else $success = false;
                        
            return $this->asJson([
                'success' => $success,
                'errormessages' => $item->getErrors()
            ]);

        } catch (Exception $e){
            Yii::error("Imagemanager - Item konnte nicht gelöscht werden: ".$e,__METHOD__);
            throw new ServerErrorHttpException("Imagemanager - Item konnte nicht gelöscht werden");
        }        
        
    }
    
    
    
}
