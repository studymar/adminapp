<?php

namespace app\models\helpers;

use Yii;

/**
 * Matching der Pfade und Methoden (GET) zu den RightIds
 * Wird für Authorisierung verwendet im MyHttpBearerAuth
 * Hier eingetragene Matchings können nur mit entsprechenden Rechten aufgerufen
 * werden. Alle Anderen auch ohne.
 * @author mwort
 */
class AuthorityMatching {

    public function __construct() {
    }
    
    public static function matchedActions()
    {
        return [
            //nur erlaubt:
            //'{path}' =>(benötigt bei {method} die rightId XY) 
            //'{path}' => ['method'=>'{method}', rightId => XY]
            /*
            'v1/role/get-roles' => [
                ['method'=>'GET', 'right_id'=>'100'],
            ],
            'v1/rightgroup/get-rightgroups' => [
                ['method'=>'GET', 'right_id'=>'100'],
            ]
            */
            'v1/role/get-roles' => [
                'GET'   => 100,
            ],
            'v1/rightgroup/get-rightgroups' => [
                'GET'   => 100,
            ],
            'v1/roles' => [
                'GET'   => 100,
                'POST'  => 100, //anlegen
                'PUT'   => 100, //ändern
                'PATCH' => 100, //
                'DELETE'=> 100, //delete
            ],
            'v1/navigations' => [
                'GET'   => 101,
            ],
            'v1/navigations/create-item' => [
                'POST'  => 101,
            ],

        ];
    }    

    
}
