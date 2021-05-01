<?php

namespace app\models\content;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributesBehavior;
use yii\i18n\Formatter;
use app\models\user\User;
use app\models\helpers\DateConverter;

/**
 * This is the model class for table "release".
 *
 * @property integer $id
 * @property integer $is_released
 * @property string $from_date
 * @property string $to_date
 * @property string $created
 *
 * @property Download[] $downloads
 * @property HistoryLinklistColumn[] $historyLinklistColumns
 * @property HistoryPage[] $historyPages
 * @property HistoryTeaser[] $historyTeasers
 * @property ImageItemFotogalerie[] $imageItemFotogaleries
 * @property LinklistColumn[] $linklistColumns
 * @property LinklistColumnItem[] $linklistColumnItems
 * @property Page[] $pages
 * @property PageHasTemplate[] $pageHasTemplates
 * @property Teaser[] $teasers
 * @property Termin[] $termins
 * @property Text[] $texts
 */
class Release extends \yii\db\ActiveRecord
{
    
    public function __construct($is_released = false){
        if($is_released)
            $this->is_released = 1;
        else 
            $this->is_released = 0;
        parent::__construct();
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'release';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_released'], 'integer'],
            [['from_date', 'to_date','created'], 'safe'],
            /*
            [['from_date', 'to_date'], 'date', 'format' => 'php:d.m.Y','message'=>'Datum bitte im Format dd.MM.yyyy angeben'],
             * 
             */
        ];
    }

    public function fields()
    {
        return [
            'id',
            'is_released',
            'from_date',
            'to_date',
        ];
    }
    
    public function extraFields()
    {
        return [
        ];
    }      
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_released' => 'Öffentlich sichtbar',
            'from_date' => 'Sichtbar ab',
            'to_date' => 'Sichtbar bis',
            'created' => 'Created',
        ];
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
                'createdByAttribute' => false,
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => AttributesBehavior::className(),
                'attributes' => [
                    /*
                    'from_date' => [
                        //ActiveRecord::EVENT_BEFORE_INSERT => DateConverter::convert($this->from_date, DateConverter::DATE_FORMAT_DB),
                        //ActiveRecord::EVENT_BEFORE_UPDATE => DateConverter::convert($attribute, DateConverter::DATE_FORMAT_DB),
                        //ActiveRecord::EVENT_AFTER_FIND => DateConverter::convert($this->from_date, DateConverter::DATE_FORMAT_VIEW),
                    ],
                    'attribute2' => [
                        ActiveRecord::EVENT_BEFORE_VALIDATE => [$this, 'storeAttributes'],
                        ActiveRecord::EVENT_AFTER_VALIDATE => [$this, 'restoreAttributes'],
                    ],
                    'attribute3' => [
                        ActiveRecord::EVENT_BEFORE_VALIDATE => $fn2 = [$this, 'getAttribute2'],
                        ActiveRecord::EVENT_AFTER_VALIDATE => $fn2,
                    ],
                     */ 
                    'from_date' => [
                        ActiveRecord::EVENT_BEFORE_UPDATE => function ($event, $attribute) {
                            return DateConverter::convert($this->from_date, DateConverter::DATE_FORMAT_DB);
                        },
                        ActiveRecord::EVENT_AFTER_FIND => function ($event, $attribute) {
                            if($this->from_date){
                                Yii::debug("Release - find from_date: ".$this->from_date,__METHOD__);
                                return DateConverter::convert($this->from_date, DateConverter::DATE_FORMAT_VIEW);
                            }
                        },
                    ],
                    'to_date' => [
                        ActiveRecord::EVENT_BEFORE_UPDATE => function ($event, $attribute) {
                            return DateConverter::convert($this->to_date, DateConverter::DATE_FORMAT_DB);
                        },
                        ActiveRecord::EVENT_AFTER_FIND => function ($event, $attribute) {
                            if($this->to_date){
                                Yii::debug("Release - find to_date: ".$this->to_date,__METHOD__);
                                return DateConverter::convert($this->to_date, DateConverter::DATE_FORMAT_VIEW);
                            }
                        },
                    ],
                ],
            ],
        ];
    }    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDownloads()
    {
        return $this->hasMany(Download::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryLinklistColumns()
    {
        return $this->hasMany(HistoryLinklistColumn::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryPages()
    {
        return $this->hasMany(HistoryPage::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryTeasers()
    {
        return $this->hasMany(HistoryTeaser::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageItemFotogaleries()
    {
        return $this->hasMany(ImageItemFotogalerie::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinklistColumns()
    {
        return $this->hasMany(LinklistColumn::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinklistColumnItems()
    {
        return $this->hasMany(LinklistColumnItem::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageHasTemplates()
    {
        return $this->hasMany(PageHasTemplate::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeasers()
    {
        return $this->hasMany(Teaser::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermins()
    {
        return $this->hasMany(Termin::className(), ['release_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTexts()
    {
        return $this->hasMany(Text::className(), ['release_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ReleaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReleaseQuery(get_called_class());
    }
    
    
    public static function create($is_released = false){
        $release = new Release($is_released);
        if($release->save()){
            Yii::debug('Release erstellt: '.$release->id,__METHOD__);
            return $release;
        }
        else Yii::debug ($release->getErrors(),__METHOD__);
        //var_dump($release->getErrors());
        Yii::debug('Release erstellt (ERROR): '.$release->getErrors(),__METHOD__);
        Yii::error (json_encode($release->getErrors ()));
    }

    /**
    * Prueft, ob sichtbar oder nicht 
    * (sichtbar = freigegeben + Releasezeitraum aktiv)
    * @return boolean
    */
   public function isVisible(){
      if($this->is_released){
         return $this->isActive();
      }
      return false;
   }

    /**
    * Prueft, ob freigegeben, aber Releasezeitraum noch nicht erreicht
    * @return boolean
    */
   public function isWaiting(){
      if($this->is_released && isset($this->from_date)){
        $from = Yii::$app->formatter->asTimestamp($this->from_date);
        if( ($from && (time() < $from)) )
           return true;
      }
      return false;
   }

    /**
    * Prueft, ob freigegeben, aber der Releasezeitraum abgelaufen ist
    * @return boolean
    */
   public function isExpired(){
      if($this->is_released && isset($this->to_date)){
         $to   = Yii::$app->formatter->asTimestamp($this->to_date);
         if( $to <= time() )
            return true;
      }
      return false;
   }
    /**
    * Prueft, ob der Releasezeitraum aktiv ist
+    * @return boolean
    */
   public function isActive(){
      if(isset($this->from_date))
         $from = Yii::$app->formatter->asTimestamp($this->from_date);
      if(isset($this->to_date))
         $to   = Yii::$app->formatter->asTimestamp($this->to_date);
      //beides gefüllt und aktuell liegt dazwischen
      if( isset($from) && isset($to) && $from <= time() && time() <= $to ){
         return true;
      }
      //nur from gefüllt, liegt in Vergangenheit
      if(isset($from) && !isset($to) && $from <= time()  ){
         return true;
      }
      //nur to gefüllt, liegt in Zukunft
      if( !isset($from) && isset($to) && time() <= $to ){
         return true;
      }
      //beides leer
      if( !isset($from) && !isset($to)){
         return true;
      }
      return false;
   }
   
   
    /**
    * Prueft den Releasestatus und gibt eine Textinfo dazu zurueck
    * @return string
    */
   public function getReleasestatusText(){
      switch ($this->getReleasestatus()){
         case 3:
            return "Sichtbar";
         case 2:
            return "Abgelaufen";
         case 1:
            return "Vor Freigabezeitraum";
         case 0:
            return "Nicht freigegeben";
         default:
            throw new \LogicException('Unbekannter Releasestatus');
      }
   }   

    /**
    * (0 = nicht freigegegen, 1 = Freigegeben, 
    * aber Freigabezeitraum abgelaufen, 2 = Freigegeben, aber
    * Freigabezeitraum noch nicht erreicht, 3 = öffentlich
    * @return string
    */
   public function getReleasestatus(){
      if($this->is_released && $this->isActive())
         return 3;//veröffentlicht
      if($this->is_released && isset($this->to_date) && $this->isExpired())
         return 2;//Freigabezeitraum abgelaufen
      if($this->is_released && isset($this->from_date) && $this->isWaiting())
         return 1;//Freigabezeitraum noch nicht erreicht
      if(!$this->is_released)
         return 0;//nicht freigegeben
   }   
   
    /**
    * (0 = nicht freigegegen, 1 = Freigegeben, 
    * aber Freigabezeitraum abgelaufen, 2 = Freigegeben, aber
    * Freigabezeitraum noch nicht erreicht, 3 = öffentlich
    * @return string
    */
   public function getReleasestatusCSSClass(){
      if($this->is_released && $this->isActive())
         return 'released';//veröffentlicht
      if($this->is_released && $this->isExpired())
         return 'expired';//Freigabezeitraum abgelaufen
      if($this->is_released && $this->isWaiting())
         return 'waiting';//Freigabezeitraum noch nicht erreicht
      if(!$this->is_released)
         return 'notreleased';//nicht freigegeben
   }

   public function updateFromRequest(){
        if($this->load(Yii::$app->request->post()) && $this->validate()){
            Yii::debug("Release - saving from_date: ".$this->from_date,__METHOD__);
            Yii::debug("Release - saving to_date: ".$this->to_date,__METHOD__);
            $this->save();
        }
   }
   
    
}
