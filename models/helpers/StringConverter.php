<?php

namespace app\models\helpers;

/**
 * Description of StringConverter
 * Zum Formatieren von Strings
 * @author mwort
 */
class StringConverter {

    /**
     * Kovertiert ein als Date erkennbaren Datumsformat zu einem anderen
     * @param string $dateStr Datum als String (Quellformat)
     * @param string $format [optional] Zieldatumsformat (siehe Konstanten) / default: DATETIME_FORMAT_VIEW
     * @return string
     */
    public static function camelCase2Hyphen($string) {
        $string = preg_replace('/(?<=\\w)(?=[A-Z])/',"-$1", $string); 
        $string = strtolower($string);
        return $string;
    }
    
}
