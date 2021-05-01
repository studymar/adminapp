<?php

namespace app\models\helpers;

use Yii;

/**
 * Description of DateConverter
 * Zum Formatieren vonn Datumswerten
 * @author mwort
 */
class MyJWT {

    static    $divider    = ".";
    static    $header     = ["typ"=>"JWT", "alg"=>"SHA512"];
    static    $duration   = 60*60*12;//Dauer Gültigkeit des Tokens in sec = 1 T
    static    $secret     = "Nothing4EverHappens";
    
    public function __construct() {
    }
    

    
}
