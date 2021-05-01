<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class SortPageHasTemplateForm extends Model {
   
   public $ids;//pageHasTemplateId

   public function __construct() {
      
   }

   
   public function rules() {
      return array(
         [['ids'], 'required' ],
         /*['ids', 'each', 'rule' => ['integer'], 'message'=>'{attribute} muss eine Zahl sein' ],*/
        );
   }


   
}