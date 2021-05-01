<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class AddTemplateForm extends Model {
   
   public $template_id;

   public function __construct() {
      
   }

   public function rules() {
      return array(
         [['template_id'], 'required' ],
         ['template_id', 'integer', 'message'=>'{attribute} muss eine Zahl sein' ],
        );
   }

   public function attributeLabels(){
      return array(
         'headline' => 'Überschrift des neuen Moduls',
          'template_id' => 'Template',
      );
   }    

   
}