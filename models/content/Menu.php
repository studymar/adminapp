<?php

namespace app\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use app\models\content;


/**
 * Menu beschreibt das Hauptmenu mit seinen Unterseiten als Tree
 * 
 */
class Menu
{

    /**
     * Gibt den Tree einer Seite als Arrays mit SeitenIDs zurück
     * D.h. alle Seiten über dieser Seite, bis eine keinen Parent mehr hat
     * Die Seite selbst wird nicht zurückgegeben
     * @return Array mit PageIds
     */
    public static function getParenttreeForPage($page)
    {
        //alle parants auflisten
        $parents        = [];
        //bis nach ganz oben iterieren
        $iteratorPage   = $page;
        while($iteratorPage->parentpage_id != null){
            $parents[]    = $iteratorPage->parentpage_id;
            $iteratorPage = $iteratorPage->parentpage;
        }
        return $parents;
    }
    
}
