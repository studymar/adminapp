<?php

namespace app\models\content;

use Yii;
use app\models\Errormessages;
use yii\base\Exception;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\models\user\User;

/**
 * This is the model class for table "image_list".
 *
 * @property int $id
 * @property string $created
 * @property int $page_has_template_id
 *
 * @property ImageItem[] $imageItems
 * @property Uploadedimages[] $uploadedimages
 * @property Teaser[] $teasers
 * @property User $createdBy
 * @property PageHasTemplate $pageHasTemplate
 * @property User $updatedBy
 */
class ImageList extends \yii\db\ActiveRecord  implements IsDeletablePageHasTemplateInterface
{
    public $uploadedimages_ids; //für speichern aus einer Form
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image_list';
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            ['uploadedimages_ids', 'each', 'rule' => ['number']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageHasTemplate()
    {
        return $this->hasOne(PageHasTemplate::className(), ['id' => 'page_has_template_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageItems()
    {
        return $this->hasMany(ImageItem::className(), ['image_list_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedimages()
    {
        return $this->hasMany(Uploadedimage::className(), ['id' => 'uploadedimage_id'])->viaTable('image_item', ['image_list_id' => 'id']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeasers()
    {
        return $this->hasMany(Teaser::className(), ['image_list_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    
    
    public static function create($page_has_template_id = null){
        $item = new ImageList();
        $item->page_has_template_id = $page_has_template_id;
        if($item->save()){
            return $item;
        }
    }    
    
    /**
     * Updatet das Objekt mit den Requestdaten
     * @param Array $objReq ImageList als Array
     * @throws Exception
     */
    public function updateFromRequest($objReq = false){
        Yii::debug("UpdateFromRequest ImageList",__METHOD__);
        //Normalfall, wenn nichts übergeben
        $req = Yii::$app->request->post(); // falls nicht übergeben
        //falls übergeben, Requestdaten aus Parameter
        if($objReq){
           $req = [];
           $req['ImageList'] = $objReq;
        }
        //alte ImageItems löschen
        ImageItem::deleteAll('image_list_id = '.$this->id);
        //neue einbinden und speichern
        if($this->load($req) ){
            if($this->validate(['uploadedimages_ids'])){
                //neue erstellen
                $i = 1;
                if($this->uploadedimages_ids){
                    Yii::debug("Uploadedimages_ids: ".json_encode($this->uploadedimages_ids),__FUNCTION__);
                    foreach ($this->uploadedimages_ids as $image_id){
                        Yii::debug("Speichere ImageItem: ".$image_id,__FUNCTION__);
                        $item = new ImageItem();
                        $item->image_list_id = $this->id;
                        $item->sort = $i++;
                        $item->uploadedimage_id = $image_id;
                        if(!$item->save()){
                            Yii::error('Uploadedimageid: '.$image_id,__METHOD__);
                            throw new Exception (Errormessages::$errors['IMAGEITEMSAVINGERROR']['message'], Errormessages::$errors['IMAGEITEMSAVINGERROR']['id']);
                        }
                    }
                }
            }
            else {
                Yii::error('Got Request: '.json_encode(Yii::$app->request->post()),__METHOD__);
                Yii::error('Errors: ' . json_encode($this->getErrors()),__METHOD__);
                throw new Exception (Errormessages::$errors['IMAGELISTVALIDATIONERROR']['message'], Errormessages::$errors['IMAGELISTVALIDATIONERROR']['id']);
            }
        }

    }
    
    
    /**
     * Gibt das erste Image aus der Liste zurück
     * Vor allem sinnvoll, wenn die Liste auf ein einziges Image beschränkt ist (vereinfacht den Abruf)
     * Gibt false zurück, wenn kein Objekt in Liste
     * @return Uploadedimage
     */
    function getFirstUploadedimage(){
        if( $this->countUploadedimages() > 0 )
            return $this->uploadedimages[0];
        else return false;
    }

    /**
     * Gibt die Anzahl der Uploadedimages in der Liste zurück
     * @return int
     */
    function countUploadedimages(){
        return count($this->uploadedimages);
    }

    
    /**
     * 
     * @param int $p
     * @return Text
     */
    public static function findByPageHasTemplateId($p){
        return self::find()->where(['page_has_template_id'=>$p])->one();
    }
    
    /**
     * Löscht das Item
     */
    public function deleteItem(){
        foreach($this->imageItems as $item){
            $item->delete();
        }
        $this->delete();
        return true;
    }
    
    
}
