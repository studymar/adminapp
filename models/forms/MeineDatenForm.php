<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\user\User;

/**
 * @property integer $id
 * @property integer $isvalidated
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $lastlogindate
 * @property string $created
 * 
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string $phone
 * 
 * @property integer $role_id
 * @property integer $organisation_id
 * @property integer $useraddress_id
 */
class MeineDatenForm extends Model {
   
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $lastlogindate;
    public $created;
    public $organisation_id;
    
    public $street;
    public $zip;
    public $city;
    public $phone;


    public function __construct() {
      
   }

   public function rules() {
        return [
            [['firstname','lastname','street','zip','city','email','organisation_id'], 'required'],
            [['phone'], 'safe'],
            [['email'], 'email','message'=>'Bitte tragen Sie Ihre reale Emailadresse ein.'],
            [['organisation_id'], 'exist', 'targetClass' => '\app\models\organisation\Organisation', 'targetAttribute' => 'id'],
        ];
   }
   
   /**
    * Laedt Daten ins Formular
    * @param User $user
    */
   public function mapFromUser($user){
       $this->id                = $user->id;
       $this->firstname         = $user->firstname;
       $this->lastname          = $user->lastname;
       $this->email             = $user->email;
       $this->lastlogindate     = $user->lastlogindate;
       $this->created           = $user->created;
       $this->organisation_id   = $user->organisation_id;
       
       $this->street            = $user->useraddress->street;
       $this->zip               = $user->useraddress->zip;
       $this->city              = $user->useraddress->city;
       $this->phone             = $user->useraddress->telephone;
   }
   
   /**
    * Laedt Daten in ein bestehenden User
    * @param User $user
    * @return User
    */
   public function mapToUser($user){
       $user->firstname         = $this->firstname;
       $user->lastname          = $this->lastname;
       $user->email             = $this->email;
       $user->organisation_id   = $this->organisation_id;
       return $user;
   }
   
   /**
    * Laedt Daten in ein bestehenden User
    * @param Useraddress $useraddress
    * @return Useraddress
    */
   public function mapToUseraddress($useraddress){
       $useraddress->street    = $this->street;
       $useraddress->zip       = $this->zip;
       $useraddress->city      = $this->city;
       $useraddress->telephone = $this->phone;
       return $useraddress;
   }   
   
}