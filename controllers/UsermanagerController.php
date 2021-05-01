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


class UsermanagerController extends Controller
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
                        'actions' => ['index','update','delete'],
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
     * Zeigt die Loginseite
     */
    public function actionIndex()
    {
        try {
            $topError = null;

            $model = new User();
            $model->load(Yii::$app->request->post(),'Filter');        
            $dataProvider = new ActiveDataProvider([
                'query' => $model->search(),
                'pagination' => [
                    'pageSize' => 50,
                ],
            ]);


        } catch (Exception $e) {
            Yii::error('Usermanager - Users cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }
        
        return $this->render('index', [
            'dataProvider'      => $dataProvider,
            'topError'          => $topError,
            'model'             => $model
        ]);
        
    }

    /**
     * Ändern eines Users
     */
    public function actionUpdate($p)
    {
        try {
            $topSuccess = null;
            $topError   = null;

            $model      = User::find()->where('id = '.$p)->one();
            if($model){
                if($model->load(Yii::$app->request->post()) && $model->validate()){
                    $model->save();
                    $topSuccess = Yii::t('errors', 'DATASAVINGSUCCESS');;
                }
            }
            else 
                throw new yii\web\ServerErrorHttpException(Yii::t('errors', 'DATALOADINGERROR'));
            
            $allOrganisations = Organisation::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();
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
            ->orderBy('sort asc')
            ->all();
            
        } catch (Exception $e) {
            Yii::error('Usermanager - User cannot be loaded: '.$e, __METHOD__);
            $topError = Yii::t('errors', 'DATALOADINGERROR');
        }

        return $this->render('update', [
            'model'             => $model,
            'allOrganisations'  => $allOrganisations,
            'allRoles'          => $allRoles,
            'allRightGroups'    => $allRightGroups,
            'topSuccess'        => $topSuccess,
            'topError'          => $topError,
            'errors'            => $model->getErrors(),
        ]);
        
    }

    /**
     * Ändern eines Users
     * @param int $id ID des Users, der gelöscht werden soll 
     */
    public function actionDelete($id)
    {
        try {
            $item = User::find()->where('id = '.$id)->one();
            $item->delete();
        } catch (\yii\db\IntegrityException $e) {
            Yii::error('Usermanager - Error while deleting User: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATADELETINGERROR'));
        } catch (Exception $e) {
            Yii::error('Usermanager - Error while deleting User: '.$e, __METHOD__);
            throw new \yii\web\BadRequestHttpException(Yii::t('errors', 'DATADELETINGERROR'));
        }
        
        return $this->redirect(['usermanager/index']);
        
    }
    
    
}
