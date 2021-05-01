<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\models\filters;

use Yii;
use yii\filters\AccessRule;
use app\models\user\User;

/**
 * This class represents an access rule defined by the [[AccessControl]] action filter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MyAccessRule extends AccessRule
{
    
    /**
     * Checks whether the Web user is allowed to perform the specified action by this rule.
     * @param Action $action the action to be performed
     * @param User|false $user the user object or `false` in case of detached User component
     * @param Request $request
     * @return bool `true` if the user is allowed to perform this rule, `false` if the user is denied,
     */
    public function allows($action, $user, $request)
    { 
        //1.gilt Regel nur für bestimmte Actions?
        Yii::debug("Rule{ action: ".json_encode($this->actions).", roles: ".json_encode($this->roles)."}",__METHOD__);
        if ( !empty($this->actions) ) {
            //1a regel gilt nur für bestimmte actions
            //aktuelle action dabei?
            if(in_array($action->id, $this->actions, true)){
                //Ja, dann nur true, wenn benötigte Rolle/Rechte vorhanden
                //Yii::debug("Rule relevant für action ".$action->id." : true",__METHOD__);
                return $this->matchesRolesAndRights($user);
            }
            else {
                //Yii::debug("Rule relevant für action ".$action->id.": false",__METGHOD__);
                return true; //sonst immer true, weil nicht für aktuelle action relevant
            }
        }
        else {
            Yii::debug("Rule: Alle Actions", __METHOD__);
            //1b Regel gilt für alle Actions
            //benötigte Rolle/Rechte vorhanden?
            return $this->matchesRolesAndRights($user);
        }

        return false;//not reachable
    }    


    /**
     * Checks if the actual user has the right which is needed for this action
     * @param User $user the user object
     * @return bool whether the rule applies to the role
     * @throws InvalidConfigException if User component is detached
     */
    public function matchesRolesAndRights($user){
        //rollen für diese action (in Controller definiert) / wenn kein recht definiert, ist es immer erlaubt
        $items = empty($this->roles) ? [] : $this->roles;

        if ($user === false) {
            throw new InvalidConfigException('The user application component must be available to specify roles in AccessRule.');
        }
        
        //check each needed role or right
        foreach ($items as $item) {
            if ($item === '?') {
                //kein Gast, dann false
                if (!$user->getIsGuest()) {
                    return false;
                }
            } else if ($item === '@') {
                //nicht eingeloggt, dann false
                if ($user->getIsGuest()) {
                    return false;
                }
            } else {
                //spezielles Recht definiert
                //Rechte des Users pruefen
                if (!User::checkRight($item)) {//überschriebene Zeile vom Orginal
                    return false;
                }
            }
        }
        return true; // nur wenn alle Rollen/Rechte ok, dann true
        
    }
    
    /**
     * Checks whether the Web user is allowed to perform the specified action.
     * @param Action $action the action to be performed
     * @param User|false $user the user object or `false` in case of detached User component
     * @param Request $request
     * @return bool|null `true` if the user is allowed, `false` if the user is denied, `null` if the rule does not apply to the user
     *
    public function allows($action, $user, $request)
    {
        if (
            $this->checkRule($action, $user, $request)
        ) {
            return $this->allow ? true : false;
        }

        return null;
    }    

    /**
     * Checks if the actual user has the right which is needed for this action
     * @param Action $action the action
     * @param User $user the user object
     * @param Request $request
     * @return bool whether the rule applies to the role | null not relevant rule for that action
     * @throws InvalidConfigException if User component is detached
     *
    protected function checkRule($action, $user, $request)
    {
        //rollen für diese action (in Controller definiert) / wenn kein recht definiert, ist es immer erlaubt
        $items = empty($this->roles) ? [] : $this->roles;
        
        //rollen, rechte für diese rule
        $roles   = empty($this->roles) ? [] : $this->roles;
        $actions = empty($this->actions) ? [] : $this->actions;
        $bool    = null;
        
        foreach ($items as $item) {
            if ($item === '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
                else if( in_array($action->id, $this->actions, true) )
                    return false;
                else 
                    $bool = false;
            } elseif ($item === '@') {
                if (!$user->getIsGuest()) {
                    return true;
                }
                else if( in_array($action->id, $this->actions, true) )
                    return false;
                else 
                    $bool = false;
            } else {
                // mwo:
                //Rechte des Users pruefen
                if (User::checkRight($item)) {//überschriebene Zeile vom Orginal
                    return true;
                }
                else if( in_array($action->id, $this->actions, true) )
                    return false;
                else 
                    $bool = false;
            }
        }
        if(!$bool)
            return null;
        
    }
    
    /**
     * Checks if the actual user has the right which is needed for this action
     * @param User $user the user object
     * @return bool whether the rule applies to the role
     * @throws InvalidConfigException if User component is detached
     *
    protected function matchRole($user)
    {
        //rollen für diese action (in Controller definiert) / wenn kein recht definiert, ist es immer erlaubt
        $items = empty($this->roles) ? [] : $this->roles;

        if (!empty($this->permissions)) {
            $items = array_merge($items, $this->permissions);
        }

        if (empty($items)) {
            return true;
        }

        if ($user === false) {
            throw new InvalidConfigException('The user application component must be available to specify roles in AccessRule.');
        }
        foreach ($items as $item) {
            if ($item === '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif ($item === '@') {
                if (!$user->getIsGuest()) {
                    return true;
                }
            } else {
                if (!isset($roleParams)) {
                    $roleParams = $this->roleParams instanceof Closure ? call_user_func($this->roleParams, $this) : $this->roleParams;
                }
                // mwo:
                //Rechte des Users pruefen
                if (User::checkRight($item)) {//überschriebene Zeile vom Orginal
                    return true;
                }
            }
        }

        return false;
    }
*/
}
