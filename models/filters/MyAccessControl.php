<?php

namespace app\models\filters;

use Yii;
use yii\app\models\User;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\filters\MyAccessRule;

/**
 *
 * @author mwort
 */
class MyAccessControl extends AccessControl
{
    public $ruleConfig = ['class' => 'app\models\filters\MyAccessRule'];


    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        Yii::debug("Action: ".$action->id, __METHOD__);
        $user = $this->user;
        $request = Yii::$app->getRequest();
        /* @var $rule AccessRule */
        //wenn keine Regeln defninert sind, dann true
        if(empty($this->rules)) return true;
        //alle Regeln durchgehen
        //bei false = forbidden | bei true oder null = ok oder nicht relevant, weitere Regeln prüfen
        //nur wenn kein false vorkommt durchlassen
        //Yii::debug('Prüfe Rechte (Action:'.$action->id.')',__METHOD__);
        //Yii::debug('Anzahl Rules:'.count($this->rules),__METHOD__);
        foreach ($this->rules as $rule) {
            if ($rule->allows($action, $user, $request) === false) {
                Yii::debug('**Rule: deny: '.json_encode($rule),__METHOD__);
                //nicht berechtigt
                if ($this->denyCallback !== null) {//wenn callback definiert
                    //ggf. spezielle denyCallback für diese action ausführen, falls implementiert
                    call_user_func($this->denyCallback, null, $action);
                } else {
                    //sonst standard-deny
                    Yii::debug('**Rule: deny: '.json_encode($rule),__METHOD__);
                    $this->denyAccess($user);
                }
                return false;
            } 
        }
        return true; //vorher kein false, also berechtigt
        
    }
    
    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to be executed.
     *
    public function beforeAction($action)
    {
        $user = $this->user;
        $request = Yii::$app->getRequest();
        // @var $rule AccessRule
        //wenn keine Regeln defninert sind, dann true
        if(empty($this->rules)) return true;
        //sonst muss irgendeine regel getroffen werden, um action aufzurufen
        foreach ($this->rules as $rule) {
            if ($allow = $rule->allows($action, $user, $request)) {
                return true;
            } 
            // mwo:
            // auch bei null ablehnen. Damit wird allow = true einstellung im config
            // nicht mehr möglich, aber dafür mehrere Rules pro action möglich
            elseif ($allow === false || $allow === null) {
                if (isset($rule->denyCallback)) {
                    call_user_func($rule->denyCallback, $rule, $action);
                } elseif ($this->denyCallback !== null) {
                    call_user_func($this->denyCallback, $rule, $action);
                } else {
                    //$this->denyAccess($user);//fehlerseite soll erst nach evaluierung aller Regeln geworfen werden
                }

                //return false; //false soll erst nach evaluierung aller rules geworfen werden
            }
        }
        if ($this->denyCallback !== null) {
            call_user_func($this->denyCallback, null, $action);
        } else {
            $this->denyAccess($user);
        }

        return false;
    }
    
    
    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param User|false $user the current user or boolean `false` in case of detached User component
     * @throws ForbiddenHttpException if the user is already logged in or in case of detached User component.
     */
    protected function denyAccess($user)
    {
        if ($user !== false && $user->getIsGuest()) {
            Yii::$app->controller->redirect(['account/index']);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
        
    
    
}
