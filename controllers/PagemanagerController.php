<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\filters\MyAccessControl;
use app\models\role\Right;
use app\models\content\Page;
use app\models\forms\PageFilterForm;

class PagemanagerController extends Controller
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
                        'actions' => ['get-items'],
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
    public function actionIndex()
    {
        $this->layout = 'nolayout';
        try {
            $filterform = new PageFilterForm();
            $filterform->load(Yii::$app->request->post());
            $filterform->validate();
            
            
            $dataProvider = new ActiveDataProvider([
                'query' => Page::find()
                    ->filterWhere( (!$filterform->hasErrors())? ['like', 'headline', $filterform->searchstring] : [])
                    ->orderBy('created desc')
                    //->offset( 0 )
                    ->limit(100),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            
            return $this->render('index',[
                'dataProvider'  => $dataProvider,
                'filterform'    => $filterform,
            ]);

        } catch (Exception $e){
            Yii::error("Pageselect nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Pageselect konnte nicht geladen werden");
        }        
        
    }
    */
    
    /**
     * Displays Teaserlist
     *
     * @return string
     */
    public function actionGetItems()
    {
        $this->layout = 'nolayout';
        try {
            $filterform = new PageFilterForm();
            $filterform->load(Yii::$app->request->post());
            $filterform->validate();
            
            
            $dataProvider = new ActiveDataProvider([
                'query' => Page::find()
                    //->select(['page.id as id','page.created as created','user.lastname as lastname'])
                    ->filterWhere( (!$filterform->hasErrors())? ['like', 'headline', $filterform->searchstring] : [])
                    //->with(['createdBy'])
                    ->joinWith(['createdBy'])
                    ->orderBy('created desc'),
                    //->asArray(),
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
            Yii::error("Pagemanager Items konnten nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Pagemanager Items konnten nicht geladen werden");
        }        
        
    }
    

}
