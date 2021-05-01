<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\base\Exception;
use app\models\content\Uploadedimage;

class UploadImageForm extends Model {
   
    public $imageFiles;

    public function __construct() {
      
    }

    public function rules() {
        return array(
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif', 'maxFiles' => 10,'maxSize'=>1024 * 1024 * 2  /*2 MB*/],
        );
    }

    public function upload()
    {
        if ($this->validate()) { 
            foreach ($this->imageFiles as $file) {
                //save image
                $filename = $file->baseName.'-'. date('YmdHis') . '.' . $file->extension;
                $file->saveAs(\app\models\content\Uploadedimage::DIRECTORY . $filename);
                //save db-item
                $item = Uploadedimage::create($file, $filename);
                if($item->hasErrors()){
                    $this->addErrors($item->getErrors ());
                }
            }
            if(!$this->hasErrors())
                return true;
        }
        
        return false;
    }
   
}