<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class RemoveTemplateForm extends Model {
   
   public $page_has_template_id;//pageHasTemplate

   public function __construct() {
      
   }

   public function rules() {
      return array(
         [['page_has_template_id'], 'required' ],
         ['page_has_template_id', 'integer', 'message'=>'{attribute} muss eine Zahl sein' ],
        );
   }

   public function attributeLabels(){
      return array(
         'page_has_template_id' => 'ID',
      );
   }    

   
}