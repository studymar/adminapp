<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\base\Exception;

class UploadedimageEditForm extends Model {
   
    public $name;

    public function __construct() {
      
    }

    public function rules() {
        return array(
         ['name', 'string' ,'length'=>[1,50], 'tooShort'=>'{attribute} muss zwischen {min} Zeichen lang sein.', 'tooLong'=>'{attribute} darf max. {max} Zeichen lang sein.' ],
         ['name', 'match', 'pattern'=>'/^([a-zA-Z0-9öÖüÜäÄß?= &%:-{+}]+)/','message'=>'{attribute} enthält unerlaubte Zeichen.'],
        );
    }

    public function map($item)
    {
        $item->name = $this->name;
        return $item;
    }
   
}