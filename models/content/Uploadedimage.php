<?php

namespace app\models\content;

use Yii;
use app\models\user\User;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\imagine\Image;

/**
 * This is the model class for table "uploadedimage".
 *
 * @property integer $id
 * @property string $name
 * @property string $filename
 * @property string $extensionname
 * @property integer $width
 * @property integer $height
 * @property integer $size
 * @property string $imagetype
 * @property string $created
 * @property integer $created_by
 * @property integer $is_landscape
 *
 * @property ImageItemFotogalerie[] $imageItemFotogaleries
 * @property ImageItemTeaser[] $imageItemTeasers
 * @property ImageItemText[] $imageItemTexts
 * @property User $createdBy
 */
class Uploadedimage extends \yii\db\ActiveRecord
{
    const DIRECTORY = 'content/images/up/';
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uploadedimage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'filename', 'extensionname', 'imagetype'], 'string'],
            [['width', 'height', 'size', 'created_by', 'is_landscape'], 'number'],
            [['created'], 'safe']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
        ];
    }      
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'filename' => 'Filename',
            'extensionname' => 'Extensionname',
            'width' => 'Width',
            'height' => 'Height',
            'size' => 'Size',
            'imagetype' => 'Imagetype',
            'created' => 'Created',
            'created_by' => 'Created By',
            'is_landscape' => 'Is Landscape',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     *
    public function getImageItemFotogaleries()
    {
        return $this->hasMany(ImageItemFotogalerie::className(), ['uploadedimage_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function  getImageItems()
    {
        return $this->hasMany(ImageItem::className(), ['uploadedimage_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return UploadedimageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UploadedimageQuery(get_called_class());
    }
    
    /**
     * Erstellt ein UploadedImage, speichert es und gibt es zurück
     * @param \yii\web\UploadedFile $uploadedFile
     */
    public static function create(\yii\web\UploadedFile $uploadedFile, $filename){
        $item = new Uploadedimage();
        $item->name         = null;
        $item->filename     = $filename;
        $item->extensionname= substr(stristr($item->filename, '.'),1);
        $item->size         = $uploadedFile->size / 1024; // in kb
        $item->imagetype    = $uploadedFile->type;
        //get original size
        list($width, $height, $type, $attr) = getimagesize(self::DIRECTORY.$item->filename);
        //auf 480px Höhe berechnet, da sonst ggf. zu hoch, bei weniger als 60px breite wird zentriert
        $item->size = $item->resize($width/$height * 480, 480) / 1024;
        //$item->size = $item->resize(610, $height/$width * 610) / 1024;
        //get new size
        list($width, $height, $type, $attr) = getimagesize(self::DIRECTORY.$item->filename);
        $item->width        = $width;
        $item->height       = $height;
        $item->is_landscape = ($width > $height)? 1 : 0;
        $item->save();
        return $item;
    }
    
    public function resize($newWidth, $newHeight){
        Image::getImagine()
            ->open(Uploadedimage::DIRECTORY . $this->filename)
            ->resize(new \Imagine\Image\Box($newWidth, $newHeight))
            ->save(Uploadedimage::DIRECTORY . $this->filename , ['quality' => 100]);
        clearstatcache();
        return filesize(Uploadedimage::DIRECTORY . $this->filename);

    }

    public function crop(\app\models\forms\UploadedimageCropForm $form){
        $filename       = $this->filename;
        Image::getImagine()
            ->open(Uploadedimage::DIRECTORY . $this->filename)
            ->crop(new \Imagine\Image\Point($form->left,$form->top), new \Imagine\Image\Box($form->width,$form->height))
            //unter namen mit aktualisierem Datum speichern (um caching zu verhindern)
            ->save(Uploadedimage::DIRECTORY . $this->updateFilenamedate() , ['quality' => 100]);
        clearstatcache();
        $this->updateImageattributes();
        Uploadedimage::deleteFile($filename);//altes image löschen
        return $this->save();

    }

    public function updateImageattributes(){

        list($width, $height, $type, $attr) = getimagesize(self::DIRECTORY.$this->filename);
        $this->width        = $width;
        $this->height       = $height;
        $this->size         = filesize(Uploadedimage::DIRECTORY . $this->filename) / 1024;
        
    }

    public function deleteItem($deleteFile = true){
        $items = $this->getImageItems()->all();
        if(count($items)==0){
            unlink(Uploadedimage::DIRECTORY . $this->filename);
            if($deleteFile){
                return $this->delete();
            }
        }
        else 
            return false;
        
    }
    
    /**
     * 
     * @param string $filename filename 
     * Path is autamatically added
     * @return boolean
     */
    public static function deleteFile($filename){
        return unlink(Uploadedimage::DIRECTORY . $filename);
        
    }

    /**
     * Takes the filename and updates the date
     * @return string Filename
     */
    public function updateFilenamedate(){
        //altes Datum abtrennen
        $filename = stristr($this->filename, '-', true);
        //wenn kein datum gefunden, dann zumindest nur extension entfernen
        if($filename === false)
            $filename = stristr($this->filename, '.', true);
        //neues datum + extensionname anhängen und zurückgeben
        return $this->filename = $filename . '-' . date('YmdHis') . '.' . $this->extensionname;
        
    }

    
}
