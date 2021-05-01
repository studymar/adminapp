<?php

namespace app\models\helpers;

/**
 * Description of DateConverter
 * Zum Formatieren vonn Datumswerten
 * @author mwort
 */
class DateCalculator {

    //const DATE_FORMAT         = 'php:Y-m-d';
    const SECONDS_OF_MINUTE     = 60;
    const SECONDS_OF_HOUR       = 3600;
    const SECONDS_OF_DAY        = 86400;
 
    /**
     * Rechnet aus, ob ein Datum älter als x Tage ist
     * @param string $timestamp Datum als timestamp-String
     * @param string $days Anzahl Tage
     * @return boolean true Datum ist älter, false Datum ist nicht älter
     */
    public static function isOlderThanXDays($timestamp, $days) {
        if( (time() - $timestamp) > (self::SECONDS_OF_DAY*$days) )
            return true;
        return false;
    }

    /**
     * Prüft, ob das Item an einem anderen Tag als dem Erstellungsdatum geändert wurde
     * Z.b. um danach Updatedatum auszugeben
     * @return boolean
     */
    public static function isUpdatedAnotherDay($created,$updated){
        //tageszahl aus created und updatet vergleichen
        if(DateConverter::convert($created, DateConverter::DATE_FORMAT_VIEW) != DateConverter::convert($updated, DateConverter::DATE_FORMAT_VIEW))
            return true;
        return false;
    }    
  
    
}
