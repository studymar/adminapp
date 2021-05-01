<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class PagetypeSelectForm extends Model {
   
   public $pagetype_id;

   public function __construct() {
      
   }

   public function rules() {
      return array(
         [['pagetype_id'], 'required' ],
         ['pagetype_id', 'integer', 'message'=>'{attribute} muss eine Zahl sein' ],
        );
   }

   public function attributeLabels(){
      return array(
         'pagetype_id' => 'Typ der Seite',
      );
   }    

   
}