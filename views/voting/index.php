<?php

/* 
 * Startseite der Votings
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\assets\VueAsset;
VueAsset::register($this);

/**
if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir = "vue-components/content/";

include_once($env_vuecomponent_dir.'ReleaseComponent.php');
**/
?>

    <div id="listOfVotings" class="voting-list">
        <h2>Aktuelle Umfrage/Abstimmungs-Themen</h2>

        <p class="alert alert-danger" v-if="config.topErrorLoading">{{config.topErrorLoadingMessage}}</p>
        <p class="alert alert-info" v-if="items == null || items.length == 0">{{config.topNoVotingTopics}}</p>

        <div class="list-group" v-if="items != null && items.length>0" >
           <a v-bind:href="config.showTopic + item.id" v-for="item in items" :key="item.id" class="list-group-item list-group-item-action flex-column align-items-start">
              <div class="d-flex w-100 justify-content-between">
                 <h5 class="mb-1">{{item.headline}}</h5>
              </div>
            <p class="mb-1">{{item.description}}</p>
           </a>
        </div>

        <br/><br/>
                    
    </div>

    <script type="text/javascript">    
    new Vue({
        el: '#listOfVotings',
        components: {
        },
        data: {
            config: {
                topNoVotingTopics: 'Zur Zeit sind keine Abstimmungen aktiv.',
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                getLink: '/voting/get-topics/',
                showTopic: '<?= Url::toRoute(['voting/vote']).'/' ?>'
            },
            votingActivated: false,
            items: [
            ]
        },
        mounted: function() {
            //alle 10sec neuladen
            this.onLoad();
        },
        // define methods under the `methods` object
        methods: {
            onLoad() {
                //alle 10s aktualisieren
               this.getItems();
               setInterval(this.getItems, 10000);
            },
            getItems() {
                //item laden
                self = this;
                $.post(self.config.getLink,
                {
                })
                .done(function(data) {
                    self.items = data
                    self.config.topErrorLoading = false
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error loading item" )
                });
            }
        }
    });
        
    </script>
    
