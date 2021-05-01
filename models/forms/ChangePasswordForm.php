<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\user\User;

class ChangePasswordForm extends Model {
   
   public $password;
   public $password_repeat;

   public function __construct() {
      
   }

   public function rules() {
      return array(
        [['password','password_repeat'], 'required','message'=>'Bitte füllen Sie die Felder aus.'],
        [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'message'=>'Die Passwörter stimmen nicht überein.'],
        [['password','password_repeat'], 'string','length' => [5, 40], 'message'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.', 'tooLong'=>'{attribute} muss zwischen {min} und {max} Zeichen lang sein.'],
       );
   }
   
   public function attributeLabels(){
      return array(
          'password' => 'Passwort',
          'password_repeat' => 'Passwort(Wdh.)'
      );
   } 

   /**
    * Laedt Daten in ein bestehenden User
    * @param User $user
    * @return User
    */
   public function mapToUser($user){
        $hash = password_hash($this->password, PASSWORD_DEFAULT);
        $user->password         = $hash;
        $user->password_repeat  = $hash;
        return $user;
   }   
   
}