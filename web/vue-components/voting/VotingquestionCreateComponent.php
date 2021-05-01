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
    $env_vuecomponent_dir_vqc = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir_vqc = "vue-components/content/";

include_once($env_vuecomponent_dir_vqc.'TextfieldComponent.php');

?>

<template id="voting-question-create-component">

        <div class="p-2">
            <div class="d-flex justify-content-between title-block">
                <h3 class="fs-5">Abstimmung erstellen</h3>
            </div>

            <p class="alert alert-danger" v-if="config.saveError">{{config.saveErrorMessage}}</p>

            <validation-observer v-slot="{ handleSubmit }">
            <form v-on:submit.prevent="handleSubmit(onSubmit)" method="POST">
                <textfieldcomponent
                    v-model="item.question"
                    label="Fragestellung"
                    fieldname="Votingquestion[question]"
                    v-bind:errors="errors"
                    v-bind:servererror="getErrormessage('question')"
                    inline="false"
                    >
                </textfieldcomponent>
                
                <div id="" class="">
                    <div class="fs-6 title-block mb-1">Typ der Umfrage</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <validation-provider rules="required|integer" v-slot="{ errors }" name="Typ">
                                <div class="form-check" v-for="type in votingtypes" :key="type.id">
                                    <input class="form-check-input" type="radio" name="votingtype_id" id="votingtype_id" v-model="item.votingtype_id" v-bind:value="type.id">
                                    <label class="form-check-label" for="votingtype_id">
                                      {{type.description}}
                                    </label>
                                </div>
                                <span class="invalid">{{ errors[0] }}</span>
                                <p class="help-block help-block-error" v-if="servererrors.hasOwnProperty('votingtype_id')">{{getErrormessage('votingtype_id')}}</p>
                            </validation-provider>
                        </div>
                    </div>
                    
                    <div class="fs-6 title-block mb-1">Einstellungen</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <validation-provider rules="required" v-slot="{ errors }" name="Ergebnis">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="showresults" v-model="item.showresults">
                                    <label class="form-check-label" for="showresults">Ergebnis nach Abstimmung für alle sichtbar</label>
                                </div>
                                <span class="invalid">{{ errors[0] }}</span>
                                <p class="help-block help-block-error" v-if="servererrors.hasOwnProperty('showresults')">{{getErrormessage('showresults')}}</p>
                            </validation-provider>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <validation-provider rules="required" v-slot="{ errors }" name="Gewichtung">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="hasweighting" v-model="item.hasweighting">
                                    <label class="form-check-label" for="hasweighting">Abstimmung mit Gewichtung</label>
                                </div>
                                <span class="invalid">{{ errors[0] }}</span>
                                <p class="help-block help-block-error" v-if="servererrors.hasOwnProperty('hasweighting')">{{getErrormessage('hasweighting')}}</p>
                            </validation-provider>
                        </div>
                    </div>

                    <div class="fs-6 title-block mb-1">Abstimmung jetzt aktiv?</div>
                    <div class="row">
                        <div class="col-sm-12">
                            <validation-provider rules="required" v-slot="{ errors }" name="Aktivierung">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="active" v-model="item.active">
                                    <label class="form-check-label" for="active">User können jetzt abstimmen</label>
                                </div>
                                <span class="invalid">{{ errors[0] }}</span>
                                <p class="help-block help-block-error" v-if="servererrors.hasOwnProperty('active')">{{getErrormessage('active')}}</p>
                            </validation-provider>
                        </div>
                    </div>
                    
                    <div id="votingoptions">
                        <div class="fs-6 title-block mb-1">Auswahloptionen</div>
                        <span v-if="votingoptions.length == 0">
                            <div class="row font-italic">
                                Noch keine Auswahloptionen eingestellt
                            </div>
                            <div class="row">
                              <div class="col-12 p-1 text-right">
                                  <a href="" v-on:click.prevent="addVotingoption()" class="btn btn-outline-primary"><div class="material-icons">add</div></a>
                              </div>
                            </div>
                        </span>
                        <span v-if="votingoptions.length > 0">
                            <validation-provider rules="required" v-slot="{ errors }" name="Bezeichnung">
                            <div class="row">
                              <div class="col-1 p-2">
                                  Nr
                              </div>
                              <div class="col-9 p-2">
                                  Bezeichnung
                              </div>
                              <div class="col-2 p-2">
                              </div>
                            </div>
                            <div class="row" v-for="(item,index) in votingoptions" :key="item.id">
                              <div class="col-1 p-2">
                                  {{index + 1}}
                              </div>
                              <div class="col-9 p-1">
                                <input type="text" class="form-control" placeholder="Bezeichnung" aria-label="Name" name="Votingoptions[value]" v-model="item.value">
                              </div>
                              <div class="col-2 p-1">
                                <a href="" class="btn btn-outline-light" v-on:click.prevent="deleteVotingoption(index)"><div class="material-icons">delete</div></a>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12 p-1 text-right">
                                  <a href="" v-on:click.prevent="addVotingoption()" class="btn btn-outline-primary"><div class="material-icons">add</div></a>
                              </div>
                            </div>
                            </validation-provider>
                        </span>
                    </div>

                    
                </div>

                <br/>
                <button type="submit" id="submit-button" class="btn btn-primary">Speichern</button>
                <a href="" v-on:click.prevent="$parent.closeTopicQuestionCreate()" class="btn btn-outline-primary" id="cancel-button">Zurück</a>
                

            </form>
            </validation-observer>

        </div>
    
</template>

<script type="text/javascript">
Vue.component('validation-observer', VeeValidate.ValidationObserver);

var votingQuestionCreateComponent = {
    template: '#voting-question-create-component',
    props: ['topic'],
    components: {
        textfieldcomponent
    },
    data: function(){
        return {
            config: {
                saveLink: '<?= Url::toRoute(['voting/create-question']).'/' ?>',
                saveError: false,
                saveErrormessage: 'Fehler: Abstimmung konnte nicht erstellt werden'
            },
            item: {
                question: "",
                votingtype_id: null,
                showresults: false,
                active: false,
                hasweighting: false
            },
            votingoptions: [],
            votingtypes: [
                {
                    id: "1",
                    description: "Mehrere auswählen"
                },
                {
                    id: "2",
                    description: "Eins auswählen"
                },
                {
                    id: "3",
                    description: "Texteingabe"
                }
            ],
            errors: [],
            servererrors: []
        }
    },
    computed: {
    },
    mounted: function() {
        //vorbelegung fuer gewichtung = true, wenn eine gewichtung eingetragen
        if(this.topic.votingweights.length > 0)
            this.item.hasweighting = true
        //sonst false
        else 
            this.item.hasweighting = false
        
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
                "Votingquestion[question]": self.item.question,
                "Votingquestion[votingtype_id]": self.item.votingtype_id,
                "Votingquestion[showresults]": self.item.showresults,
                "Votingquestion[active]": self.item.active,
                "Votingquestion[hasweighting]": self.item.hasweighting,
                "Votingoptions": self.votingoptions
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
                    self.$parent.closeTopicQuestionCreate()
                }
            })
            .fail(function() {
                
            });
        },
        addVotingoption(){
            var newid = 1
            if(this.votingoptions.length > 0){
                last = this.votingoptions[this.votingoptions.length - 1]
                newid = last.id + 1
            }
            this.votingoptions.push({
                'id': newid,
                'value': ''
            })
        },
        deleteVotingoption(index){
            this.votingoptions.splice(index, 1)
        }
    }
}

</script>
