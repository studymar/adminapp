<?php

namespace app\models\voting;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use app\models\voting\Votingweights;
use app\models\user\User;

/**
 * This is the model class for table "votingtopic".
 *
 * @property string $headline
 * @property string $description
 * @property int $active
 * @property int $sort
 * @property string $created
 * @property int $created_by
 *
 * @property User $createdBy
 * @property Votingweights[] $votingweights
 * @property Votingquestions[]
 */
class Votingtopic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votingtopic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','sort','created_by'], 'integer'],
            [['active'], 'boolean'],
            [['description'], 'string'],
            [['created'], 'safe'],
            [['headline'], 'required'],
            [['headline'], 'string', 'max' => 50],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'headline' => 'Thema',
            'description' => 'Beschreibung',
            'active' => 'Active',
            'created' => 'Created',
            'created_by' => 'Created By',
        ];
    }

    //herausfiltern von felder
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset(
            $fields['created_by'], 
        );

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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingweights()
    {
        return $this->hasMany(Votingweights::className(), ['votingtopic_id' => 'id']);
    }    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingquestions()
    {
        return $this->hasMany(Votingquestion::className(), ['votingtopic_id' => 'id'])->orderBy('sort asc')->with('votingtype');
    }    
    
    /**
     * Liste der öffentlich sichtbarem Votingtopics
     * @return ActiveRecord[]
     */
    public static function getPublicVotingtopics()
    {
        return self::find()->where('active = 1')->orderBy('sort desc')->all();
    }    

    /**
     * Liste aller Votingtopics
     * @return ActiveRecord[]
     */
    public static function getAllVotingtopics()
    {
        return self::find()->orderBy('sort desc')->with('votingweights')->asArray()->all();
    }    
    
    /**
     * Sortiert ein Item eine Position höher
     * @return Votingtopic
     */
    public function sortUp()
    {
        //abwärts sortiert, daher nächstes Item finden
        $nextItem  = Votingtopic::find()->where(['sort'=>($this->sort + 1)])->one();
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
     * Gibt den aktuell höchsten Wert für sort zurück
     */
    public static function getMaxSort()
    {
        return Votingtopic::find()->max("sort");
    }    
    
    /**
     * Setzt sort bei allen Items hinter diesem Sort-Wert zurück
     * Wird nach dem Delete aufgerufen
     * @param int $sort
     */
    public static function decreaseSortAfter($sort)
    {
        $items = self::find()->where('sort > '.$sort)->all();
        if($items){
            foreach($items as $item){
                $item->sort = $item->sort-1;
                $item->save();
            }
        }
    }    
    
    /**
     * Erstellt ein Votingtopic
     * @param String $headline
     */
    public static function create($headline)
    {
       $item = new Votingtopic();
       $item->id        = 0;
       $item->sort      = self::getMaxSort()+1;
       $item->headline  = $headline;
       if($item->save())
           return $item;
       else return false;
    }    

    /**
     * Löscht ein Votingtopic und seine Votings
     */
    public function deleteTopic()
    {
       foreach($this->getVotingquestions() as $votingquestion){
           $votingquestion->delete();           
       }
       foreach($this->votingweights as $votingweight){
           $votingweight->delete();           
       }
       if($this->delete())
           return true;
       else return false;
    }    

    /**
     * Updatet das Objekt mit den Requestdaten
     * @param Array $objReq Item als Array
     * @throws Exception
     */
    public function updateFromRequest(){
        Yii::debug("UpdateFromRequest VotingTopic",__METHOD__);
        //Normalfall, wenn nichts übergeben
        $req = Yii::$app->request->post(); // falls nicht übergeben
        if($this->load($req) ){
            if($this->active == "true") 
                $this->active = 1;
            else $this->active = 0;
            
            if($this->validate()){
                if(!$this->save()){
                    throw new Exception (Errormessages::$errors['DATASAVINGERROR']['message'], Errormessages::$errors['DATASAVINGERROR']['id']);
                }
                return true;
            }
        }
        throw new Exception (Errormessages::$errors['DATASAVINGERROR']['message'], Errormessages::$errors['DATASAVINGERROR']['id']);

    }    
    
    
    public function getActiveVotingWeights(){
        return Votingweights::find()->where(['votingtopic_id'=>$this->id, 'active'=>1 ])->count();
    }

    public function getActiveVotingWeightsStimmen(){
        return Votingweights::find()->where(['votingtopic_id'=>$this->id, 'active'=>1 ])->sum('stimmen');
    }
    
    /**
     * Gibt die höchste Anzahl von Antworten in den Questions des Topic zurück
     * @return int
     */
    public function countActiveAtTopic() {
        $anz = 0;
        foreach($this->votingquestions as $question){
            $maxAnswers = Votinganswer::countResultsByAnswerer($question);
            if($maxAnswers > $anz)
                $anz = $maxAnswers;            
        }
        return $anz;
    }

    
}
