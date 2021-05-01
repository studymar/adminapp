<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\filters\MyAccessControl;
use app\models\user\User;
use app\models\organisation\Organisation;
use app\models\role\Role;
use app\models\role\Right;
use app\models\role\Rightgroup;
use app\models\role\RoleHasRight;
use yii\helpers\ArrayHelper;



class RolemanagerController extends Controller
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
                //'except' => ['create', 'update'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['index','update','delete','create','get-rights-of-role'],
                        'roles' => [Right::USERVERWALTUNG],
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
     * Zeigt die Liste
     */
    public function actionIndex()
    {
        try {
            $topError = null;
            
            $model = new Role();
            $model->load(Yii::$app->request->post(),'Filter');        
            
            $dataProvider = new ActiveDataProvider([
                'query' => $model->search(),
                'pagination' => [
                    'pageSize' => 50,
                ],
            ]);

        } catch (Exception $e) {
            Yii::error('Rolemanager - Users cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }
        
        return $this->render('index', [
            'model'             => $model,
            'dataProvider'      => $dataProvider,
            'topError'          => $topError,
        ]);
        
    }

    /**
     * Ändern einer Rolle
     * @param int $p ID der Rolle
     */
    public function actionUpdate($p)
    {
        try {
            $topSuccess = null;
            $topError   = null;

            $model      = Role::find()->where('id = '.$p)->one();
            if($model){
                if($model->load(Yii::$app->request->post()) && $model->validate()){
                    $model->save();
                    $topSuccess = Yii::t('errors', 'DATASAVINGSUCCESS');;

                    //alte Rechte löschen
                    RoleHasRight::deleteAll(['role_id'=>$model->id]);
                    //neue Rechte hinzufügen
                    $items = Yii::$app->request->post('RoleHasRights', []);
                    if(!$model->saveRights($items)){
                        Yii::error('Rolemanager - Role edit rights cannot be saved. Rights: '.json_encode($items), __METHOD__);
                        throw new yii\web\UnprocessableEntityHttpException(Yii::t('errors', 'DATASAVINGERROR'));
                    }
                }
            }
            else {
                Yii::error('Rolemanager - Role edit cannot be saved. Unbekanntes Model: '.$p, __METHOD__);
                throw new yii\web\UnprocessableEntityHttpException(Yii::t('errors', 'DATASAVINGERROR'));
            }
            
            $allRoles = Role::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();
            $allRights = Right::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();
            $allRightGroups = RightGroup::find()
            ->orderBy('sort asc')
            ->all();
            
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Rolemanager - Error while editimg Role: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATADELETINGERROR'));
        } catch (Exception $e) {
            Yii::error('Rolemanager - Role edit cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }
        
        return $this->render('update', [
            'model'             => $model,
            'allRoles'          => $allRoles,
            'allRightGroups'    => $allRightGroups,
            'topSuccess'        => $topSuccess,
            'topError'          => $topError,
            'errors'            => $model->getErrors(),
        ]);
        
    }

    
    /**
     * Ändern einer Rolle
     */
    public function actionCreate()
    {
        try {
            Role::create();
            return $this->redirect(['rolemanager/index']);
            
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Rolemanager - Error while creating Role: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        } catch (Exception $e) {
            Yii::error('Rolemanager - Role cannot be created: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATASAVINGERROR');
        }
    }

    /**
     * Löschen einer Rolle
     * @param int $id ID des Users, der gelöscht werden soll 
     */
    public function actionDelete($p)
    {
        try {
            $item = Role::find()->where('id = '.$p)->one();
            //nur zulassen, wenn 0 User
            if($item && $item->getCountUsers() > 0){
                Yii::error('Usermanager - Error while deleting Role: '.$p, __METHOD__);
                throw new \yii\web\UnprocessableEntityHttpException(Yii::t('errors', 'DATADELETINGERRORSTILLINUSE'));
            }
            $item->delete();
            return $this->redirect(['rolemanager/index']);
                        
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Rolemanager - Error while deleting Role: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATADELETINGERROR'));
        } catch (Exception $e) {
            Yii::error('Rolemanager - Error while deleting Role: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATADELETINGERROR'));
        }
        
        return $this->redirect(['rolemanager/index']);
        
    }


    /**
     * Gibt die Rechte der Rolle zurück
     * @param int $p ID der Rolle 
     */
    public function actionGetRightsOfRole($p)
    {
        $this->layout = 'nolayout';
        
        try {
            $role = Role::find()->where('id = '.$p)->one();
            $rights = [];
            foreach($role->rights as $right){
                $rights[] = $right->id;
            }
            
            $allRoles = Role::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();
            $allRights = Right::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();
            /*
            $allRightGroups = RightGroup::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();
            */
            $allRightGroups = RightGroup::find()
            ->with('rights')
            ->orderBy('sort asc')
            ->asArray()
            ->all();

            return $this->asJson([
                'allRightGroups' => $allRightGroups,
                'role' => $role,
                'rights' => $rights
            ]);
            
            
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Rolemanager - Error while loading Role: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATALOADINGERROR'));
        } catch (Exception $e) {
            Yii::error('Rolemanager - Error while loading Role: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATALOADINGERROR'));
        }
    }
    
}
