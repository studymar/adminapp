<?php

namespace app\models\helpers;

use Yii;

/**
 * Description of DateConverter
 * Zum Formatieren vonn Datumswerten
 * @author mwort
 */
class AllowedDomains {

    public function __construct() {
    }
    
    public static function allowedDomains()
    {
        return [
            //nur erlaubt:
            //'*', // star allows all domains
            'http://localhost:4200',
            'http://localhost:3000',
            'http://dist.localhost',
            'http://ttkv-harburg.de',
            'https://ttkv-harburg.de',
        ];
    }    

    
}
