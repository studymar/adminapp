<?php

/* 
 * Startseite der Votings für EDIT
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\assets\VueAsset;
VueAsset::register($this);


if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/voting/';
else $env_vuecomponent_dir = "vue-components/voting/";


?>

    <div class="">
        <div class="d-flex justify-content-between title-block">
            <h2>Home</h2>
        </div>
        <div class="content-block">
        
            <div class="col-sm-12 h-100 p-0 row">
                <div class="" >
                    Bitte wählen Sie einen Menüpunkt aus.
                </div>
            </div>
        </div>
        <br/><br/>

    </div>

    
