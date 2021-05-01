<?php

namespace app\models\helpers;

/**
 * Description of DateConverter
 * Zum Formatieren vonn Datumswerten
 * @author mwort
 */
class DateConverter {

    //const DATE_FORMAT         = 'php:Y-m-d';
    const DATE_FORMAT_DB        = 'php:Y-m-d';
    const DATETIME_FORMAT_DB    = 'php:Y-m-d H:i:s';
    const DATE_FORMAT_VIEW      = 'php:d.m.Y';
    const DATETIME_FORMAT_VIEW  = 'php:d.m.Y H:i';
    const DATETIME_FORMAT_VIEW_SEC = 'php:d.m.Y H:i:s';
    const TIME_FORMAT           = 'php:H:i:s';
 
    /**
     * Kovertiert ein als Date erkennbaren Datumsformat zu einem anderen
     * @param string $dateStr Datum als String (Quellformat)
     * @param string $format [optional] Zieldatumsformat (siehe Konstanten) / default: DATETIME_FORMAT_VIEW
     * @return string
     */
    public static function convert($dateStr, $format = null) {
        if($dateStr){
            $fmt = ($format == null) ? self::DATETIME_FORMAT_VIEW : $format;
            return \Yii::$app->formatter->asDate($dateStr, $fmt);
        }
        else
            return null;
    }
    
    /**
     * Kovertiert ein als Date erkennbaren Datumsformat zu timestamp
     * @param string $dateStr Datum als String (quelle)
     * @return string (timestamp)
     */
    public static function convertToTimestamp($dateStr) {
        if($dateStr){
            return \Yii::$app->formatter->asTimestamp($dateStr);
        }
        else
            return null;
    }    
    
}
