<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class SortForm extends Model {
   
   public $ids;

   public function __construct() {
      
   }

   
   public function rules() {
      return array(
         [['ids'], 'required' ],
         /*['ids', 'each', 'rule' => ['integer'], 'message'=>'{attribute} muss eine Zahl sein' ],*/
        );
   }


   
}