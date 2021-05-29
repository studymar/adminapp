<?php
use yii\helpers\Url;

/**
 * Shows on an editpage the teaserlist component
 * Preconditions:
 * - pageHasTemplate given as props
 */
/* vue laden */
use app\assets\VueAsset;
VueAsset::register($this);

/*
if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/voting/';
else $env_vuecomponent_dir = "vue-components/voting/";
*/
//include_once($env_vuecomponent_dir.'TeaserlistSortComponent.php');

?>

<template id="voting-question-result-component">
    <div id="votingResultComponent" class="p-2">
        
        <div class="d-flex justify-content-between title-block">
            <h3 class="fs-5">
                <a href="" class="onlySmall" v-on:click.prevent="$parent.closeTopic()">
                    <i class="material-icons">keyboard_arrow_left</i>
                </a>
                {{topic.headline}}
                <br>
                {{question.question}}
            </h3>
        </div>

        <p class="alert alert-danger" v-if="config.getResultsError">{{config.getResultsErrorMessage}}</p>
        <p class="alert alert-danger" v-if="config.deleteError">{{config.deleteErrorMessage}}</p>
        
        
        <div class="table-responsive">
        <table class="table caption-top" v-if="question.hasweighting=='1' && question.votingtype.name != 'text'">
            <caption>
                <div v-for="(option,index) in resultstatistics" :key="option.id">{{index + ': ' + option.value }}</div>
            </caption>
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Name</th>
                    <th scope="col" v-for="(option,index) in resultstatistics" :key="option.id">{{index }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(vw,index) in votingweightsstatistic" :key="vw.id">
                    <td scope="row">{{index}}</th>
                    <td>{{vw.name}}</td>
                    <td v-for="(option,index) in resultstatistics" :key="option.index">
                        <span v-for="answer in vw.votinganswers" :key="answer.id" v-if="vw.votinganswers.length > 0">
                            <span v-if="answer.votingoption_id == option.votingoption_id">
                                {{(question.votingtype.name == 'radio')? vw.stimmen : '1'}}
                            </span>
                        </span>
                        <span v-else>
                            0
                        </span>
                    </td>
                    <td><a href="#" class="material-icons" v-on:click="deleteVotingOfVW(vw.id)">delete</a></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="row">=</th>
                    <th>Gesamt</th>
                    <th v-for="(option,index) in resultstatistics" :key="option.id">{{option.anz}} ({{option.percent}}%)</th>
                </tr>
            </tfoot>
        </table>
        <table class="table caption-top" v-if="question.votingtype.name != 'text'">
            <thead>
                <tr>
                    <th scope="col">Antwort</th>
                    <th scope="col" v-if="question.hasweighting=='1' && question.votingtype.name == 'radio'">Stimmen</th>
                    <th scope="col" v-else>Häufigkeit</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(res,index) in resultstatistics" :key="res.value">
                    <td scope="row">{{res.value}}</th>
                    </td>
                    <td scope="row">{{res.anz}}</th>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table caption-top" v-if="question.votingtype.name == 'text'">
            <thead>
                <tr>
                    <th scope="col">Antwort</th>
                    <th scope="col">Häufigkeit</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(res,index) in resultstatistics" :key="res.value">
                    <td scope="row">{{res.value}}</th>
                    </td>
                    <td scope="row">{{res.anz}}</th>
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <div>Gesamtzahl Abstimmungen: {{countresults}}</div>
            <div>Gesamtzahl abgegebener Stimmen: {{sumValues}}</div>
            <div>
                Gesamtzahl anwesender Personen: {{active}}
                <br>
                (= höchste Anzahl Abstimmungen in der Veranstaltung)
            </div>
            <div v-if="question.hasweighting=='1' && question.votingtype.name == 'radio'">Gesamtzahl anwesender Stimmen: {{activeStimmen}}</div>

        </div>
            
        <br/>
        <a href="" v-on:click.prevent="$parent.closeTopicQuestionEdit()" class="btn btn-outline-primary" id="cancel-button">Zurück</a>
            
        </div>

        <br/><br/>
    </div>
</template>

<script type="text/javascript">

var votingQuestionResultComponent = {
    template: '#voting-question-result-component',
    props: ['topic','question'],
    components: {
    },
    data: function(){
        return {
            config: {
                getResultsLink: '<?= Url::toRoute(['voting/get-results']).'/' ?>',
                getResultsError: false,
                getResultsErrorMessage: 'Ergebnisse konnten nicht geladen werden.',
                deleteVWLink: '<?= Url::toRoute(['voting/delete-results-of-vw']).'/' ?>',
                deleteError: false,
                deleteErrorMessage: 'Ergebnisse konnten nicht gelöscht werden.'
            },
            countresults: null,
            resultstatistics: null,
            sumValues: null,
            votingweightsstatistic: null,
            active: null,
            activeStimmen: null,
            intervall: null
        }
    },
    mounted: function() {
        this.onLoad()
    },
    beforeDestroy: function() {
        clearInterval(this.intervall);
    },
    watch: {
    },
    methods: {
            onLoad() {
                //alle 10s aktualisieren
               this.getResults();
               this.intervall = setInterval(this.getResults, 10000);
            },
            getResults() {
                //item laden
                self = this;
                $.get(self.config.getResultsLink + this.question.id)
                .done(function(data) {
                    if(data.result){
                        self.countresults       = data.result.countresults
                        self.resultstatistics   = data.result.resultstatistics
                        self.sumValues          = data.result.sumValues
                        self.votingweightsstatistic = data.result.votingweightsstatistic
                        self.active             = data.result.active
                        self.activeStimmen      = data.result.activeStimmen
                        self.config.getResultsError = false
                    }
                    else {
                        self.countresults       = null
                        self.resultstatistics   = null
                        self.sumValues          = null
                        self.votingweightsstatistic = null
                        self.config.getResultsError = true
                    }
                    if(self.$parent.view != "question-result" || (self.$parent.question != null && self.$parent.question.id != self.question.id))
                        clearInterval(this.intervall);
                })
                .fail(function() {
                    self.countresults       = null
                    self.resultstatistics   = null
                    self.sumValues          = null
                    self.votingweightsstatistic = null
                    self.config.getResultsError = true
                });
            },
            deleteVotingOfVW(vw_id){
                self = this;
                $.get(self.config.deleteVWLink + self.question.id + '/' + vw_id)
                .done(function(data) {
                    if(!data.deleted){
                        //error
                        self.config.deleteError = true
                    }
                    else {
                        //success
                        self.config.deleteError      = false
                        self.getResults()
                    }
                })
                .fail(function() {
                    self.config.deleteError = true
                });
                
            }
    }
}

</script>
