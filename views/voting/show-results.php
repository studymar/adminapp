<?php

/* 
 * Zeigt aktuelles Voting
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\assets\VueAsset;
VueAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir = "vue-components/voting/";

include_once($env_vuecomponent_dir.'ResultstatisticsComponent.php');
include_once($env_vuecomponent_dir.'VotingweightsstatisticComponent.php');


?>

    <div id="listOfVotings" class="voting-list">
        <h2>{{votingquestion.question}}</h2>

        <p class="alert alert-danger" v-if="config.topErrorLoading">{{config.topErrorLoadingMessage}}</p>
        
            <resultstatisticscomponent 
                v-bind:resultstatistics="resultstatistics"
                v-bind:sum-values="sumValues"
                >

            </resultstatisticscomponent>
            <br/><br/>

            <votingweightsstatisticcomponent 
                v-bind:votingweightsstatistic="votingweightsstatistic"
                v-bind:sum-abgestimmt="countresults"
                v-bind:sum-stimmen="sumValues"
                >

            </votingweightsstatisticcomponent>
        
        
            </div>
        </div>

        <br/><br/>
                    
    </div>

    <script type="text/javascript">    

    new Vue({
        el: '#listOfVotings',
        components: {
            resultstatisticscomponent,
            votingweightsstatisticcomponent
        },
        data: {
            config: {
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                topErrorVotingConfig: false,
                topErrorVotingConfigMessage: 'Zur Umfrage wurden noch keine Auswahlmöglichkeiten konfiguriert.',
                getResultsLink: '/voting/get-results/<?= $model->id ?>'
            },
            votingquestion: {
                question: ''
            },
            countresults: "0",
            resultstatistics: [],
            sumValues: '0',
            votingweightsstatistic: []
        },
        mounted: function() {
            //alle 10sec neuladen
            this.onLoad();
        },
        // define methods under the `methods` object
        methods: {
            onLoad() {
                //alle 10s aktualisieren
               this.getResults();
               setInterval(this.getResults, 10000);
            },
            getResults() {
                //voting und topic laden
                self = this;
                $.post(self.config.getResultsLink,
                {
                })
                .done(function(data) {
                    //question
                    self.votingquestion  = data.votingquestion 
                    //anzahl Personen mit abgegebener Stimme eintragen (wird immer angezeigt)
                    self.sumValues  = data.sumValues 
                    //anzahl abgegebener Stimmen eintragen
                    self.countresults = data.countresults
                    //Umfragestatistik eintragen (kommt nur, wenn vorher bereits abgestimmt und Ergebnis freigegeben)
                    self.resultstatistics = data.resultstatistics // Stimmenergebnis
                    self.votingweightsstatistic = data.votingweightsstatistic
                    
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
    
