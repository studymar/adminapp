<?php

namespace app\models\voting;

use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * This is the model without db persistence
 */
class Votingresultstatistic
{
    public $countresults        = 0;
    public $sumValues           = 0;
    public $resultstatistics    = [];
    public $active              = 0;
    public $activeStimmen       = 0;
    public $votingweightsstatistic       = [];

    /**
     * Ergebnisstatistik für Fragen mit Gewichtung
     * @param Votingquestion $votingquestion
     * @return Array
     */
    public static function getResultstatisticsWithWeightingRadio($votingquestion)
    {
        $resultstatistic = new Votingresultstatistic();
        
        $resultstatistic->votingweightsstatistic = Votingweights::find()
            ->where(['votingtopic_id'=>$votingquestion->votingtopic->id])
            ->asArray()
            ->all();
        foreach($resultstatistic->votingweightsstatistic as &$vw){
            $vw['votinganswers'] = Votingweights::getVotinganswersOfQuestion($votingquestion->id, $vw['id']);
        }
        
        
        //Anzahl antworten raussuchen
        $resultstatistic->countresults   = Votinganswer::countResultsByAnswerer($votingquestion);

        //Anzahl abgegebener Stimmen
        $resultstatistic->sumValues      = Votinganswer::countResultsByStimmen($votingquestion);
        if($resultstatistic->sumValues == null) $resultstatistic->sumValues = 0;

        //..die Anzahl Stimmen herausfinden
        $resultstatistic->resultstatistics = Votinganswer::getResultStatisticsPerOption($votingquestion, $resultstatistic->sumValues, true);
        
        //active
        $resultstatistic->active         = Votingweights::countActiveVW($votingquestion->votingtopic);
        //activeStimmen
        $resultstatistic->activeStimmen  = $votingquestion->votingtopic->getActiveVotingWeightsStimmen($votingquestion->votingtopic);

        
        return $resultstatistic;
        
    }   

    /**
     * Ergebnisstatistik für Fragen mit Gewichtung
     * @param Votingquestion $votingquestion
     * @return Array
     */
    public static function getResultstatisticsWithWeightingCheckbox($votingquestion)
    {
        $resultstatistic = new Votingresultstatistic();
        
        $resultstatistic->votingweightsstatistic = Votingweights::find()
            ->where(['votingtopic_id'=>$votingquestion->votingtopic->id])
            ->asArray()
            ->all();
        foreach($resultstatistic->votingweightsstatistic as &$vw){
            $vw['votinganswers'] = Votingweights::getVotinganswersOfQuestion($votingquestion->id, $vw['id']);
        }
        
        //Anzahl antworten raussuchen
        $resultstatistic->countresults   = Votinganswer::countResultsByAnswerer($votingquestion);
        //Anzahl abgegebener Stimmen
        $resultstatistic->sumValues      = Votinganswer::countResultsByValues($votingquestion);
        if($resultstatistic->sumValues == null) $resultstatistic->sumValues = 0;

        //..die Anzahl Stimmen herausfinden
        $resultstatistic->resultstatistics = Votinganswer::getResultStatisticsPerOption($votingquestion, $resultstatistic->sumValues);
        
        //active
        $resultstatistic->active         = Votingweights::countActiveVW($votingquestion->votingtopic);

        
        return $resultstatistic;
        
    }   
    

    /**
     * Ergebnisstatistik für Fragen ohne Gewichtung
     * @param Votingquestion $votingquestion
     * @return Array
     */
    public static function getResultstatisticsWithoutWeightingRadioOrCheckbox($votingquestion)
    {
        $resultstatistic = new Votingresultstatistic();
        
        //Anzahl antworten raussuchen
        $resultstatistic->countresults   = Votinganswer::countResultsByAnswers($votingquestion);

        //Anzahl abgegebener Stimmen
        $resultstatistic->sumValues      = Votinganswer::countResultsByAnswers($votingquestion);
        if($resultstatistic->sumValues == null) $resultstatistic->sumValues = 0;

        //..die Anzahl Stimmen herausfinden
        $resultstatistic->resultstatistics = Votinganswer::getResultStatisticsPerOption($votingquestion, $resultstatistic->sumValues, false);
        
        //active
        $resultstatistic->active         = $votingquestion->votingtopic->countActiveAtTopic();

        
        return $resultstatistic;
        
    }   

    
    /**
     * Ergebnisstatistik für Fragen mit Gewichtung
     * @param Votingquestion $votingquestion
     * @return Array
     */
    public static function getResultstatisticsWithoutWeighting($votingquestion)
    {
        $resultstatistic = new Votingresultstatistic();

        //Anzahl antworten raussuchen
        $resultstatistic->countresults   = Votinganswer::countResultsByAnswerer($votingquestion);
        //Anzahl abgegebener Stimmen
        $resultstatistic->sumValues      = Votinganswer::countResultsByValues($votingquestion);
        if($resultstatistic->sumValues == null) $resultstatistic->sumValues = 0;

        //..die Anzahl Stimmen herausfinden
        $resultstatistic->resultstatistics = Votinganswer::getResultStatisticForText($votingquestion);

        //active
        $resultstatistic->active         = $votingquestion->votingtopic->countActiveAtTopic();

        return $resultstatistic;
    }    



    
    
}
