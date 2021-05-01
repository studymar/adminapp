<?php

namespace app\models\role;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use app\models\user\User;
use app\models\content\Pagetype;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $name
 * @property string $created
 *
 * @property RoleAllowsPagetype[] $roleAllowsPagetypes
 * @property Pagetype[] $pagetypes
 * @property RoleHasRight[] $roleHasRights
 * @property Right[] $rights
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    public $right_ids;//wird im Detailview und updatecontroller verwendet
    public $pagetype_ids;
    public $anz;//für Filter (nicht gefüllt)
    
   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['created', 'right_ids'], 'safe'],
            ['right_ids', 'each', 'rule'=> ['integer'] ],
            ['pagetype_ids', 'each', 'rule'=> ['integer'] ],
            ['name', 'required' ],
            ['name', 'string' ,'length'=>[3, 40], 'tooShort'=>'{attribute} muss mindestens {min} Zeichen lang sein.', 'tooLong'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.' ],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Rolle',
            'created' => 'Erstellt am',
            'countUsers' => 'Anzahl User',
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleAllowsPagetypes()
    {
        return $this->hasMany(RoleAllowsPagetype::className(), ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagetypes()
    {
        return $this->hasMany(Pagetype::className(), ['id' => 'pagetype_id'])->viaTable('role_allows_pagetype', ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasRights()
    {
        return $this->hasMany(RoleHasRight::className(), ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRights()
    {
        return $this->hasMany(Right::className(), ['id' => 'right_id'])->select(['id','name'])->viaTable('role_has_right', ['role_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {   
        return $this->hasMany(User::className(), ['role_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return RightQuery the active query used by this AR class.
     *
    public static function find()
    {
        return new RightQuery(get_called_class());
    }

    /**
     * Gibt ein Array mit Text(Name) und Value (ID) zurück
     * @return \yii\db\ActiveQuery
     */
    public static function getAllRolesAsTextValuePair()
    {
        return Role::find()->select(['value'=>'id', 'text'=>'name'])->asArray()->all();
    }

    /**
     * @return int
     */
    public function getCountUsers()
    {   
        return $this->hasMany(User::className(), ['role_id' => 'id'])->count();
    }

    public static function countUsers($p)
    {   
        return User::find()->where(['role_id'=>$p])->count();
    }
    
    /**
     * @return boolean
     */
    public function hasRight($id)
    {
        return RoleHasRight::find()->where(['role_id' => $this->id, 'right_id'=>$id])->exists();
        //return $this->hasOne(Right::className(), ['role_id' => 'id', 'id'=>$id]);
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }    
    
    public static function create()
    {
        $role = new Role();
        $role->name = "Neue Rolle";
        if( $role->save() )
            return $role;
        return null;
    }
    
    /**
     * Löscht bisherige Rechte und speichert die neuen,übergebenen Rechte
     * @param array $rights ids der Right-Objekte
     * @return boolean
     */
    public function saveRights($rights = array()){
        //alte Zuordnung loeschen
        RoleHasRight::deleteAll(['role_id' => $this->id]);
        
        //sicherstellen, dass parameter ein array ist (Beispiel: keine Rechte = null)
        if(!is_array($rights)) 
            $rights = array();
        //neue zuordnung eintragen
        foreach($rights as $rightId){
            $newRight = new RoleHasRight();
            $newRight->right_id = $rightId;
            $newRight->role_id  = $this->id;
            $newRight->created  = new Expression('NOW()');
            if(!$newRight->save()){
                Yii::error(json_encode($newRight->getErrors())." beim Speichern der Rollenrechte");
                return false;
            }
        };
        return true;
    }

    /**
     * Löscht bisherige Rechte zum Anlegen von Pagetypes und speichert die neuen,übergebenen Rechte
     * @param array $pagetypes ids der Pagetype-Objekte
     * @return boolean
     */
    public function savePagetypes($pagetypes = array()){
        //alte Zuordnung loeschen
        RoleAllowsPagetype::deleteAll(['role_id' => $this->id]);
        
        //sicherstellen, dass parameter ein array ist (Beispiel: keine Rechte = null)
        if(!is_array($pagetypes)) 
            $pagetypes = array();
        //neue zuordnung eintragen
        foreach($pagetypes as $pagetypeId){
            $newPT = new RoleAllowsPagetype();
            $newPT->pagetype_id = $pagetypeId;
            $newPT->role_id  = $this->id;
            $newPT->created  = new Expression('NOW()');
            if(!$newPT->save()){
                Yii::error(json_encode($newPT->getErrors())." beim Speichern der Rollenrechte zu Pagetypes");
                return false;
            }
        };
        return true;
    }
    
    public function search(){
        $search = Role::find();
        if($this->name != null)
            $search->andWhere(['like','name',$this->name]);
        return $search;
    }    
    
}
