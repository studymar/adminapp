<?php

namespace app\models\voting;

use Yii;
use app\models\voting\Votingtype;
use app\models\voting\Votingoption;
use app\models\voting\Votinganswer;

/**
 * This is the model class for table "votingquestion".
 *
 * @property int $id
 * @property int $votingtopic_id
 * @property int $votingtype_id
 * @property int $active
 * @property int $showresults
 * @property string $question
 * @property int $hasweighting
 * @property int $sort
 *
 * @property Votinganswer[] $votinganswers
 * @property Votingoption[] $votingoption
 * @property Votingtopic $votingtopic
 * @property Votingtype $votingtype
 */
class Votingquestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votingquestion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['votingtopic_id', 'votingtype_id'], 'required'],
            [['votingtopic_id', 'sort'], 'integer'],
            [['active','showresults','hasweighting'], 'boolean'],
            [['question'], 'string'],
            [['votingtopic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Votingtopic::className(), 'targetAttribute' => ['votingtopic_id' => 'id']],
            [['votingtype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Votingtype::className(), 'targetAttribute' => ['votingtype_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'votingtopic_id' => 'Votingtopic ID',
            'votingtype_id' => 'Votingtype ID',
            'active' => 'Active',
            'question' => 'Question',
            'hasweighting' => 'mit Gewichtung',
        ];
    }

    //herausfiltern von felder
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information

        return $fields;
    }    
    
    //angabe, welche relations mit ausgegeben werden sollen
    /**/
    public function extraFields()
    {
        //return ['createdBy'];
        return [];
    }     
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotinganswers()
    {
        return $this->hasMany(Votinganswer::className(), ['votingquestion_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingoptions()
    {
        return $this->hasMany(Votingoption::className(), ['votingquestion_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingtopic()
    {
        return $this->hasOne(Votingtopic::className(), ['id' => 'votingtopic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingtype()
    {
        return $this->hasOne(Votingtype::className(), ['id' => 'votingtype_id']);
    }
    
    /**
     * Gibt den aktuell höchsten Wert für sort zurück
     * @param int $votingtopic_id
     */
    public static function getMaxSort($votingtopic_id)
    {
        return self::find()->where('votingtopic_id = '.$votingtopic_id)->max("sort");
    }    

    /**
     * Sortiert ein Item eine Position höher
     * @return Votingtopic
     */
    public function sortUp()
    {
        //abwärts sortiert, daher nächstes Item finden
        $nextItem  = Votingquestion::find()->where('votingtopic_id = '.$this->votingtopic_id)->andWhere(['sort'=>($this->sort + 1)])->one();
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
     * Setzt sort bei allen Items hinter diesem Sort-Wert zurück
     * Wird nach dem Delete aufgerufen
     * @param int $votingtopic_id
     * @param int $sort
     */
    public static function decreaseSortAfter($votingtopic_id, $sort)
    {
        $items = self::find()->where('votingtopic_id = '.$votingtopic_id)->andWhere('sort > '.$sort)->all();
        if($items){
            foreach($items as $item){
                $item->sort = $item->sort-1;
                $item->save();
            }
        }
    }    

    /**
     * Überspeichert die Daten mit denen aus dem Request
     * Request mit Votingquestion als array haben
     * @param int $votingquestion_id
     */
    public function updateFromRequest()
    {        
        
        if($this->load(Yii::$app->request->post())){
            if($this->active == "true") 
                $this->active = 1;
            else $this->active = 0;
            if($this->showresults == "true") 
                $this->showresults = 1;
            else $this->showresults = 0;
            if($this->hasweighting == "true") 
                $this->hasweighting = 1;
            else $this->hasweighting = 0;
            
            if($this->sort == null)
                $this->sort = Votingquestion::getMaxSort($this->votingtopic_id)+1;
            
            if($this->validate() && $this->save()){
                return true;
            }
            Yii::debug(json_encode($this->getErrors()), __METHOD__);
        }
        else {
            Yii::debug("Votingquestion[] not found in request", __METHOD__);
        }
        return false;
        
        
    }
    

    /**
     * Überspeichert die Votingoptions mit denen aus dem Request
     * Request mit Votingoptions als array haben
     * @param int $votingquestion_id
     */
    public static function updateOptionsFromRequest($p)
    {
        
        //delete vorherige
        Votingoption::deleteAll('votingquestion_id = '.$p);

        //speichern der options
        $items = Yii::$app->request->post("Votingoptions");
        if($items){
            $i = 1;
            foreach($items as $item){
                $new = new Votingoption();
                $new->votingquestion_id = $p;
                $new->id    = 0;
                $new->value = $item["value"];
                $new->save();
            }
        }
        
    }
    
}
