<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class RoleEditForm extends Model {
   
   public $name;
   public $right_ids;
   public $pagetype_ids;

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
         ['right_ids', 'each', 'rule'=> ['integer'] ],
         ['pagetype_ids', 'each', 'rule'=> ['integer'] ],
         ['name', 'required' ],
         ['name', 'string' ,'length'=>[4, 40], 'tooShort'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.', 'tooLong'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.' ],
       );
   }

   

   
}