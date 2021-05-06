<?php

namespace app\models\user;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;
use app\models\user\Useraddress;
use app\models\role\Role;
use app\models\role\Right;
use app\models\organisation\Organisation;
use app\models\mail\MailCollection;
use app\models\helpers\DateConverter;
use app\models\helpers\DateCalculator;

/**
 * This is the model class for table "User".
 *
 * @property integer $id
 * @property integer $isvalidated
 * @property string $validationtoken
 * @property string $authkey
 * @property string $lastname
 * @property string $firstname
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $lastlogindate
 * @property string $created
 * @property string $updated
 * @property string $deleted
 * @property string $locked
 * @property integer $role_id
 * @property integer $organisation_id
 * @property integer $useraddress_id
 * 
 * @property Document[] $documents
 * @property DocumentItemDownload[] $documentItemDownloads
 * @property DocumentItemTeaser[] $documentItemTeasers
 * @property DocumentItemText[] $documentItemTexts
 * @property Download[] $downloads
 * @property Downloadlist[] $downloadlists
 * @property Fotogalerie[] $fotogaleries
 * @property HistoryDownload[] $historyDownloads
 * @property HistoryDownloadlist[] $historyDownloadlists
 * @property HistoryFotogalerie[] $historyFotogaleries
 * @property HistoryLinklist[] $historyLinklists
 * @property HistoryLinklistColumn[] $historyLinklistColumns
 * @property HistoryLinklistColumnItem[] $historyLinklistColumnItems
 * @property HistoryPageinstance[] $historyPageinstances
 * @property HistoryTeaser[] $historyTeasers
 * @property HistoryTermin[] $historyTermins
 * @property HistoryTerminuebersicht[] $historyTerminuebersichts
 * @property HistoryText[] $historyTexts
 * @property ImageItemFotogalerie[] $imageItemFotogaleries
 * @property ImageItemTeaser[] $imageItemTeasers
 * @property ImageItemText[] $imageItemTexts
 * @property Linklist[] $linklists
 * @property LinklistColumn[] $linklistColumns
 * @property LinklistColumnItem[] $linklistColumnItems
 * @property Pageinstance[] $pageinstances
 * @property Submenu[] $submenus
 * @property Teaser[] $teasers
 * @property Termin[] $termins
 * @property Terminbereich[] $terminbereiches
 * @property Terminuebersicht[] $terminuebersichts
 * @property TerminuebersichtHasTerminbereiche[] $terminuebersichtHasTerminbereiches
 * @property Text[] $texts
 * @property Uploadedimage[] $uploadedimages
 * @property Organisation $organisation
 * @property Role $role
 * @property Useraddress $useraddress
 * 
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    //only for registrationform
    public $password_repeat; // wird bei registrierung verwendet
    public $organisationname; //wird in userlist/index verwendet
    public $rolename; //wird in userlist/index verwendet
    public $agree;//checkbox bei registrate

    protected static $user;

        /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email','lastname','firstname','organisation_id'], 'required', 'on'=>'meine-daten'],
            [['email','lastname','firstname','organisation_id','role_id'], 'required', 'on'=>'usermanager-edit'],
            [['email','password','password_repeat'], 'required', 'on'=>'passwordchange'],
            [['username','email','lastname','firstname','organisation_id','password','password_repeat'], 'required', 'on'=>'registration'],
            [['agree'], 'required', 'on'=>'registration','message'=>'Bitte stimmen Sie der Datenspeicherung zu.'],

            [['organisationname','rolename'], 'match', 'pattern'=>'/^([a-zA-Z0-9_])/','message'=>'{attribute} enthält unerlaubte Zeichen.'],
            [['organisationname','rolename'], 'string', 'max' => 256],            

            [['isvalidated', 'role_id', 'organisation_id'], 'integer'],
            [['username','authkey', 'lastname', 'firstname', 'password','password_repeat'], 'string'],
            [['username'], 'unique', 'message'=>'Bitte verwenden Sie einen anderen Username','on'=>'registration'],
            [['username'], 'validateUsername', 'message'=>'Bitte verwenden Sie einen anderen Username','on'=>'registration'],
             ['username', 'match', 'pattern'=>'/^([a-zA-Z0-9_])/','message'=>'{attribute} enthält unerlaubte Zeichen.'],
            [['username'], 'string', 'max' => 256],
            /*
             * [['email'], 'unique', 'message'=>'Bitte verwenden Sie eine andere Email'],
             */
            [['lastlogindate', 'created', 'updated', 'deleted','locked'], 'safe'],
            [['validationtoken', 'email'], 'string', 'max' => 100],
            [['email'], 'email','message'=>'Bitte tragen Sie Ihre reale Emailadresse ein.'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message'=>'Die Passwörter stimmen nicht überein.', 'on'=>'registration'],
            [['validationtoken'], 'unique'],
            [['organisation_id'], 'exist', 'targetClass' => '\app\models\organisation\Organisation', 'targetAttribute' => 'id'],
        ];
    }

    public function validate($attributeNames = NULL, $clearErrors = true){
        $this->username = $this->email;
        return parent::validate();
    }
    
    public function validateUsername($attribute, $params, $validator)
    {
        if (User::findByUsername($this->$attribute) !== null) {
            $this->addError($attribute, $validator->message);
        }
    }    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'isvalidated' => 'Isvalidated',
            'validationtoken' => 'Validationtoken',
            'authkey' => 'Auth Key',
            'lastname' => 'Nachname',
            'firstname' => 'Vorname',
            'email' => 'Email',
            'password' => 'Passwort',
            'password_repeat' => 'Passwort (Wdh.)',
            'lastlogindate' => 'Letzter Login',
            'created' => 'Erstellt',
            'deleted' => 'Deleted',
            'locked' => 'Gesperrt',
            'role_id' => 'Rolle',
            'organisation_id' => 'Verein',
            'organisationname' => 'Verein',
            'rolename' => 'Rolle',
        ];
    }

    public function getAuthKey() {
        return $this->authkey;
    }

    public function getId() {
        return $this->id;
    }

    public function validateAuthKey($authKey) {
        return $this->authkey === $authKey;
    }

    /**
     * Wird bei jedem Aufruf aufgerufen, User gefunden, dann Update lastlogin
     * @param type $id
     * @return type
     */
    public static function findIdentity($id) {
        $user = self::findOne($id);
        return $user;
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        //throw new \yii\base\NotSupportedException;
        return static::findOne(['authkey' => $token, 'deleted'=>null]);
    }
    public static function findIdentityByAuthKey($token) {
        //throw new \yii\base\NotSupportedException;
        return static::findOne(['authkey' => $token, 'deleted'=>null]);
    }

    public function validatePassword($password) {
        //$hash = password_hash($password, PASSWORD_DEFAULT);
        //Yii::info('ValidatePassword: '.$hash.' = '.$this->password);
        //throw new ServerErrorHttpException("Internal Server Error. Please try again later.");
        //return $this->password === sha1($password);
        return password_verify($password, $this->password);
    }
    public static function findByEmail($email) {
        return self::findOne(['email' => $email]);
    }
    public static function findByUsername($username) {
        return self::findOne(['username' => $username]);
    }
    public static function findByValidationtoken($token) {
        return self::findOne(['validationtoken' => $token]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentItemDownloads()
    {
        return $this->hasMany(DocumentItemDownload::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentItemTeasers()
    {
        return $this->hasMany(DocumentItemTeaser::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentItemTexts()
    {
        return $this->hasMany(DocumentItemText::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDownloads()
    {
        return $this->hasMany(Download::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDownloadlists()
    {
        return $this->hasMany(Downloadlist::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFotogaleries()
    {
        return $this->hasMany(Fotogalerie::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryDownloads()
    {
        return $this->hasMany(HistoryDownload::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryDownloadlists()
    {
        return $this->hasMany(HistoryDownloadlist::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryFotogaleries()
    {
        return $this->hasMany(HistoryFotogalerie::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryLinklists()
    {
        return $this->hasMany(HistoryLinklist::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryLinklistColumns()
    {
        return $this->hasMany(HistoryLinklistColumn::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryLinklistColumnItems()
    {
        return $this->hasMany(HistoryLinklistColumnItem::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryPageinstances()
    {
        return $this->hasMany(HistoryPageinstance::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryTeasers()
    {
        return $this->hasMany(HistoryTeaser::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryTermins()
    {
        return $this->hasMany(HistoryTermin::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryTerminuebersichts()
    {
        return $this->hasMany(HistoryTerminuebersicht::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryTexts()
    {
        return $this->hasMany(HistoryText::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageItemFotogaleries()
    {
        return $this->hasMany(ImageItemFotogalerie::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageItemTeasers()
    {
        return $this->hasMany(ImageItemTeaser::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageItemTexts()
    {
        return $this->hasMany(ImageItemText::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinklists()
    {
        return $this->hasMany(Linklist::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinklistColumns()
    {
        return $this->hasMany(LinklistColumn::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinklistColumnItems()
    {
        return $this->hasMany(LinklistColumnItem::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageinstances()
    {
        return $this->hasMany(Pageinstance::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmenus()
    {
        return $this->hasMany(Submenu::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeasers()
    {
        return $this->hasMany(Teaser::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermins()
    {
        return $this->hasMany(Termin::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerminbereiches()
    {
        return $this->hasMany(Terminbereich::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerminuebersichts()
    {
        return $this->hasMany(Terminuebersicht::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerminuebersichtHasTerminbereiches()
    {
        return $this->hasMany(TerminuebersichtHasTerminbereiche::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTexts()
    {
        return $this->hasMany(Text::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNavigations()
    {
        return $this->hasMany(Navigation::className(), ['created_by' => 'id']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedimages()
    {
        return $this->hasMany(Uploadedimage::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::className(), ['id' => 'organisation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
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
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'updated',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }    
    
    /*
    public function registrateUser($useraddress){
        $useraddress->save();
        $this->useraddress_id    = $useraddress->id;
        //validationtoken per zufall setzen
        //gesetzt = Registrierung noch nicht abgeschlossen
        $this->validationtoken  = password_hash(\Yii::$app->security->generateRandomString(), PASSWORD_BCRYPT);
        $this->isvalidated      = 0;
        $this->role_id = 2;//Standard
        //$this->authkey = \Yii::$app->security->generateRandomString();
        if($this->validate()){
            //nach validierung, passwörter verschlüsseln und user anlegen
            $hash = password_hash($this->password, PASSWORD_DEFAULT);
            $this->password         = $hash;
            $this->password_repeat  = $hash;
            return $this->save();
        }
        return false;
    }
    */

    public function createUser(){
            //validationtoken per zufall setzen
            //gesetzt = Registrierung noch nicht abgeschlossen
            //$this->validationtoken  = \yii\helpers\Html::encode(password_hash(\Yii::$app->security->generateRandomString(), PASSWORD_BCRYPT),true);
            $this->validationtoken  = \yii\helpers\Html::encode(\Yii::$app->security->generateRandomString());
            $this->isvalidated      = 0;
            $this->role_id = 2;//Standard
            //$this->authkey = \Yii::$app->security->generateRandomString();
            if($this->validate()){
                //nach validierung, passwörter verschlüsseln und user anlegen
                $hash = password_hash($this->password, PASSWORD_DEFAULT);
                $this->password         = $hash;
                $this->password_repeat  = $hash;
                return $this->save();
            }
            return false;
    }
    
    public function sendRegistrationMail(){
        return MailCollection::sendRegistrationMail($this);
        
    }
    
    /**
     * Prüft ob es einen User mit diesem Validierungstoken gibt, der noch nicht validiert ist.
     * Null, if not found.
     * @param string $token
     * @return boolean
     */
    public static function validateUser($token) {
        $item = self::findByValidationtoken(\yii\helpers\Html::decode($token));
        //gefunden und korrekt noch nicht validiert?
        if(!is_null($item)){
            if(!$item->isvalidated){
                $createdTimestamp = DateConverter::convertToTimestamp($item->created);
                if(\app\models\helpers\DateCalculator::isOlderThanXDays($createdTimestamp, 3)){
                    Yii::info("Freischalten der Registration erfolglos weil Registrierung zu lange her: ". json_encode($item->getErrors()),__METHOD__);
                    throw new \yii\base\ErrorException('Ihre Registrierung ist zu lange her, bitte registrieren Sie sich erneut.');
                }
                //validierung eintragen
                $item->isvalidated = 1;
                if($item->save()){
                    //Yii::info('User erfolgreich freigeschaltet: User ID '.$item->id, __METHOD__);
                    return true;
                }
                else {
                    Yii::error("Fehler beim Freischalten der Registration: ". json_encode($item->getErrors()),__METHOD__);
                    throw new \yii\base\ErrorException('Fehler: Ihre Bestätigung konnte nicht gepeichert werden, versuchen Sie es später nochmal.');
                }
            }
            else {
                Yii::warning('User hat erneut versucht Account freizuschalten mit RegistrationToken: '.$token,__METHOD__);
                throw new \yii\base\ErrorException('Ihr Account ist bereits bestätigt.');
            }
        }
        else {
            Yii::warning('Registrierung kann nicht freigeschaltet werden wegen unbekanntem/falschem mit unbekanntem Registrationtoken: '.$token,__METHOD__);
            throw new \yii\base\Exception('Fehler: Registrierung nicht gefunden.');
        }
        return false;
    }
    
    /**
     * Prueft, ob der User das Recht zum Aufruf einer Funktion hat
     * - findet zuerst die gematchte RightId zur Methode und Path
     * - prueft dann ob User das Recht in seiner Rolle hat nutzen darf
     * @param String $method GET, POST, etc.
     * @param String $path Url ohne Domain
     * @return boolean true, wenn erlaubt; false wenn nicht erlaubt
     *
    public function isAuthorizedForMethod($method, $path){
        $rightId = Right::getRightIdForMatchedAction($method, $path);
        //wenn gefunden, dann Recht pruefen
        if($rightId)
            return $this->hasRight($rightId);
        //sonst keine Beschraenkung auf der Action also erlaubt
        return true;
    }
    

    /**
     * Prüft, ob der User ein Recht hat
     * @param int $rightId
     * @return boolean
     */
    public function hasRight($rightId) {
        if($this->locked !== null) return false;
        return $this->role->hasRight($rightId);
    }    
    
    /**
     * Prüft ob eingeloggt und ob Recht vorhanden
     * @param int $rightId
     * @return boolean
     */
    public static function checkRight($rightId) {
        if(!Yii::$app->user->isGuest){
            $user = Yii::$app->user->identity;
            return $user->hasRight($rightId);
        }
        return false;
    }
    
    /**
     * Setzt lastLogin-Date und speichert
     * @param boolean $withSave [default:true] Speichern lässt sich ausschalten, falls im Controller
     * bereits der User gespeichert wird, um doppeltes Speichern zu verhindenr
     */
    public function updateLastlogin($withSave = true) {
        $this->lastlogindate = new Expression('NOW()');
        if($withSave)
            $this->update();
    }
    
    public function getUser(){
        return User::findIdentity(Yii::$app->user->identity->id);
    }
    public static function getLoggedInUser(){
        if(self::$user === null)
            self::$user = User::findIdentity(Yii::$app->user->identity->id);
        return self::$user;
    }

    public function getName(){
        return $this->firstname." ".$this->lastname;
    }

    /**
     * Setzt ein neues, zufälliges Passwort
     * @return boolean|string
     */
    public function resetPassword(){
        $newPassword = "P".rand(10000000, 99999999);
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->password         = $hash;
        
        if($this->save())
            return $newPassword;
        return false;
    }

    public function sendResetPasswordMail($newPassword){
        return MailCollection::sendResetPasswordMail($this, $newPassword);        
    }
    
    /**
     * Markiert den User als gelöscht(danach kein Login mehr möglich)
     * @return boolean|string
     */
    public function markAsDeleted(){
        $this->deleted         = new Expression('NOW()');
        if($this->save())
            return true;
        return false;
    }
    
    /**
     * Sperrt den User (kein Login mehr möglich)
     * @return boolean|string
     */
    public function lock(){
        $this->locked         = new Expression('NOW()');
        if($this->save())
            return true;
        return false;
    }    

    /**
     * Entsperrt den User
     * @return boolean|string
     */
    public function unlock(){
        $this->locked         = new Expression('NULL');
        if($this->save())
            return true;
        return false;
    }    

    public function search(){
        $search = User::find();
        $search->where("user.deleted is null and isvalidated = 1");

        $search->joinWith(['role']);
        $search->joinWith(['organisation']);
        
        if($this->id != null)
            $search->andWhere(['like','id',$this->id]);
        if($this->lastname != null)
            $search->andWhere(['like','lastname',$this->lastname]);
        if($this->firstname != null)
            $search->andWhere(['like','firstname',$this->firstname]);
        if($this->email != null)
            $search->andWhere(['like','email',$this->email]);
        if($this->organisationname != null)
            $search->andWhere(['like','organisation.name',$this->organisationname]);
        if($this->rolename != null)
            $search->andWhere(['like','role.name',$this->rolename]);
        return $search;
    }    

    
    
    /**
     * Prüft, ob der User das Pagemenu der Seite öffnen darf 
     * @param Page $page
     * @return boolean
     */
    public static function hasRightToOpenPageMenu($page)
    {
        if(!self::checkRight(Right::PAGE_ADMIN_MENU)){
            return false;
        }
        else {
            if(!$page->details_admin_only)
                return true;
            else 
                if(self::checkRight(Right::PAGE_ADMIN_ASADMIN))
                    return true;
        }
        return false;
    }    
    
    
}
