<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * @property integer $width
 * @property string $height
 * @property integer $areaWidth
 * @property string $areaHeight
 * @property string $left
 * @property string $top
 */
class UploadedimageCropForm extends Model {
   
    public $width;
    public $height;
    public $areaWidth;
    public $areaHeight;
    public $left;
    public $top;


    public function __construct() {
      
   }

   public function rules() {
        return [
            [['width','height','areaWidth','areaHeight','top','left'], 'required'],
            [['width','height','areaWidth','areaHeight','top','left'], 'number'],
        ];
   }
   
   
   
}