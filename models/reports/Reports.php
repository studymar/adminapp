<?php

namespace app\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use app\models\user\User;
use app\models\forms\PaginationForm;


/**
 * Generating Reports.
 *
 */
class Reports extends \yii\db\ActiveRecord
{
    /**
     * Images without File
     * @return []
     */
    public static function getImagesWithoutFiles(){
    }

    /**
     * Documents without File
     * @return []
     */
    public static function getDocumentsWithoutFiles(){
    }
    
    
}
