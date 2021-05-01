<?php

namespace app\models\voting;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UnprocessableEntityHttpException;

/**
 * This is the model class for table "votinganswer".
 *
 * @property int $id
 * @property string $value
 * @property string $answerer
 * @property int $votingquestion_id
 * @property int $votingweights_id
 * @property int $stimmen
 *
 * @property Votingquestion $votingquestion
 * @property Votingquestion $votingweights
 */
class Votinganswer extends \yii\db\ActiveRecord
{
    public $answers = [];
    public $anz;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votinganswer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'votingquestion_id'], 'required'],
            [['id', 'votingquestion_id','votingweights_id','stimmen'], 'integer'],
            [['value','answerer'], 'string'],
            [['id'], 'unique'],
            [['votingquestion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Votingquestion::className(), 'targetAttribute' => ['votingquestion_id' => 'id']],
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
        ];
    }    
    
    //herausfiltern von felder
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset(
            $fields['answerer'], 
        );

        return $fields;
    }     
    
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'answerer' => 'Answerer',
            'votingquestion_id' => 'Votingquestion ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingquestion()
    {
        return $this->hasOne(Votingquestion::className(), ['id' => 'votingquestion_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingweights()
    {
        return $this->hasOne(Votingweights::className(), ['id' => 'votingweights_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingoptions()
    {
        return $this->hasOne(Votingoption::className(), ['id' => 'votingoption_id']);
    }
    
    /**
     * Speichert die Antworten
     */
    public static function createAnswers($votingquestion,$answers,$votingweights_id = false,$stimmen = false)
    {
        if(is_array($answers)){
            foreach($answers as $answer){
                Votinganswer::create($votingquestion, $answer,$votingweights_id,$stimmen);
            }
        }
        else {
            Votinganswer::create($votingquestion, $answers,$votingweights_id,$stimmen);
        }
        return true;
    }
    
    /**
     * Legt eine Antwort an
     */
    private static function create($votingquestion,$answer,$votingweights_id = false, $stimmen = false){
        $new = new Votinganswer();
        $new->id                = 0;
        $new->votingquestion_id = $votingquestion->id;
        $new->value             = $answer;
        if($votingweights_id)
            $new->votingweights_id  = $votingweights_id;
        if($stimmen)
            $new->stimmen       = $stimmen;
        $new->answerer          = sha1(Yii::$app->request->getUserIP());
        if($new->validate()){
            if($new->save()){
                return true;
            }
            else {
                throw new UnprocessableEntityHttpException(json_encode($new->getErrors()));
            }
        }
        else throw new UnprocessableEntityHttpException(json_encode($new->getErrors()));        
    }
    
    
    /**
     * Ruft die bereits abgegebene Antworten zu aktuellen IP ab
     * @param Votingquestion $votingquestion 
     * @return \yii\db\ActiveQuery
     */
    public static function getAnswersOfIp($votingquestion)
    {
        return Votinganswer::find()->where('votingquestion_id = '.$votingquestion->id)->andWhere("answerer = '". sha1(Yii::$app->request->getUserIP())."'")->all();
    }
    
    /**
     * Ruft die bereits abgegebene Antworten zu aktuellen IP ab
     * @param Votingquestion $votingquestion 
     * @param int $votingweights_id
     * @return \yii\db\ActiveQuery
     */
    public static function getAnswersOfVotingweights($votingquestion, $votingweights_id)
    {
        return Votinganswer::find()->where('votingquestion_id = '.$votingquestion->id)->andWhere("votingweights_id = '".$votingweights_id."'")->all();
    }
    
    /**
     * Zählt die abgegebenen Personen
     * @return int
     */
    public static function countResultsByAnswerer($votingquestion)
    {
        //wenn mit gewichtung, die Anzahl unterschiedlicher votingweights-Antworter zählen
        if($votingquestion->hasweighting)
            return Votinganswer::find()->where('votingquestion_id = '.$votingquestion->id)->select('votingweights_id')->distinct()->count();
        //sonst anzahl unterschiedlicher IPs zählen
        else
            return Votinganswer::find()->where('votingquestion_id = '.$votingquestion->id)->select('answerer')->distinct()->count();
    }

    /**
     * Zählt die abgegebenen Values
     * @return int
     */
    public static function countResultsByValues($votingquestion)
    {
        return Votinganswer::find()->where('votingquestion_id = '.$votingquestion->id)->select('value')->count();
    }

    /**
     * Zählt die abgegebenen Antworten
     * @return int
     */
    public static function countResultsByAnswers($votingquestion)
    {
        return Votinganswer::find()->where('votingquestion_id = '.$votingquestion->id)->select('id')->count();
    }
    
    
    /**
     * Zählt die abgegebenen Stimmen
     * @return int
     */
    public static function countResultsByStimmen($votingquestion)
    {
        return Votinganswer::find()
                ->where('votingquestion_id = '.$votingquestion->id)
                ->joinWith(['votingweights'],false)
                ->sum('votingweights.stimmen');
    }

    

    /**
     * Ergebnisstatistik
     * @param String $value nur Ergebnisse zu einem Values
     * @return int
     */
    public static function getResultStatisticForText($votingquestion,$value = false)
    {
        if($value)
            return Votinganswer::find()
                ->select(['value','count(*) as anz'])
                ->where('votingquestion_id = '.$votingquestion->id)
                ->andWhere("value = '".$value."'")
                ->groupBy('value')
                ->asArray()
                ->all();
        else
            return Votinganswer::find()
                ->select(['value','count(*) as anz'])
                ->where('votingquestion_id = '.$votingquestion->id)
                ->groupBy('value')
                ->asArray()
                ->all();
    }
    
    
    /**
     * Ergebnisstatistik
     * @param String $value nur Ergebnisse zu einem Values
     * @return int
     */
    public static function getResultStatistic($votingquestion,$votingoption_id = false)
    {
        if($votingoption_id){
            $query = new \yii\db\Query();
            return $query->select(['votinganswer.votingoption_id','anz'=>'count(*)'])
                ->from('votinganswer')
                ->where('votinganswer.votingquestion_id = '.$votingquestion->id)
                ->andWhere("votinganswer.votingoption_id = '".$votingoption_id."'")
                ->groupBy(['votinganswer.votingoption_id'])
                ->all();
            /*
            return $query->select(['votingoption.value as value','votinganswer.votingoption_id','anz'=>'count(*)'])
                ->from('votinganswer')
                ->where('votinganswer.votingquestion_id = '.$votingquestion->id)
                ->andWhere("votinganswer.votingoption_id = '".$votingoption_id."'")
                ->leftJoin('votingoption', ['votinganswer.votingoption_id'=>'votingoption.id'])
                ->groupBy(['votinganswer.votingoption_id'])
                ->all();
            //Yii::debug( $query->createCommand()->getSql());
            /*
            return $query->select(['votingoption_id','count(*) as anz'])
                ->from('votinganswer')
                ->where('votingquestion_id = '.$votingquestion->id)
                ->andWhere("votingoption_id = '".$votingoption_id."'")
                ->groupBy(['votingoption_id'])
                ->all();

            return Votinganswer::find()
                ->select(['votingoption_id','count(*) as anz'])
                ->where('votingquestion_id = '.$votingquestion->id)
                ->andWhere("votingoption_id = '".$votingoption_id."'")
                ->groupBy(['votingoption_id'])
                ->asArray()
                ->all();
             
             */
        }
        else
            return Votinganswer::find()
                ->select(['votingoption_id','count(*) as anz'])
                ->where('votingquestion_id = '.$votingquestion->id)
                ->groupBy('votingoption_id')
                ->asArray()
                ->all();
    }

    /**
     * Ergebnisstatistik mit Stimmengewichtung
     * @param String $votingoption_id nur Ergebnisse zu einem Values
     * @return int
     */
    public static function getResultStatisticWithStimmen($votingquestion,$votingoption_id = false)
    {
        if($votingoption_id)
            return Votinganswer::find()
                ->select(['votinganswer.votingoption_id','sum(votingweights.stimmen) as anz'])
                ->where('votinganswer.votingquestion_id = '.$votingquestion->id)
                ->andWhere("votinganswer.votingoption_id = '".$votingoption_id."'")
                ->joinWith(['votingweights' ],false)
                //->groupBy('votinganswer.votingoption_id')
                ->asArray()
                ->one();
            /*
            return Votingweights::find()
                ->select(['votingweights.id','votingweights.name','votinganswer.votingoption_id','sum(votingweights.stimmen) as anz'])
                ->where('votinganswer.votingquestion_id = '.$votingquestion->id)
                ->andWhere("votinganswer.votingoption_id = '".$votingoption_id."'")
                ->joinWith(['votinganswers' ])
                //->groupBy('votinganswer.votingoption_id')
                ->asArray()
                ->all();
             * 
             */
        else
            return Votinganswer::find()
                ->select(['value','sum(stimmen) as anz'])
                ->where('votingquestion_id = '.$votingquestion->id)
                ->groupBy('value')
                ->asArray()
                ->all();
    }

    
    /**
     * Ergebnisstatistik für jede Option des Votings berechnen
     * @param Votingquestion $votingquestion
     * @param int $sumValues Summe der abgegebenen Ergebniseingaben
     * @param boolean $withStimmen Wenn Stimmen per Gewichtung gezählt werden sollen
     * @return Array
     */
    public static function getResultStatisticsPerOption($votingquestion, $sumValues, $withStimmen = false)
    {
        $resultstatistics = [];
        
        $options = $votingquestion->votingoptions;
        //für jede option durchgehen...
        foreach($options as $opt ){
            //..die Anzahl Stimmen herausfinden
            if($withStimmen){
                $results = Votinganswer::getResultStatisticWithStimmen($votingquestion,$opt->id);
                if($results ){
                    $results["percent"]     = round($results["anz"]/$sumValues * 100,2);//prozent berechnen
                    $results["value"]    = $opt->value;
                    //zum resultset hinzufügen
                    $resultstatistics[] = $results;
                }
            }
            else {
                $results = Votinganswer::getResultStatistic($votingquestion,$opt->id);
                //Yii::debug($results);
                if($results ){
                    $results[0]["percent"]  = round($results[0]["anz"]/$sumValues * 100,2);//prozent berechnen
                    $results[0]["value"]    = $opt->value;
                    //zum resultset hinzufügen
                    $resultstatistics[] = $results[0];
                }
            }
            //..falls keine Stimmen, eigenes Objekt mit anz und percent anlegen
            if(!$results ){
                $res = [];
                $res["votingoption_id"] = $opt->id;
                $res["anz"] = 0;
                $res["value"] = $opt->value;
                $res["percent"]= 0;//prozent berechnen
                $resultstatistics[] = $res;
            }
            /*
            else {
                //Yii::debug($results);
                //Yii::debug('SumValues: '.$sumValues);
                //..falls Stimme vorhanden nur percent ausrechnen
                $results["percent"] = round($results["anz"]/$sumValues * 100,2);//prozent berechnen
            }
             */
            //zum resultset hinzufügen
            //$resultstatistics[] = $results;
        }
        return $resultstatistics;
        
    }   


    /**
     * Ergebnisstatistik für jeden Value des Votings berechnen
     * Beispiel: Freie Texteingabe ohne Optionen
     * @param Votingquestion $votingquestion
     * @param int $sumValues Summe der abgegebenen Ergebniseingaben
     * @return Array
     */
    public static function getResultStatisticsPerValue($votingquestion, $sumValues)
    {
        $resultstatistics = [];
        
        //..die Anzahl Stimmen herausfinden
        $results = Votinganswer::getResultStatistic($votingquestion);
        //für jedes result den prozentanteil berechnen
        foreach($results as $res){
            $res["percent"] = round($res["anz"]/$sumValues * 100,2);//prozent berechnen
            //zum resultset hinzufügen
            $resultstatistics[] = $res;
        }

        return $resultstatistics;
        
    }   

    public static function deleteResultOfVW($votingquestion_id, $vw_id)
    {
        return Votinganswer::deleteAll(['votingquestion_id'=>$votingquestion_id, 'votingweights_id'=>$vw_id]);
    }

    public static function deleteResultOfQuestion($votingquestion_id)
    {
        return Votinganswer::deleteAll(['votingquestion_id'=>$votingquestion_id]);
    }
    
    
}
