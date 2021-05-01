<?php

namespace app\models\content;

use Yii;
use app\models\user\User;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property string $name
 * @property string $filename
 * @property string $extensionname
 * @property int $size
 * @property string $doctype
 * @property string $created
 * @property int $created_by
 *
 * @property User $createdBy
 * @property DocumentItem[] $documentItems
 */
class Document extends \yii\db\ActiveRecord
{
    const DIRECTORY = 'content/documents/up/';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'filename', 'extensionname', 'doctype'], 'string'],
            [['size', 'created_by'], 'integer'],
            [['created'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'filename' => 'Filename',
            'extensionname' => 'Extensionname',
            'size' => 'Size',
            'doctype' => 'Doctype',
            'created' => 'Created',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\app\models\user\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentItems()
    {
        return $this->hasMany(DocumentItem::className(), ['document_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentLists()
    {
        return $this->hasMany(DocumentList::className(), ['id' => 'teaser_id'])->viaTable('document_item', ['document_list_id' => 'id']);
    }
    
    public function deleteItem($deleteFile = true){
        $items = $this->getDocumentItems()->all();
        if(count($items)==0){
            unlink(Document::DIRECTORY . $this->filename);
            if($deleteFile){
                return $this->delete();
            }
        }
        else 
            return false;
        
    }
    
    /**
     * Erstellt ein Document, speichert es und gibt es zurück
     * @param \yii\web\UploadedFile $uploadedFile
     */
    public static function create(\yii\web\UploadedFile $uploadedFile, $filename){
        $item = new Document();
        $item->name         = substr($filename, 0, strripos($filename,'-'));
        $item->filename     = $filename;
        $item->extensionname= substr(stristr($item->filename, '.'),1);
        $item->size         = $uploadedFile->size / 1024; // in kb
        $item->doctype      = $uploadedFile->type;
        //get original size
        $item->size         = filesize(Document::DIRECTORY . $item->filename);
        $item->save();
        return $item;
    }
    
}
