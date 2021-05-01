<?php

namespace app\models\content;

use Yii;
use yii\base\Exception;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "teaser".
 *
 * @property integer $id
 * @property string $headline
 * @property string $text
 * @property string $created
 * @property integer $created_by
 * @property string $updated
 * @property integer $updated_by
 * @property integer $link_item_id
 * @property integer $teaserlist_id
 * @property integer $sort
 * @property integer $release_id
 * @property integer $document_list_id
 * @property integer $image_list_id
 *
 * @property User $createdBy
 * @property ImageList $imageList
 * @property LinkItem $linkItem
 * @property Release $release
 * @property DocumentList $documentList
 * @property Teaserlist $teaserlist
 * @property User $updatedBy
 */
class Teaser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teaser';
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['headline', 'text'], 'string'],
            [['headline','text', 'teaserlist_id', 'release_id', 'image_list_id', 'document_list_id', 'link_item_id'], 'required'],
            [['created', 'updated'], 'safe'],
            [['created_by', 'updated_by', 'link_item_id', 'teaserlist_id', 'image_list_id', 'document_list_id', 'sort', 'release_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'headline' => 'Headline',
            'text' => 'Text',
            'created' => 'Created',
            'created_by' => 'Created By',
            'updated' => 'Updated',
            'updated_by' => 'Updated By',
            'link_item_id' => 'Link Item ID',
            'teaserlist_id' => 'Teaserlist ID',
            'sort' => 'Sort',
            'release_id' => 'Release ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryTeasers()
    {
        return $this->hasMany(HistoryTeaser::className(), ['teaser_id' => 'id']);
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
    public function getLinkItem()
    {
        return $this->hasOne(LinkItem::className(), ['id' => 'link_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelease()
    {
        return $this->hasOne(Release::className(), ['id' => 'release_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeaserlist()
    {
        return $this->hasOne(Teaserlist::className(), ['id' => 'teaserlist_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentList()
    {
        return $this->hasOne(DocumentList::className(), ['id' => 'document_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentListWithDocuments()
    {
        return $this->hasOne(DocumentList::className(), ['id' => 'document_list_id'])->with('documents');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageList()
    {
        return $this->hasOne(ImageList::className(), ['id' => 'image_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageListWithImages()
    {
        return $this->hasOne(ImageList::className(), ['id' => 'image_list_id'])->with('uploadedimages');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TeaserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeaserQuery(get_called_class());
    }
    
    /**
     * Sortiert ein Item eine Position höher
     * @return Teaser
     */
    public function sortUp()
    {
        //abwärts sortiert, daher nächstes Item finden
        $nextItem  = Teaser::find()->where(['sort'=>($this->sort + 1)])->one();
        //aktuelles Item rauf sortieren
        $this->sort = $this->sort + 1;
        //nächstes Item runter sortieren
        $nextItem->sort = $nextItem->sort - 1;
        if($this->save() && $nextItem->save()){
            return $this;
        }
        else {
            throw new \yii\web\UnprocessableEntityHttpException(json_encode($this->getErrors()));
        }
    }

    /**
     * Gibt den aktuell höchsten Wert für sort zurück in der Teaserliste
     * @param int $p teaserlist_id
     */
    public static function getMaxSort($teaserlist_id)
    {
        return Teaser::find()->where(['teaserlist_id'=>$teaserlist_id])->max("sort");
    }

    /**
     * Sortiert alle FolgeItems (mit höherem Sort) eins hoch (z.b. beim Löschen)
     * @return boolean
     */
    public function decreaseFollowing()
    {
        $items  = Teaser::find()->where(['>','sort',$this->sort])->all();
        foreach ($items as $item){
            $item->sort = $item->sort-1;
            if($item->save()){
                return $this;
            }
            else {
                throw new \yii\web\UnprocessableEntityHttpException(json_encode($item->getErrors()));
            }
        }
    }    

    /**
     * Entfernt die Verlinkung
     * @return boolean
     */
    public function removeLink()
    {
        $linkItem = $this->linkItem;
        $this->link_item_id = new Expression("NULL");
        $this->save();
        return $linkItem->delete();
        
    }    

    /**
     * Updatet das Objekt mit den Requestdaten
     * @param Array $objReq Text als Array
     * @throws Exception
     */
    public function updateFromRequest($objReq = false){
        Yii::debug("UpdateFromRequest Teaser",__METHOD__);
        //Normalfall, wenn nichts übergeben
        $req = Yii::$app->request->post(); // falls nicht übergeben
        if($this->load($req) ){
            if($this->validate()){
                $this->updated = new Expression('NOW');
                if(!$this->save()){
                    throw new Exception (Errormessages::$errors['DATASAVINGERROR']['message'], Errormessages::$errors['DATASAVINGERROR']['id']);
                }
            }
            else {
                Yii::error('Got Request: '.json_encode(Yii::$app->request->post()),__METHOD__);
                Yii::error('Errors: ' . json_encode($this->getErrors()),__METHOD__);
                throw new Exception (Errormessages::$errors['DATASAVINGERROR']['message'], Errormessages::$errors['DATASAVINGERROR']['id']);
            }
        }
        else Yii::error ('Kein Teaser[] im Request gefunden',__METHOD__);

    }

    /**
     * Legt einen Teaser an
     * @param int $teaserlist_id
     * @return \app\models\content\Teaser
     * @throws Exception
     */
    public static function create($teaserlist_id, $headline){
        $item = new Teaser();
        $item->teaserlist_id    = $teaserlist_id;
        $item->headline         = $headline;
        $item->text             = Yii::t('app', "TEXT_CONTENT_DEFAULT");
        $item->sort             = self::getMaxSort($teaserlist_id) + 1;
        
        $release                = Release::create(); 
        $imageList              = ImageList::create(); 
        $documentList           = DocumentList::create();
        $linkItem               = LinkItem::create();
        
        $item->release_id       = $release->id;
        $item->image_list_id    = $imageList->id;
        $item->document_list_id = $documentList->id;
        $item->link_item_id     = $linkItem->id;
        
        if($item->save()){
            return $item;
        }
        else {
            throw new Exception( json_encode($item->getErrors()) );
        }
        
    }    
    
    
}
