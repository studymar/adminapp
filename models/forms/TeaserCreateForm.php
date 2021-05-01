<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class TeaserCreateForm extends Model {
   
   public $headline;

   public function __construct() {
      
   }

   public function rules() {
      return array(
         ['headline', 'required' ],
         ['headline', 'string' ,'length'=>[4, 60], 'tooShort'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.', 'tooLong'=>'{attribute} muss zwischen {min} und {min} Zeichen lang sein.' ],
       );
   }

   

   
}