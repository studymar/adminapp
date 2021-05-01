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
use app\models\content\Text;
use app\models\content\LinkItem;
use app\models\content\DocumentList;
use app\models\content\ImageList;
use app\models\helpers\DateConverter;

class TextController extends Controller
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
                        'actions' => ['edit','edit-item','get','save-item','sort-up','delete-item','delete'
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
     * Displays Text
     *
     * @return string
     *
    public function actionIndex($p)
    {
        $this->layout = 'nolayout';
        
        try {
            $model = Text::find()->where('page_has_template_id = '.$p)->one();
            
            return $this->render('index',[
                'model'=>$model,
            ]);

        } catch (Exception $e){
            throw new ServerErrorHttpException("Text konnte nicht geladen werden");
        }        
        
    }
    
    /**
     * Bearbeitung des Items
     * Zeigt edit, sort und lösch-Buttons an
     * $param int $p PageHasTemplateId
     * @return array
     *
    public function actionEdit($p)
    {
        $this->layout = 'nolayout';

        try {
            $model = Text::find()->where('page_has_template_id = '.$p)->one();
            
            return $this->render('edit',[
                'model'=>$model,
            ]);

        } catch (Exception $e){
            throw new ServerErrorHttpException("Text im Editmodus konnte nicht geladen werden");
        }
        
        return $ret;
    }    

    /**
     * Ändern eines Items
     * @param int $p ID des Text
     *
    public function actionEditItem($p)
    {
        try {
            $topSuccess = null;
            $topError   = null;

            $model      = Text::find()->where('id = '.$p)->one();
            /*
            if($model->pageHasTemplate->release->from_date)
                $model->pageHasTemplate->release->from_date = DateConverter::convert ($model->pageHasTemplate->release->from_date, DateConverter::DATE_FORMAT_VIEW);
            if($model->pageHasTemplate->release->to_date)
                $model->pageHasTemplate->release->to_date = DateConverter::convert ($model->pageHasTemplate->release->to_date, DateConverter::DATE_FORMAT_VIEW);
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
            *
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Text - Error while saving EditItem: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        } catch (Exception $e) {
            Yii::error('Text - EditItem cannot be saved: '.$e, __METHOD__);
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
     * Speichern eines Items
     * @return string
     *
    public function actionSaveItem($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            
            /******* Begin Speichern ************
            // speichern, wenn daten übergeben
            $model      = Text::find()->where('id = '.$p)->one();
            if($model){
                if($model->load(Yii::$app->request->post())){
                    if($model->validate()){
                        if($model->text == "") $model->text = null;
                        $model->save();
                        $model->pageHasTemplate->release->updateFromRequest();
                        $model->documentList->updateFromRequest();

                        //put validationerrors in response
                        if(!$model->hasErrors() 
                            && !$model->pageHasTemplate->release->hasErrors() 
                            )
                            
                        $ret['saved'] = true;
                        $transaction->commit();
                    }
                    //sonst validierungsfehler, nichts tun
                }
                else {
                    Yii::error('Got Request/not savable: '.Yii::$app->request->post(),__METHOD__);
                    throw new Exception(Errormessages::$errors['MISSINGREQUESTDATAERROR']['message'], Errormessages::$errors['MISSINGREQUESTDATAERROR']['id']);
                }
            }
            else {
                Yii::error('Text ID: '.$p, __METHOD__);
                throw new Exception(Errormessages::$errors['DATANOTFOUNDERROR']['message'], Errormessages::$errors['DATANOTFOUNDERROR']['id']);
            }
            /******* End Speichern ************
            
            // errormessages setzen
            if($model->hasErrors()) 
                $ret['errormessages']['item'] = $model->getErrors();
            if($model->pageHasTemplate->release->hasErrors())
                $ret['errormessages']['release'] = $model->release->getErrors();

        } catch (\yii\db\Exception | \yii\base\Exception $e){
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        }
        return $this->asJson($ret);
    }
    
    
    
    /**
     * Daten des Items abrufen
     * @param int $p ID des Item
     */
    public function actionGet($p)
    {
        $this->layout = 'nolayout';
        
        try {
            
            $model      = Text::find()->select(['id','headline','text'])->where('page_has_template_id = '.$p)->one();
            $model      = Text::find()->where('page_has_template_id = '.$p)->one();
            
            return $this->asJson(['item'=>$model->attributes]);

        } catch (Exception | \yii\db\IntegrityException $e) {
            Yii::error('Text - Get cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }
    }
    
    

    /**
     * Sortiert ein Item eine Position höher
     * @param int $p ID des Eintrags, dass sortiert werden soll
     * @return string
     *
    public function actionSortUp($p)
    {
        try {
            /******* Begin Speichern ************
            // speichern, wenn daten übergeben
            $ret = array();
            $model      = Text::find()->where('id = '.$p)->one();

            //wenn Objekt gefüllt..
            if ($model->sort < Text::getMaxSort($model->teaserlist_id))
            {
                $model->sortUp();
                return $this->redirect(['page/edit','p'=>$model->pageHasTemplate->page->urlname]);
            }
            /******* End Speichern ************
            
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
     * Löscht die Liste
     * @param int $p ID des zu löschenden Items
     * @return redirect
     */
    public function actionDelete($p)
    {
        $model          = Text::find()->where(['id'=>$p])->one();
        $transaction    = Yii::$app->db->beginTransaction();

        try {
            $model->deleteList();
            $transaction->commit();
            
            return $this->redirect(['page/edit','p'=>$model->pageHasTemplate->page->urlname]);

        } catch (\yii\db\IntegrityException $e){
            $transaction->rollback();
            Yii::error("Text (".$model->id.") konnte nicht gelöscht werden ". json_encode($model->getErrors()), __METHOD__);
            throw new ServerErrorHttpException("Text konnte nicht gelöscht werden.");
        }
        
        return $ret;
    }    
 


    /**
     * Daten abrufen
     * @param int $p ID des Text
     */
    public function actionGetFormData($p)
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
     */
    public function actionSaveFormData($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            
            /******* Begin Speichern ************/
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
            /******* End Speichern ************/
            
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


    
}
