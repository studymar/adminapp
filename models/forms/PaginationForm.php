<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

class PaginationForm extends Model {
   
   /** @var int $itemsPerPage **/
   public $itemsPerPage = 15;
   public $actualPage   = 1;

   /*
   public $totalPages;
   public $totalItems;
   public $actualItemsFrom;
   public $actualItemsTo;
   */
   
   public function __construct() {
      
   }

   public function rules() {
      return array(
         [['actualPage', 'itemsPerPage'], 'integer', 'message'=>'{attribute} muss eine Zahl sein' ],
       );
   }

   /**
    * Gibt die Werte für das Pagination zurück
    * @params int $countItemsAll Anzahl Items Gesamt
    * @return Array
    */
   function getPaginationAsArray($countAllItems){
        //pagination Werte fuer pagination berechnen anhand Ergebnis
        $pagination   = array();
        $pagination['itemsPerPage']     = $this->itemsPerPage;
        $pagination['actualPage']       = $this->actualPage;
        $pagination['totalPages']       = ceil($countAllItems/ $this->itemsPerPage);
        $pagination['totalItems']       = $countAllItems;
        $pagination['actualItemsFrom']  = $this->getOffset()+1;
        //Bis: falls letzte Seite, dann nur bis zum letzten Item
        $pagination['actualItemsTo']    = (($this->actualPage * $this->itemsPerPage)>$countAllItems) ? $countAllItems : $this->actualPage * $this->itemsPerPage;
        return $pagination;
   }
   
   /**
    * Gibt die Werte für das Pagination zurück
    * @return Array
    */
   function getOffset(){
       return (($this->actualPage-1) * $this->itemsPerPage);
   }
   
}