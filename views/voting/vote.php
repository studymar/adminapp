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

include_once($env_vuecomponent_dir.'CheckboxComponent.php');
include_once($env_vuecomponent_dir.'RadioComponent.php');
include_once($env_vuecomponent_dir.'TextComponent.php');
include_once($env_vuecomponent_dir.'ResultstatisticsComponent.php');
include_once($env_vuecomponent_dir.'ChooseweightinguserComponent.php');


?>

    <div id="listOfVotings" class="voting-list">
        <h2>{{topic.headline}}</h2>

        <p class="alert alert-danger" v-if="config.topErrorLoading">{{config.topErrorLoadingMessage}}</p>
        <p class="alert alert-info" v-if="voting == null && view == 'vote'">{{config.topNoVotingQuestions}}</p>

        <chooseweightingusercomponent
            v-if="view == 'choose'"
            v-bind:topic="topic"
            v-bind:weightuser="weightuser"
            >
        </chooseweightingusercomponent>                    

        
        <div class="list-group" v-if="view != 'choose' && voting != null" >
            <div class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between header">
                    <h4 class="mb-1">{{voting.question}}</h4>
                    <small>{{countresults}} Person<span v-if="countresults!=1">en haben</span><span v-if="countresults==1"> hat</span>  abgestimmt</small>
                </div>

                <p class="alert alert-danger" v-if="config.topErrorVotingConfig">{{config.topErrorVotingConfigMessage}}</p>

                <form v-on:submit.prevent="saveVoting" v-if="!resultstatistics.length">

                <textcomponent
                    v-if="view == 'vote' && voting && voting.votingtype && voting.votingtype.name == 'text'" 
                    v-bind:myanswers="myanswers"
                    >
                </textcomponent>

                <checkboxcomponent
                    v-if="view == 'vote' && voting && voting.votingtype && voting.votingtype.name == 'checkbox'" 
                    v-for="item in voting.votingoptions" 
                    :key="item.id"
                    v-bind:item="item"
                    >
                </checkboxcomponent>

                <radiocomponent 
                    v-if="view == 'vote' && voting && voting.votingtype && voting.votingtype.name == 'radio'" 
                    v-for="item in voting.votingoptions" 
                    :key="item.id"
                    v-bind:item="item"
                    v-bind:answer="voting.answer"
                    >
                </radiocomponent>
                
                <div v-if="view == 'vote'">
                    <div class="d-flex w-100 justify-content-between header">
                        <div>
                            <button type="submit" class="btn btn-primary" v-if="!myanswers.length  && voting.votingoptions.length>0|| voting.votingtype.name=='text'">Abschicken</button>
                            <small v-if="myanswers.length>0 && voting.votingtype.name!='text' ">Sie haben abgestimmt</small>
                        </div>
                        <small>{{countresults}} Person<span v-if="countresults!=1">en haben</span><span v-if="countresults==1"> hat</span>  abgestimmt</small>
                    </div>
                </div>
            </form>
                
            <resultstatisticscomponent 
                v-if="view == 'result'"
                v-bind:resultstatistics="resultstatistics"
                v-bind:sum-values="sumValues"
                >

            </resultstatisticscomponent>
                
            </div>
        </div>

        <br/><br/>
                    
    </div>

    <script type="text/javascript">    

    new Vue({
        el: '#listOfVotings',
        components: {
            checkboxcomponent,
            radiocomponent,
            textcomponent,
            resultstatisticscomponent,
            chooseweightingusercomponent
        },
        data: {
            view: null, // null, vote, result
            config: {
                topNoVotingQuestions: 'Bitte warten Sie bis eine Umfrage/Abstimmung vom Moderator aktiviert wird.',
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                topErrorVotingConfig: false,
                topErrorVotingConfigMessage: 'Zur Umfrage wurden noch keine Auswahlmöglichkeiten konfiguriert.',
                showTopic: '<?= Url::toRoute(['voting/topic']).'/' ?>',
                getLink: '/voting/get-voting-of-topic/<?= $model->id ?>',
                saveLink: '/voting/save-voting-of-question/'
            },
            topic: {
                id: 1,
                headline: "TTKV Kreisverbandstag 2020",
                description: "Abstimmungen auf dem Kreisverbandstag",
                votingweights: []
            },
            weightuser: null,
            voting: {
                id: 1,
                question: 'Genehmigung des Protokolls',
                active: 1,
                votingtype: {
                    name: 'checkbox'
                },
                votingoptions: [
                    {
                        id: '1',
                        answer: 'Ja'
                    },
                    {
                        id: '2',
                        answer: 'Nein'
                    },
                    {
                        id: '3',
                        answer: 'Enthaltung'
                    }
                ]
            },
            countresults: 0,
            answer: [],
            myanswers: [],
            resultstatistics: [],
            sumValues: 0
        },
        mounted: function() {
            //alle 10sec neuladen
            this.onLoad();
        },
        // define methods under the `methods` object
        methods: {
            onLoad() {
                //alle 10s aktualisieren
               this.getVoting();
               setInterval(this.getVoting, 10000);
            },
            getVoting() {
                //voting und topic laden
                self = this;
                var getLink = self.config.getLink
                if(self.topic.votingweights.length && self.weightuser)
                    getLink+= '/' + self.weightuser.id
                $.post(getLink,
                {
                })
                .done(function(data) {
                    //topic + votingquestion eintragen
                    self.topic      = data.topic
                    self.voting     = data.voting
                    //fehler ausgeben, wenn zu votingquestion keine Auswahloption vorhanden und es keine text-abfrage ist
                    if(self.voting && self.voting.votingtype.name != 'text' && self.voting.votingoptions.length==0){
                        console.log(self.config.topErrorVotingConfigMessage)
                        self.config.topErrorVotingConfig = true
                    }
                    else
                        self.config.topErrorVotingConfig = false
                    //Bereits agegebene Stimmen eintragen
                    self.myanswers  = data.myanswers
                    //wenn myanswer gefüllt (also stimme bereits abgegeben)
                    //auch answer damit füllen, damit dies als ausgewählt angezeigt (gecheckt) wird
                    //(nur, wenn eigene answer noch nicht geändert)
                     if(self.answer.length == 0 && self.voting && self.voting.type != 'text'){ //bei Text ist die aktuelle answer immer frei und wird durch Ergebnisse überschrieben
                        self.answer  = []
                        self.myanswers.forEach(function(item){
                            self.answer.push(item.value)
                        })
                    }
                    
                    //anzahl Personen mit abgegebener Stimme eintragen (wird immer angezeigt)
                    self.sumValues  = data.sumValues 
                    //anzahl abgegebener Stimmen eintragen
                    self.countresults = data.countresults
                    //Umfragestatistik eintragen (kommt nur, wenn vorher bereits abgestimmt und Ergebnis freigegeben)
                    self.resultstatistics = data.resultstatistics // Stimmenergebnis
                    
                    self.config.topErrorLoading = false
                    
                    //view setzen
                    if( self.topic.votingweights.length >0 && self.weightuser == null)
                        self.view = 'choose'
                    else if (self.resultstatistics.length > 0)
                        self.view= 'result'
                    else
                        self.view = 'vote'
                    
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error loading item" )
                });
            },
            saveVoting() {
                    self = this;
                    
                    //id des users mitschicken, wenn vorhanden
                    var weightuser_id = null
                    if(self.weightuser != null)
                        weightuser_id = self.weightuser.id

                    //speichern
                    $.post(self.config.saveLink + self.voting.id,
                    {
                        "Votinganswer[value]": self.answer,
                        "Votinganswer_votingweights_id": weightuser_id
                    })
                    .done(function(data) {
                        console.log(data)
                        self.myanswers = data.myanswers
                        self.countresults = data.countresults
                        //wenn myanswer gefüllt (also stimme bereits abgegeben)
                        //auch answer damit füllen, damit dies als ausgewählt angezeigt (gecheckt) wird
                        self.answer  = []
                        if(self.voting.votingtype.name != 'text'){
                            self.myanswers.forEach(function(item){
                                self.answer.push(item.value)
                            })
                        }
                        self.config.topErrorLoading = false
                    })
                    .fail(function() {
                        self.config.topErrorLoading = true
                        console.log( "error saving item" )
                    });                
            }
            
        }
    });
        
    </script>
    
