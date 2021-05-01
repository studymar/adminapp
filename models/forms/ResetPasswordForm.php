<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\user\User;

class ResetPasswordForm extends Model {
   
   public $email;

   public function __construct() {
      
   }

   public function rules() {
      return array(
          /*
         array('email, password', 'required','message'=>'Bitte füllen Sie die Felder aus.'),
         array('email, password', 'match', 'pattern'=>'/^([a-zA-Z0-9_])/','message'=>'{attribute} enthält unerlaubte Zeichen.'),
         array('email', 'email', 'message'=>'Bitte geben Sie eine gültige Emailadresse ein.'),
         array('password', 'length', 'min'=>3, 'max'=>40, 'tooShort'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.', 'tooLong'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.'),
          */
         ['email', 'required' ],
         [['email'], 'email', 'message'=>'Bitte geben Sie eine Email ein'],
         ['email', 'match', 'pattern'=>'/^([a-zA-Z0-9_])/','message'=>'{attribute} enthält unerlaubte Zeichen.'],
         [['email'], 'string','length' => [5, 100], 'message'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.', 'tooLong'=>'{attribute} muss zwischen {min} und {max} Zeichen lang sein.'],
       );
   }
   
   public function attributeLabels(){
      return array(
         'email' => 'Email',
      );
   } 

   
}