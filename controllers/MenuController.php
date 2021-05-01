<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\filters\MyAccessControl;
use app\models\role\Right;
use app\models\content\navigation\Navigation;
use app\models\content\navigation\Subnavigation;
use app\models\content\Release;
use yii\data\ActiveDataProvider;
use app\models\forms\PagetypeSelectForm;
use yii\web\ServerErrorHttpException;

class MenuController extends Controller
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
                        //'actions' => ['index','update','delete'],
                        'roles' => [Right::NAVIGATION],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied by default
                ],
            ],            
        ];
    }

    /**
     * Displays Main Menu.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'nolayout';
        
        try {
            $menu = Navigation::getNavigationTree();
            return $this->render('index',['menu'=>$menu]);

        } catch (Exception $e){
            Yii::error("Hauptmenue konnte nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Hauptmenue konnte nicht geladen werden");
        }        
        
    }
    
    /**
     * Bearbeitung aller auch nicht freigegebenen und nicht aktiven Navigationsitems 
     * zur Anzeige im Hauptmenü im Menüpunkt Navigation
     * @return array
     */
    public function actionNavigationmanager()
    {
        try {
            $model        = new Navigation();
            $dataProvider = new ActiveDataProvider([
                'query' => Navigation::find()
                    ->select([
                        'navigation.id',
                        'navigation.name',
                        'navigation.sort',
                        'navigation.path',
                        'navigation.page_id',
                        'navigation.release_id',
                        ])
                    ->joinWith('release')
                    ->with('subnavigations','subnavigationsActive.release')
                    //->where(['release.is_released'=>1])
                    //->andWhere('release.from_date <= NOW() OR release.from_date IS NULL')
                    //->andWhere('release.to_date >= NOW() OR release.to_date IS NULL')
                    ->orderBy('sort asc'),
                    //->asArray()
                'pagination' => [
                    'pageSize' => 50,
                ],
            ]);
            return $this->render('navigationmanager',[
                'dataProvider' => $dataProvider,
                'model'        => $model
            ]);

        } catch (Exception $e){
            Yii::error("Hauptmenue konnte nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Hauptmenue konnte nicht geladen werden");
        }
        
        return $ret;
    }

    /**
     * Bearbeitung aller auch nicht freigegebenen und nicht aktiven Subnavigationsitems 
     * zur Anzeige im Hauptmenü
     * @param int $p ID des Parent, dessen Submenu bearbeitet werden soll
     * @return array
     */
    public function actionSubnavigationmanager($p)
    {
        try {
            $model        = new Navigation();
            $navigation = Navigation::find()->where(['id'=>$p])->one();
            $dataProvider = new ActiveDataProvider([
                'query' => Subnavigation::find()
                    ->select([
                        'subnavigation.id',
                        'subnavigation.navigation_id',
                        'subnavigation.name',
                        'subnavigation.sort',
                        'subnavigation.path',
                        'subnavigation.page_id',
                        'subnavigation.release_id',
                        ])
                    ->joinWith('release')
                    ->where('navigation_id = '.$p)
                    //->with('subnavigations','subnavigationsActive.release')
                    //->where(['release.is_released'=>1])
                    //->andWhere('release.from_date <= NOW() OR release.from_date IS NULL')
                    //->andWhere('release.to_date >= NOW() OR release.to_date IS NULL')
                    ->orderBy('sort asc'),
                    //->asArray()
                'pagination' => [
                    'pageSize' => 50,
                ],
            ]);
            return $this->render('navigationmanager',[
                'dataProvider'  => $dataProvider, 
                'navigation'    => $navigation,
                'model'        => $model
            ]);

        } catch (Exception $e){
            Yii::error("Submenue konnte nicht geladen werden",__METHOD__);
            throw new ServerErrorHttpException("Submenue konnte nicht geladen werden");
        }
        
        return $ret;
    }


    
    /**
     * Ändern eines Items
     * @param int $p ID des Items, welches geändert werden soll
     * @param int $p2 ID des Navigation, dessen Submenuliste bearbeitet wird (für zurück-button)
     */
    public function actionUpdate($p, $p2 = false)
    {
        try {
            $topSuccess = null;
            $topError   = null;

            if(!$p2){
                $model      = Navigation::find()->where('id = '.$p)->one();
            } else {
                $model      = Subnavigation::find()->where('id = '.$p)->one();
            }
            if($model){
                if($model->load(Yii::$app->request->post()) && $model->validate()){
                    $model->save();
                    $model->release->updateFromRequest();
                    $topSuccess = Yii::t('errors', 'DATASAVINGSUCCESS');
                }
            }
            else {
                Yii::error(($p2)?'Sub':''.'Navigationmanager - Update cannot be saved. Unbekanntes Model: '.$p, __METHOD__);
                throw new yii\web\UnprocessableEntityHttpException(Yii::t('errors', 'DATASAVINGERROR'));
            }

        } catch (\yii\db\IntegrityException $e) {
            Yii::error(($p2)?'Sub':''.'Navigationmanager - Error while updating: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATADELETINGERROR'));
        } catch (Exception $e) {
            Yii::error(($p2)?'Sub':''.'Navigationmanager - update cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }
        
        return $this->render('update', [
            'model'             => $model,
            'topSuccess'        => $topSuccess,
            'topError'          => $topError,
            'errors'            => $model->getErrors(),
            'p2'                => $p2,
        ]);
        
    }

    
    /**
     * Speichern einer Navigationseintrags
     * @return string
     */
    public function actionSaveItem($p)
    {
        try {
            /******* Begin Speichern ************/
            // speichern, wenn daten übergeben
            $ret = array();
            $model          = Navigation::find()->where(['id'=>$p])->one();

            //wenn Objekt gefüllt..
            if ($model->load(Yii::$app->request->post())
                ) {
                //und objekt validiert...
                if($model->validate() ){
                    //...dann speichern
                    if($model->save()){
                        if($model->release->load(Yii::$app->request->post()) && $model->release->save())
                            $ret['saved'] = true;
                        else {
                            throw new UnprocessableEntityHttpException(json_encode($model->getErrors()));
                        }
                    }
                    else {
                        throw new UnprocessableEntityHttpException(json_encode($model->getErrors()));
                    }
                }
            }
            /******* End Speichern ************/
            
            // errormessages setzen
            $ret['errormessages'] = $model->getErrors();

        } catch (UnprocessableEntityHttpException $e){
            $ret['errormessages']['topError'] = ["Ein Fehler ist beim speichern aufgetreten, bitte versuchen Sie es später erneut."];
            Yii::error($e,__METHOD__);
        } catch (Exception $e){
            $ret['errormessages']['topError'] = ["Navigationspunkt nicht gefunden."];
            // throw new ServerErrorHttpException("Cannot find Useraddress for User.");
        }
        
        return $ret;
    }

    /**
     * Erstellen eines Menu-Eintrags incl. Seitenerstellung
     */
    public function actionCreateMenuItem()
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $topError   = null;

            $model      = new Navigation();
            $newRelease = new Release();
            $pagetype   = new PagetypeSelectForm();
            if($model->load(Yii::$app->request->post()) && $model->validate()
                    && $newRelease->load(Yii::$app->request->post()) && $newRelease->validate()
                    && $pagetype->load(Yii::$app->request->post()) && $pagetype->validate()
                    ){
                if($model->createNavigationItem($newRelease, $pagetype->pagetype_id)){
                    $transaction->commit();
                    return $this->redirect(['menu/navigationmanager']);
                }
            }

        } catch (\yii\db\IntegrityException | Exception $e ) {
            $transaction->rollBack();
            Yii::error('Navigationmanager - Error while creating: '.$e, __METHOD__);
            $topError = "Ohh, es ist etwas schief gelaufen, die Seite konnte nicht erstellt werden.";
        }
        
        return $this->render('create', [
            'model'             => $model,
            'newRelease'        => $newRelease,
            'pagetype'          => $pagetype,
            'topError'          => $topError,
            'errors'            => $model->getErrors(),
        ]);
        
    }
    
    /**
     * Erstellen eines Submenu-Eintrags incl. Seitenerstellung
     * @param int $p ID des Parent-Navigation-Items
     */
    public function actionCreateSubmenuItem($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $topError   = null;

            $model = new Subnavigation($p);
            $newRelease = new Release();
            $pagetype   = new PagetypeSelectForm();
            if($model->load(Yii::$app->request->post()) && $model->validate()
                    && $newRelease->load(Yii::$app->request->post()) && $newRelease->validate()
                    && $pagetype->load(Yii::$app->request->post()) && $pagetype->validate()
                    ){
                if($model->createNavigationItem($newRelease, $pagetype->pagetype_id)){
                    $transaction->commit();
                    return $this->redirect(['menu/subnavigationmanager', 'p'=>$p]);
                }
            }
        

        } catch (\yii\db\IntegrityException | Exception $e ) {
            $transaction->rollBack();
            Yii::error('Subnavigationmanager - Error while creating: '.$e, __METHOD__);
            $topError = "Ohh, es ist etwas schief gelaufen, die Seite konnte nicht erstellt werden.";
        }

        return $this->render('create', [
            'model'             => $model,
            'newRelease'        => $newRelease,
            'pagetype'          => $pagetype,
            'topError'          => $topError,
            'errors'            => $model->getErrors(),
            'p'                 => $p,
        ]);
        
    }
    
    
    /**
     * Erstellen eines Extern-Menu-Eintrags
     */
    public function actionCreateExternMenuItem()
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $topError   = null;

            $model      = new Navigation();
            $model->scenario = "extern";
            $newRelease = new Release();
            if($model->load(Yii::$app->request->post()) && $model->validate()
                    && $newRelease->load(Yii::$app->request->post()) && $newRelease->validate()
                    ){
                if($model->createNavigationItemAsLink($newRelease)){
                    $transaction->commit();
                    return $this->redirect(['menu/navigationmanager']);
                }
            }

        } catch (\yii\db\IntegrityException | Exception $e ) {
            $transaction->rollBack();
            Yii::error('Navigationmanager - Error while creating: '.$e, __METHOD__);
            $topError = "Ohh, es ist etwas schief gelaufen, die Seite konnte nicht erstellt werden.";
        }
        
        return $this->render('createExtern', [
            'model'             => $model,
            'newRelease'        => $newRelease,
            'topError'          => $topError,
            'errors'            => $model->getErrors(),
        ]);
        
    }

    /**
     * Erstellen eines Extern-Submenu-Eintrags
     */
    public function actionCreateExternSubmenuItem($p)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $topError   = null;

            $model      = new Subnavigation($p);
            $model->scenario = "extern";
            $newRelease = new Release();
            if($model->load(Yii::$app->request->post()) && $model->validate()
                    && $newRelease->load(Yii::$app->request->post()) && $newRelease->validate()
                    ){
                if($model->createNavigationItemAsLink($newRelease)){
                    $transaction->commit();
                    return $this->redirect(['menu/subnavigationmanager','p'=>$p]);
                }
            }

        } catch (\yii\db\IntegrityException | Exception $e ) {
            $transaction->rollBack();
            Yii::error('Navigationmanager - Error while creating: '.$e, __METHOD__);
            $topError = "Ohh, es ist etwas schief gelaufen, die Seite konnte nicht erstellt werden.";
        }
        
        return $this->render('createExtern', [
            'model'             => $model,
            'newRelease'        => $newRelease,
            'topError'          => $topError,
            'errors'            => $model->getErrors(),
        ]);
        
    }
    
    
    /**
     * Anlegen eines Navigationseintrags
     * @param int $p [optional] p ist ID des Navigation, unterhalb dessen ein 
     * submenupunkt angelegt werden soll. Ohne p wird ein Hauptmenupunkt angelegt
     * @return string
     *
    public function actionCreateItem($p = false)
    {
        try {
            /******* Begin Speichern ************
            // speichern, wenn daten übergeben
            $ret = array();
            if($p){
                $model          = new Subnavigation();
                $model->navigation_id = $p;
            }
            else
                $model          = new Navigation();

            ($p)? $model->createEmptyItem($p) : $model->createEmptyItem();
            return ($p)? $this->redirect(['menu/subnavigationmanager', 'p'=>$p]) : $this->redirect(['menu/navigationmanager']);
            
            /******* End Speichern ************
            
            // errormessages setzen
            $ret['errormessages'] = $model->getErrors();

        } catch (UnprocessableEntityHttpException $e){
            $ret['errormessages']['topError'] = ["Ein Fehler ist beim speichern aufgetreten, bitte versuchen Sie es später erneut."];
            Yii::error($e,__METHOD__);
        } catch (Exception $e){
            $ret['errormessages']['topError'] = ["Navigationspunkt nicht gefunden."];
            // throw new ServerErrorHttpException("Cannot find Useraddress for User.");
        }
        
        return $ret;
    }

    /**
     * Sortiert ein Item eine Position höher in der Navigation
     * @param int $p ID des Eintrags der sortoert werden soll
     * @param int $p2 [optional] Wenn Submenu-Eintrag sortiert werden sol, sonst Hauptmenueintrag
     * @return string
     */
    public function actionSortUp($p, $p2 = false)
    {
        try {
            /******* Begin Speichern ************/
            // speichern, wenn daten übergeben
            $ret = array();
            if(!$p2){
                $model      = Navigation::find()->where('id = '.$p)->one();
            } else {
                $model      = Subnavigation::find()->where('id = '.$p)->one();
            }

            //wenn Objekt gefüllt..
            if ($model->sort > 1)
            {
                $model->sortUp();
                return ($p2)?$this->redirect(['menu/subnavigationmanager','p'=>$p2]):$this->redirect(['menu/navigationmanager']);
            }
            /******* End Speichern ************/
            
            // errormessages setzen
            $ret['errormessages'] = $model->getErrors();

        } catch (UnprocessableEntityHttpException $e){
            $ret['errormessages']['topError'] = ["Ein Fehler ist beim speichern aufgetreten, bitte versuchen Sie es später erneut."];
            Yii::error($e,__METHOD__);
        } catch (Exception $e){
            $ret['errormessages']['topError'] = ["Navigationspunkt nicht gefunden."];
            // throw new ServerErrorHttpException("Cannot find Useraddress for User.");
        }
        
        return $ret;
    }

    /**
     * Löscht ein Item der Navigation
     * @param int $p ID des Navigation-Items
     * @param boolean $p Ob es Subnavigation-Item ist
     * @return string
     */
    public function actionDelete($p, $p2 = false)
    {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            /******* Begin Speichern ************/
            // speichern, wenn daten übergeben
            $ret = array();
            if($p2)
                $model          = Subnavigation::find()->where(['id'=>$p])->one();
            else
                $model          = Navigation::find()->where(['id'=>$p])->one();
            $release = $model->release;
            
            // wenn kein extern, sondern Seite, dann auch Seite erst merken...
            if(isset($model->page))
                $page = $model->page;
            //wenn sub, dann parent merken für redirect
            if($p2)
                $parent_id = $model->navigation_id;
            
            // menu löschen und folgepunkte runtersortieren
            $model->decreaseFollowing();
            $model->delete();
            $release->delete();
            
            //...dann, nach dem Menü, auch Seite löschen
            if(isset($model->page))
                $page->deletePage();

            $transaction->commit();

            
            if($p2)
                return $this->redirect(['menu/subnavigationmanager','p'=>$parent_id]);
            else
                return $this->redirect(['menu/navigationmanager']);

        } catch (\yii\db\IntegrityException | Exception $e){
            $transaction->rollBack();
            Yii::error("Navigationspunkt ".$model->name." (".$model->id.") konnte nicht gelöscht werden: ". $e, __METHOD__);
            throw new ServerErrorHttpException("Navigationspunkt konnte nicht gelöscht werden.");
        }
        
        return $ret;
    }
    

}
