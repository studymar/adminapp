<?php

namespace app\models\role;

use Yii;
use app\models\helpers\AuthorityMatching;

/**
 * This is the model class for table "right".
 *
 * @property integer $id
 * @property string $name
 * @property string $created
 * @property string $info
 * @property integer $rightgroup_id
 *
 * @property Rightgroup $rightgroup
 * @property RoleHasRight[] $roleHasRights
 * @property Role[] $roles
 */
class Right extends \yii\db\ActiveRecord
{
    
  //Rights
    //Rechte zum USER;
    //const GROUPRIGHTS_USER      = 1;//Gruppe für Anzeige bei Rollenedit
    const MEINE_DATEN           = 001;

    const USERVERWALTUNG        = 100;
    const NAVIGATION            = 101;
   
    //Rechte zum CMS;
    //const GROUPRIGHTS_PAGES         = 2;//Gruppe für Anzeige bei Rollenedit
    const PAGE_EDIT                 = 200;//hiermit: Seiten editieren
    const PAGE_ADMIN_MENU           = 201;//hiermit: Page edit
    const PAGE_ADMIN_ASADMIN        = 202;//hiermit: Darf auch Seiten mit "Only Admin" konfigurieren
    const PAGE_CONFIG               = 203;//hiermit: Seite konfigurieren
    const PAGE_ADD_CONTENT          = 204;//hiermit: Inhalt zur Seite hinzufügen/entfernen
    const PAGE_DELETE               = 205;//hiermit: Seiten löschen (nur möglich, wenn kein Parent und keine Subs vorhanden)


    //Rechte zu Vereinen;
    const MAIL_VERSENDEN             = 0;
    const VEREINSMELDUNG_CONFIGURE   = 0;
    const VEREINSMELDUNG_ADMIN       = 0;
    const VEREIN_ADMINISTRIEREN      = 0;   
     
    //Rechte zu Voting;
    //const GROUPRIGHTS_VOTINGS        = 3;
    const VOTINGADMIN                = 301;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'right';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created', 'rightgroup_id'], 'required'],
            [['name', 'info'], 'string'],
            [['created'], 'safe'],
            [['rightgroup_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created' => 'Created',
            'info' => 'Info',
            'rightgroup_id' => 'Rightgroup ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRightgroup()
    {
        return $this->hasOne(Rightgroup::className(), ['id' => 'rightgroup_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasRights()
    {
        return $this->hasMany(RoleHasRight::className(), ['right_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['id' => 'role_id'])->viaTable('role_has_right', ['right_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return RightQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RightQuery(get_called_class());
    }
    
    /**
     * Holt zum Path (action) und Method (GET) gematchtes Recht
     * @param String $method
     * @param String $path
     * @return int | boolean (false), wenn kein Recht eingetragen
     */
    public function getRightIdForMatchedAction($method, $path){
        try {
            $matchingData = AuthorityMatching::matchedActions();
            if(array_key_exists($path, $matchingData)){
                $match = $matchingData[$path];
                if(array_key_exists($method, $match)){
                    return $match[$method];
                }
                //path definiert, aber nicht die method, also ablehnen
                //ablehnen: eine rightId zurueckgeben, die nicht vorhanden/nirgends zugeordnet ist
                Yii::info("AuthorityMatching: Nicht eingetragene Method verwendet. Method:".$method." - Path:".$path,__METHOD__);
                return '99999';//irgendeine ID, die es nicht gibt, also Verwendung nict zugelassen
            }
            //keine Einscränkung eingetragen, also zugelassen
            return false;//kein Matching gefunden
        } catch (ErrorException $e){
            Yii::error("Fehler beim Berechnen Matching Path/Method zu RightId. Method:".$method."/Path:".$path,__METHOD__);
            throw new ServerErrorHttpException("Entschuldigung, es ist ein Server Fehler aufgetreten! Das hätte nicht passieren sollen!");
        }
    }
    
}
