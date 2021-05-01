<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Exception;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;
use yii\data\ActiveDataProvider;
use app\models\Errormessages;
use app\models\filters\MyAccessControl;
use app\models\role\Right;
use app\models\content\Release;
use app\models\content\Teaserlist;
use app\models\content\Teaser;
use app\models\content\LinkItem;
use app\models\content\DocumentList;
use app\models\content\ImageList;
use app\models\helpers\DateConverter;

class TeaserlistController extends Controller
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
                        'actions' => ['get-all','sort-save'
                            ],
                        'roles' => [Right::PAGE_EDIT],
                    ],
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['@'],
                    ],
                    // everything else is denied by default
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
     * Displays Teaserlist
     *
     * @return string
     *
    public function actionIndex($p)
    {
        $this->layout = 'nolayout';
        
        try {
            $teaserlist = Teaserlist::find()->where('page_has_template_id = '.$p)->one();
            
            $items = $teaserlist->getVisibleTeasers()
                //->asArray()
                ->limit($teaserlist->maxVisibleItems)
                ->offset(0)
                ->all();
            return $this->render('index',[
                'model'=>$teaserlist,
                'items'=>$items
            ]);

        } catch (Exception $e){
            Yii::error("Seiteninhalt konnte nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Seiteninhalt konnte nicht geladen werden");
        }        
        
    }
    
    /**
     * Bearbeitung der Teaserlist
     * Zeigt edit, sort und lösch-Buttons an
     * $param int $p PageHasTemplateId
     * @return array
     *
    public function actionEdit($p)
    {
        $this->layout = 'nolayout';

        try {
            $teaserlist = Teaserlist::find()->where('page_has_template_id = '.$p)->one();
            
            $items = $teaserlist->getAllTeasers()
                //->asArray()
                ->limit($teaserlist->maxEditItems)
                ->offset(0)
                ->all();
            return $this->render('edit',[
                'items'=>$items,
                'model'=>$teaserlist,
            ]);

        } catch (Exception $e){
            Yii::error("Seiteninhalt im Editmodus konnte nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Seiteninhalt im Editmodus konnte nicht geladen werden");
        }
        
        return $ret;
    }

    
    /**
     * Ändern eines Items
     * @param int $p ID des Teasers
     *
    public function actionEditItem($p)
    {
        try {
            $topSuccess = null;
            $topError   = null;

            $model      = Teaser::find()->where('id = '.$p)->one();
            if($model){
                if($model->load(Yii::$app->request->post()) && $model->validate()){
                    $model->save();
                    $model->release->updateFromRequest();
                    $model->documentList->updateFromRequest();
                    $model->imageList->updateFromRequest();
                    if($model->link_item_id != null)
                        $model->linkItem->updateFromRequest();
                    else {
                        $linkItem = new LinkItem();
                        $linkItem->updateFromRequest();
                        if($linkItem->id !== null){
                            $model->link_item_id = $linkItem->id;
                            $model->save();
                        }
                    }
                    $topSuccess = Yii::t('errors', 'DATASAVINGSUCCESS');
                }
            }
            else {
                Yii::error('Teaser - EditItem cannot be saved. Unbekanntes Model: '.$p, __METHOD__);
                throw new UnprocessableEntityHttpException(Yii::t('errors', 'DATASAVINGERROR'));
            }

        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Teaser - Error while saving EditItem: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        } catch (Exception $e) {
            Yii::error('Teaser - EditItem cannot be saved: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATASAVINGERROR');
        }
        
        return $this->render('editItem', [
            'model'             => $model,
            'topSuccess'        => $topSuccess,
            'topError'          => $topError,
            'errors'            => $model->getErrors(),
        ]);
        
    }

    /**
     * Daten des Items abrufen
     * @param int $p ID des Teasers
     */
    public function actionGetItem($p)
    {
        $this->layout = 'nolayout';
        
        try {
            
            $model      = Teaser::find()->where('id = '.$p)->with('release','linkItem','linkItem.targetPage','imageList','imageList.uploadedimages'/*'imageList.imageItems','imageList.imageItems.uploadedimage'*/,'documentList','documentList.documents'/*'documentList.documentItems','documentList.documentItems.document'*/)->asArray()->one();
            $model['release']['from_date']  = DateConverter::convert($model['release']['from_date'], DateConverter::DATE_FORMAT_VIEW);
            $model['release']['to_date']    = DateConverter::convert($model['release']['to_date'], DateConverter::DATE_FORMAT_VIEW);
            for($i=0;$i< count($model['documentList']['documents']); $i++){
                $model['documentList']['documents'][$i]['created']  = DateConverter::convert($model['documentList']['documents'][$i]['created'], DateConverter::DATE_FORMAT_VIEW);
            }
            return $this->asJson($model);
            
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Teaser - Error while loading EditItem: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATALOADINGERROR'));
        } catch (Exception $e) {
            Yii::error('Teaser - EditItem cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }
    }
    
    
    /**
     * Speichern eines Items
     * @return string
     */
    public function actionSaveItem($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            
            /******* Begin Speichern ************/
            // speichern, wenn daten übergeben
            $model      = Teaser::find()->where('id = '.$p)->one();
            if($model){
                if($model->load(Yii::$app->request->post())){
                    if($model->validate()){
                        $model->save();
                        $model->release->updateFromRequest();
                        $model->documentList->updateFromRequest();
                        $model->imageList->updateFromRequest();
                        $model->linkItem->updateFromRequest();

                        //put validationerrors in response
                        if(!$model->hasErrors() 
                                && !$model->release->hasErrors() 
                                && !$model->linkItem->hasErrors()
                                && !$model->imageList->hasErrors() )
                            
                        $ret['saved'] = true;
                        $transaction->commit();
                    }
                    //sonst validierungsfehler, nichts tun
                }
                else {
                    Yii::error('Got Request: '.Yii::$app->request->post(),__METHOD__);
                    throw new Exception(Errormessages::$errors['MISSINGREQUESTDATAERROR']['message'], Errormessages::$errors['MISSINGREQUESTDATAERROR']['id']);
                }
            }
            else {
                Yii::error('Teaser ID: '.$p, __METHOD__);
                throw new Exception(Errormessages::$errors['TEASERNOTFOUNDERROR']['message'], Errormessages::$errors['TEASERNOTFOUNDERROR']['id']);
            }
            /******* End Speichern ************/
            
            // errormessages setzen
            if($model->hasErrors()) 
                $ret['errormessages']['item'] = $model->getErrors();
            if($model->release->hasErrors())
                $ret['errormessages']['release'] = $model->release->getErrors();
            if($model->linkItem && $model->linkItem->hasErrors()) 
                $ret['errormessages']['linkItem'] = $model->linkItem->getErrors();
            if($model->imageList && $model->imageList->hasErrors()) 
                $ret['errormessages']['imageList'] = $model->imageList->getErrors();

        } catch (\yii\db\Exception $e){
            $transaction->rollBack();
            Yii::error($e->getCode()." ".$e->getMessage(), __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        } catch (\yii\base\Exception $e){
            $transaction->rollBack();
            Yii::error($e->getCode()." ".$e->getMessage(), __METHOD__);
            throw new UnprocessableEntityHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        } 
        
        return $this->asJson($ret);
    }

    /**
     * Anlegen eines Items
     * @param int $p [optional] p ist der Liste, in der er angelegt werden soll 
     * @return string
     *
    public function actionCreateItem($p)
    {
        try {
            $topError   = null;

            /******* Begin Speichern ************
            // speichern, wenn daten übergeben
            $ret = array();
            $model          = new Teaser();
            $model->teaserlist_id   = $p;
            $model->sort            = Teaser::getMaxSort($p)+1;
            $release                = new Release();
            if($model->load(Yii::$app->request->post())){
                $release = Release::create(false);
                $model->release_id = $release->id;
                $model->document_list_id = DocumentList::create()->id;
                $model->image_list_id= ImageList::create()->id;
                $model->link_item_id = LinkItem::create()->id;
                if($model->save()){
                    $release->updateFromRequest();
                    return $this->redirect(['page/edit', 'p'=>$model->teaserlist->pageHasTemplate->page->urlname]);
                }
            }
            /******* End Speichern ************
            
            // errormessages setzen
            $ret['errormessages'] = $model->getErrors();
            return $this->render('createItem', [
                'model'             => $model,
                'release'           => $release,
                'topError'          => $topError,
                'errors'            => $model->getErrors(),
            ]);

        } catch (UnprocessableEntityHttpException $e){
            $ret['errormessages']['topError'] = ["Ein Fehler ist beim speichern aufgetreten, bitte versuchen Sie es später erneut."];
            Yii::error($e,__METHOD__);
        } catch (Exception $e){
            $ret['errormessages']['topError'] = ["Teaser konnte nicht erstellt werden."];
            // throw new ServerErrorHttpException("Cannot find Useraddress for User.");
        }        
    }

    /**
     * Sortiert ein Item eine Position höher
     * @param int $p ID des Eintrags, dass sortiert werden soll
     * @return string
     */
    public function actionSortUp($p)
    {
        try {
            /******* Begin Speichern ************/
            // speichern, wenn daten übergeben
            $ret = array();
            $model      = Teaser::find()->where('id = '.$p)->one();

            //wenn Objekt gefüllt..
            if ($model->sort < Teaser::getMaxSort($model->teaserlist_id))
            {
                $model->sortUp();
                return $this->redirect(['page/edit','p'=>$model->teaserlist->pageHasTemplate->page->urlname]);
            }
            /******* End Speichern ************/
            
            // errormessages setzen
            $ret['errormessages'] = $model->getErrors();

        } catch (UnprocessableEntityHttpException $e){
            $ret['errormessages']['topError'] = ["Ein Fehler ist beim speichern aufgetreten, bitte versuchen Sie es später erneut."];
            Yii::error($e,__METHOD__);
        } catch (Exception $e){
            $ret['errormessages']['topError'] = ["Item nicht gefunden."];
            // throw new ServerErrorHttpException("Cannot find Useraddress for User.");
        }
        
        return $ret;
    }

    /**
     * Löscht ein Item
     * @param int $p ID des zu löschendem Items
     * @return string
     */
    public function actionDeleteItem($p)
    {
        try {
            $model          = Teaser::find()->where(['id'=>$p])->one();
            $release = $model->release;
            $model->decreaseFollowing();
            $model->delete();
            $release->delete();
            return $this->redirect(['page/edit','p'=>$model->teaserlist->pageHasTemplate->page->urlname]);

        } catch (\yii\db\IntegrityException $e){
            Yii::error("Teaserlist Item (".$model->id.") konnte nicht gelöscht werden", __METHOD__);
            throw new ServerErrorHttpException("TeaserlistItem konnte nicht gelöscht werden.");
        }
        
        return $ret;
    }

    /**
     * Löscht die Verlinkung
     * @param int $p ID des Teasers
     * @return string
     */
    public function actionRemoveLink($p)
    {
        try {
            /******* Begin Speichern ************/
            $model      = Teaser::find()->where(['id'=>$p])->one();
            $model->removeLink();
            return $this->redirect(['teaserlist/edit-item','p'=>$model->id]);

        } catch (\yii\db\IntegrityException $e){
            Yii::error("Teaserlink (".$model->id.") konnte nicht gelöscht werden", __METHOD__);
            throw new ServerErrorHttpException("Teaserliink konnte nicht gelöscht werden.");
        }
        
        return $ret;
    }    

    /**
     * Löscht die Liste
     * @param int $p ID der zu löschenden Liste
     * @return redirect
     *
    public function actionDelete($p)
    {
        $model          = Teaserlist::find()->where(['id'=>$p])->one();
        $transaction    = Yii::$app->db->beginTransaction();

        try {
            $model->deleteList();
            $transaction->commit();
            
            return $this->redirect(['page/edit','p'=>$model->pageHasTemplate->page->urlname]);

        } catch (\yii\db\IntegrityException $e){
            $transaction->rollback();
            Yii::error("Teaserlist (".$model->id.") konnte nicht gelöscht werden ". json_encode($model->getErrors()), __METHOD__);
            throw new ServerErrorHttpException("Teaserlist konnte nicht gelöscht werden.");
        }
        
        return $ret;
    }    


    /**
     * Zeigt Edit-Formular für die Liste
     * @param int $p ID der  Liste
     *
    public function actionEditList($p)
    {
        $model          = Teaserlist::find()->where(['id'=>$p])->one();

        return $this->render('editList', [
            'model'             => $model,
        ]);

    }    


    /**
     * Daten der List abrufen
     * @param int $p ID der List
     * @deprecated
     *
    public function actionGetList($p)
    {
        $this->layout = 'nolayout';
        
        try {
            
            $model      = Teaserlist::find()->with('pageHasTemplate', 'pageHasTemplate.release')->where('id = '.$p)->asArray()->one();
            Yii::debug("Model: ".json_encode($model), __METHOD__);
            $model['pageHasTemplate']['release']['from_date']  = DateConverter::convert($model['pageHasTemplate']['release']['from_date'], DateConverter::DATE_FORMAT_VIEW);
            $model['pageHasTemplate']['release']['to_date']    = DateConverter::convert($model['pageHasTemplate']['release']['to_date'], DateConverter::DATE_FORMAT_VIEW);
            return $this->asJson($model);
            
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Teaserlist - Error while loading: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATALOADINGERROR'));
        } catch (Exception $e) {
            Yii::error('Teaserlist cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }
    }
    
    
    /**
     * Speichern einer List
     * @param int $p ID der List
     *
    public function actionSaveList($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            
            /******* Begin Speichern ************
            // speichern, wenn daten übergeben
            $model      = Teaserlist::find()->where('id = '.$p)->one();
            if($model){
                $model->pageHasTemplate->release->updateFromRequest();

                //put validationerrors in response
                if(!$model->hasErrors() 
                        && !$model->pageHasTemplate->release->hasErrors() 
                        )
                    $ret['saved'] = true;

                $transaction->commit();
            }
            else {
                Yii::error('Teaser ID: '.$p, __METHOD__);
                throw new Exception(Errormessages::$errors['TEASERNOTFOUNDERROR']['message'], Errormessages::$errors['TEASERNOTFOUNDERROR']['id']);
            }
            /******* End Speichern ************
            
            // errormessages setzen
            if($model->pageHasTemplate->release->hasErrors())
                $ret['errormessages']['release'] = $model->pageHasTemplate->release->getErrors();

        } catch (\yii\db\Exception $e){
            $transaction->rollBack();
            Yii::error($e->getCode()." ".$e->getMessage(), __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        } catch (\yii\base\Exception $e){
            $transaction->rollBack();
            Yii::error($e->getCode()." ".$e->getMessage(), __METHOD__);
            throw new UnprocessableEntityHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        } 
        
        return $this->asJson($ret);
    }

    /**
     * Daten der List abrufen
     * @param int $p ID der List
     *
    public function actionGetList($p)
    {
        $this->layout = 'nolayout';
        
        try {
            
            $model      = Teaserlist::find()->with('pageHasTemplate', 'pageHasTemplate.release')->where('id = '.$p)->asArray()->one();
            Yii::debug("Model: ".json_encode($model), __METHOD__);
            $model['pageHasTemplate']['release']['from_date']  = DateConverter::convert($model['pageHasTemplate']['release']['from_date'], DateConverter::DATE_FORMAT_VIEW);
            $model['pageHasTemplate']['release']['to_date']    = DateConverter::convert($model['pageHasTemplate']['release']['to_date'], DateConverter::DATE_FORMAT_VIEW);
            return $this->asJson($model);
            
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Teaserlist - Error while loading: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATALOADINGERROR'));
        } catch (Exception $e) {
            Yii::error('Teaserlist cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }
    }

     /*      * 
     * AB HIER FÜR VUE FRONTEND
     */
    
    /**
     * Items (only released) / fuer public-Ansicht benötigt
     * @param int $p PageHasTemplateId
     */
    public function actionGet($p)
    {
        $this->layout = 'nolayout';
        
        try {
            Yii::debug("Start Teaserlist Get: ".$p, __METHOD__);
            
            //laden
            $model      = Teaserlist::find()->where(['page_has_template_id'=>$p])->one();
            if(!$model)
                throw new \yii\web\NotFoundHttpException();
            Yii::debug("Model: ".json_encode($model), __METHOD__);

            //load items
            $items      = $model->getVisibleTeasers()->all();
            
            //
            $model['pageHasTemplate']['release']['from_date']  = DateConverter::convert($model['pageHasTemplate']['release']['from_date'], DateConverter::DATE_FORMAT_VIEW);
            $model['pageHasTemplate']['release']['to_date']    = DateConverter::convert($model['pageHasTemplate']['release']['to_date'], DateConverter::DATE_FORMAT_VIEW);
            
            return $this->asJson($items);
            
        } catch (\yii\db\IntegrityException | Exception $e) {
            Yii::error('Teaserlist - Error while loading: '.$e, __METHOD__);
            throw new \yii\web\ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Alle Items (incl. non released) / für edit-Ansicht benötigt
     * @param int $p PageHasTemplateId
     */
    public function actionGetAll($p)
    {
        $this->layout = 'nolayout';
        
        try {
            Yii::debug("Start Teaserlist Get: ".$p, __METHOD__);
            
            //laden
            $model      = Teaserlist::find()->where(['page_has_template_id'=>$p])->one();
            if(!$model)
                throw new \yii\web\NotFoundHttpException();
            Yii::debug("Model: ".json_encode($model->attributes), __METHOD__);

            //load items
            $items      = $model->getAllTeasers()->asArray()->all();
            
            //$model['pageHasTemplate']['release']['from_date']  = DateConverter::convert($model['pageHasTemplate']['release']['from_date'], DateConverter::DATE_FORMAT_VIEW);
            //$model['pageHasTemplate']['release']['to_date']    = DateConverter::convert($model['pageHasTemplate']['release']['to_date'], DateConverter::DATE_FORMAT_VIEW);
            
            return $this->asJson(['items'=>$items]);

        } catch (\yii\db\IntegrityException | Exception $e) {
            Yii::error('Teaserlist - Error while loading: '.$e, __METHOD__);
            //throw new \yii\web\ServerErrorHttpException($e->getMessage();
            throw new JsonException();
        }
    }

    public function actionEdit($p)
    {
        try {
            $item           = Teaser::find()->where('id = '.$p)->one();
            $imageList      = $item->getImageListWithImages()->asArray()->one();
            $documentList   = $item->getDocumentListWithDocuments()->asArray()->one();
            $linkItem       = $item->getLinkItem()->with('targetPage')->asArray()->one();
            $release        = $item->release;

        } catch (\yii\db\IntegrityException | Exception $e) {
            Yii::error('Teaser - Error loading Item: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException();
        }
        
        return $this->render('edit', [
            'item'             => $item,
            'imageList'        => $imageList,
            'documentList'     => $documentList,
            'linkItem'         => $linkItem,
            'release'          => $release
        ]);
        
    }

    

    /**
     * Speichern eines Items
     * @return string
     */
    public function actionEditSave($p)
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
            $model      = Teaser::find()->where('id = '.$p)->one();
            if($model){
                $model->updateFromRequest();
                if($model->hasErrors())
                    $ret['errormessages']['Teaser'] = $model->getErrors();
                $model->release->updateFromRequest();
                
                $model->imageList->updateFromRequest(Yii::$app->request->post('ImageList'));
                $model->documentList->updateFromRequest(Yii::$app->request->post('DocumentList'));
                $model->linkItem->updateFromRequest(Yii::$app->request->post('LinkItem'));
                
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

    public function actionCreate($p)
    {
        try {
            $model      = Teaserlist::find()->where(['page_has_template_id'=>$p])->one();

        } catch (\yii\db\IntegrityException | Exception $e) {
            Yii::error('Teaser - Error Create: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException();
        }
        
        return $this->render('create', [
            'model'             => $model,
        ]);
        
    }

    public function actionCreateSave($p)
    {
        $this->layout = 'nolayout';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            $model      = Teaserlist::find()->where(['id'=>$p])->one();
            $form       = new \app\models\forms\TeaserCreateForm();
            
            if($form->load(Yii::$app->request->post()) && $form->validate()){
                $headline   = $form->headline;
                $item       = Teaser::create($model->id, $headline);           

                $ret['saved'] = true;
                $transaction->commit();
            }
            else {
                $ret['errormessages'] = $form->getErrors();
            }
            
            return $this->asJson($ret);
 
        } catch (Exception $e) {
            /* model errors while save */
            Yii::error('Teaser - Error Create: '.$e, __METHOD__);
            $transaction->rollBack();
            if($item && $item->hasErrors()){
                $ret['errormessages'] = $model->getErrors();
            }
            $ret['saved'] = false;
            return $this->asJson($ret);
            
        } catch (\yii\db\IntegrityException $e) {
            $transaction->rollBack();
            Yii::error('Teaser - Error Create: '.$e, __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        }
        
    }

    /**
     * Speichern nach Sortieren
     * [post] array SortForm[ids] Ids in der gewuenschten Reihenfolge
     * @param int $p Id der Teaserlist
     */
    public function actionSortSave()
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            Yii::debug('Start SaveSort on Teaserlist ');
            $form = new \app\models\forms\SortForm();
            if($form->load(Yii::$app->request->post()) && $form->validate()){
                if(\app\models\content\Teaserlist::sortTeasers($form->ids)){
                    Yii::debug('End SaveSort sorted');
                    $transaction->commit();
                    return $this->asJson(['saved'=>true,'errors'=>$form->getErrors()]);
                }
            }
            return $this->asJson(['saved'=>false,'errors'=>$form->getErrors()]);
        }
        catch (\yii\base\Exception | \yii\db\Exception | \Exception $e){
            $transaction->rollBack();
            Yii::debug($e->getMessage());
            throw new ServerErrorHttpException();
        }
    }    
    
    
}
