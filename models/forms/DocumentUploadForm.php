<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\base\Exception;
use app\models\content\Document;

class DocumentUploadForm extends Model {
   
    public $documentFiles;

    public function __construct() {
      
    }

    public function rules() {
        return array(
            [['documentFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'doc, docx, xls, xlsx, pdf, ppt, pptx', 'maxFiles' => 10,'maxSize'=>1024 * 1024 * 2  /*2 MB*/],
        );
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->documentFiles as $file) {
                //save document
                $filename = $file->baseName.'-'. date('YmdHis') . '.' . $file->extension;
                $file->saveAs(Document::DIRECTORY . $filename);
                //save db-item
                $item = Document::create($file, $filename);
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