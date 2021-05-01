<?php
use yii\helpers\Url;

/**
 * Liste der Veranstaltungen
 */

/* vue laden */
use app\assets\FormAsset;
FormAsset::register($this);
use app\assets\VueAsset;
VueAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir_tp = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir_tp = "vue-components/content/";

include_once($env_vuecomponent_dir_tp.'TextfieldComponent.php');

?>

<template id="voting-topic-config-component">

        <div class="p-2">
            <div class="d-flex justify-content-between title-block">
                <h3 class="fs-5">Veranstaltung editieren</h3>
            </div>

            <p class="alert alert-danger" v-if="config.saveError">{{config.saveErrorMessage}}</p>

            <validation-observer v-slot="{ handleSubmit }">
            <form v-on:submit.prevent="handleSubmit(onSubmit)" method="POST">
                <textfieldcomponent
                    v-model="item.headline"
                    label="Name"
                    fieldname="VotingTopic[headline]"
                    v-bind:errors="errors"
                    v-bind:servererror="getErrormessage('headline')"
                    >
                </textfieldcomponent>
                
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="topicactive" v-model="item.active">
                  <label class="form-check-label" for="topicactive">Aktiv</label>
                </div>                
                <br/>
                <button type="submit" id="submit-button" class="btn btn-primary">Speichern</button>
                <a href="" v-on:click.prevent="$parent.closeTopicConfiguration()" class="btn btn-outline-primary" id="cancel-button">Zurück</a>
                
                <br/><br/>
                
                <div id="votingweights mb-1">
                    <div class="fs-6 title-block mb-1">Gewichtung</div>
                    <span v-if="votingweights.length == 0">
                        <div class="row font-italic">
                            Keine Gewichtung, jeder hat 1 Stimme
                        </div>
                        <div class="row">
                          <div class="col-12 p-1 text-right">
                              <a href="" v-on:click.prevent="addVotingWeight()" class="btn btn-outline-primary"><div class="material-icons">add</div></a>
                          </div>
                        </div>
                    </span>                    
                    <span v-if="votingweights.length > 0">
                        <div class="row">
                          <div class="col-1 p-2">
                              Nr
                          </div>
                          <div class="col-7 p-2">
                              Name
                          </div>
                          <div class="col-2 p-2">
                              Stimmen
                          <div class="col-2 p-2">
                          </div>
                        </div>
                        <div class="row" v-for="(item,index) in votingweights" :key="item.id">
                          <div class="col-1 p-2">
                              {{index + 1}}
                          </div>
                          <div class="col-7 p-1">
                            <input type="text" class="form-control" placeholder="Name" aria-label="Name" name="VotingWeights[name]" v-model="item.name">
                          </div>
                          <div class="col-2 p-1">
                            <input type="text" class="form-control" placeholder="1" aria-label="Stimmen" name="VotingWeights[stimmen]" v-model="item.stimmen">
                          </div>
                          <div class="col-2 p-1">
                            <a href="" class="btn btn-outline-light" v-on:click.prevent="deleteVotingWeight(index)"><div class="material-icons">delete</div></a>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-10 p-1 font-italic">
                              User wählt Name vor Abstimmung aus
                          </div>
                          <div class="col-2 p-1 text-right">
                              <a href="" v-on:click.prevent="addVotingWeight()" class="btn btn-outline-primary"><div class="material-icons">add</div></a>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12 p-1">
                              <a href="" v-on:click.prevent="saveVotingWeights()" class="btn btn-primary">Gewichtung speichern</a>
                          </div>
                        </div>
                    </span>
                </div>

            </form>
            </validation-observer>

        </div>
    
</template>

<script type="text/javascript">
Vue.component('validation-observer', VeeValidate.ValidationObserver);

var votingTopicConfigComponent = {
    template: '#voting-topic-config-component',
    props: ['topic'],
    components: {
        textfieldcomponent
    },
    data: function(){
        return {
            config: {
                saveLink: '<?= Url::toRoute(['voting/save-topic']).'/' ?>',
                saveError: false,
                saveErrormessage: 'Fehler: Veranstaltung konnte nicht gespeichert werden',
                getVWLink: '<?= Url::toRoute(['voting/get-all-votingweights-of-topic']).'/' ?>',
                getVWError: false,
                getVWErrormessage: 'Fehler: Gewichtung konnte nicht geladen werden',
                saveVWLink: '<?= Url::toRoute(['voting/save-voting-weights-of-topic']).'/' ?>',
                saveVWError: false,
                saveVWErrormessage: 'Fehler: Gewichtung konnte nicht gespeichert werden'
            },
            item: {
                headline: "",
                description: "",
                active: false
            },
            votingweights: [],
            errors: [],
            servererrors: []
            
        }
    },
    computed: {
    },
    mounted: function() {
        this.item = this.topic
        this.getVotingWeights()
    },
    methods: {
        getErrormessage(fieldname){
            if(this.servererrors && this.servererrors.hasOwnProperty(fieldname))
                return this.servererrors[fieldname];
            else false;
        },
        onSubmit(){
            self = this
            $.post(self.config.saveLink + self.topic.id,
            {
                "Votingtopic[headline]": this.item.headline,
                "Votingtopic[active]": this.item.active
            })
            .done(function(data) {
                if(!data.saved){
                    //error
                    self.saveError = true
                    self.errorMessages = data.errormessages
                }
                else {
                    //success
                    self.saveError      = false
                    self.servererrors   = []
                    self.$parent.$refs.votingTopicList.getItems()
                    self.$parent.closeTopicConfiguration()
                }
            })
            .fail(function() {
                
            });
        },
        getVotingWeights(){
            self = this
            $.get(self.config.getVWLink + self.topic.id)
            .done(function(data) {
                if(!data.items){
                    //error
                    self.getVWError = true
                }
                else {
                    //success
                    self.getVWError     = false
                    self.votingweights  = data.items
                }
            })
            .fail(function() {
                self.getVWError      = true
                console.log('Gewichtung konnte nicht geladen werden')
            });
        },
        addVotingWeight(){
            this.votingweights.push({
                'name': '',
                'stimmen': '1',
                'id': '0'
            })
        },
        saveVotingWeights(){
            self = this
            $.post(self.config.saveVWLink + self.topic.id,
            {
                "Votingweights": self.votingweights
            })
            .done(function(data) {
                if(!data.saved){
                    //error
                    self.saveError = true
                    self.errorMessages = data.errormessages
                }
                else {
                    //success
                    self.saveError      = false
                    self.servererrors   = []
                    self.$parent.$refs.votingTopicList.getItems()
                    self.$parent.closeTopicConfiguration()
                }
            })
            .fail(function() {
                
            });
        },
        deleteVotingWeight(index){
            this.votingweights.splice(index, 1);
        },
    }
}

</script>
