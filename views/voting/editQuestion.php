<?php

/* 
 * Startseite der Votings für EDIT
 * @param Votingtopic $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;

use app\assets\FormAsset;
FormAsset::register($this);
use app\assets\VueAsset;
VueAsset::register($this);


if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/editline/';
else $env_vuecomponent_dir = "vue-components/editline/";

//include_once($env_vuecomponent_dir.'EditlineComponent.php');

?>

    <div id="editItem">
        <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
            <h3>Umfrage editieren</h3>
            <br/>
            <p class="alert alert-danger" v-if="config.topErrorSaving">{{config.topErrorSavingMessage}}</p>

            <div class="row field-teaser-headline required">
                <label class="control-label col-sm-3" for="voting-question">Frage</label>
                <div class="col-sm-9">
                    <input v-validate="'required'" id="voting-question" type="text" id="voting-question" class="form-control" name="Votingquestion[question]" v-model="item.question" aria-required="true">
                    <p class="help-block help-block-error " v-if="errors.has('question')">{{errors.first('question')}}</p>
                    <p class="help-block help-block-error " ><?= (!empty($model->getErrors()))?$model->getErrors('question')[0]:'' ?></p>
                </div>
            </div> 
            <div class="row field-teaser-headline required">
                <label class="control-label col-sm-3" for="voting-active">Aktiv/Sichtbar?</label>
                <div class="col-sm-9">
                    <input type="checkbox" id="voting-active" class="" name="Votingquestion[active]" v-model="item.active" v-bind:true-value="1" v-bind:false-value="0" aria-required="true">
                    <p class="help-block help-block-error " v-if="errors.has('active')">{{errors.first('active')}}</p>
                    <p class="help-block help-block-error " ><?= (!empty($model->getErrors()))?$model->getErrors('active')[0]:'' ?></p>
                </div>
            </div> 
            <div class="row field-teaser-headline required">
                <label class="control-label col-sm-3" for="voting-showresults">Results sichtbar?</label>
                <div class="col-sm-9">
                    <input type="checkbox" id="voting-showresults" class="" name="Votingquestion[showresults]" v-model="item.showresults" v-bind:true-value="1" v-bind:false-value="0" aria-required="true">
                    <p class="help-block help-block-error " v-if="errors.has('showresults')">{{errors.first('showresults')}}</p>
                    <p class="help-block help-block-error " ><?= (!empty($model->getErrors()))?$model->getErrors('showresults')[0]:'' ?></p>
                </div>
            </div> 
            <div class="row field-teaser-headline required">
                <label class="control-label col-sm-3" for="voting-votingtype_id">Umfragetyp</label>
                <div class="col-sm-9">
                    <div class="flexbox input" v-for="(option,index) in allVotingtypes" :key="index" >
                        <input type="radio" id="voting-votingtype_id" class="" name="Votingquestion[votingtype_id]" v-model="item.votingtype_id" v-bind:value="option.id" aria-required="true">
                        <label class="text">{{option.description}}</label>
                    </div>
                    <p class="help-block help-block-error " v-if="errors.has('votingtype_id')">{{errors.first('votingtype_id')}}</p>
                    <p class="help-block help-block-error " ><?= (!empty($model->getErrors()))?$model->getErrors('votingtype_id')[0]:'' ?></p>
                </div>
            </div> 
            
            <button type="submit" id="submit-button" class="btn btn-primary" >Speichern</button>
            <?= Html::a('Zurück',['voting/edit-topic-votings','p'=>$model->votingtopic_id],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>
            
        <?php ActiveForm::end() ?>
            
        <br/><br/>

        <!-- Options eingeben -->
        <!-- Freitexteingabe ausgewählt -->
        <div class="form-group voting-weight-table" v-if="item.votingtype_id != 3">
            <label for="voteanswer">Antwortmöglichkeiten?</label>
            <p class="alert alert-danger" v-if="config.topErrorLoading">{{config.topErrorLoadingMessage}}</p>
            <p class="alert alert-danger" v-if="config.topErrorSavingOptions">{{config.topErrorSavingOptionsMessage}}</p>

            <div class="mb-3 line" v-for="item in item.votingoptions" :key="item.id">
                <input type="text" class="form-control col-8" id="voteanswer" name="Votingoption[value]" v-model="item.value" >
                <a class="btn btn-danger" v-on:click="removeOption(item)"><span class="material-icons">delete</span></a>
            </div>
            <div class="mb-3 line">
                <a href="" class="col-8 new" v-on:click.prevent="addOption">Neuer Eintrag</a>
            </div>
        </div>
        <!-- -->

        <div v-if="Object.keys(this.item.votingoptions).length" ><!-- v-if="!(votingweights instanceof Array)" -->
            <div class="d-flex w-100 justify-content-between header">
                <div>
                    <button type="submit" class="btn btn-primary" v-on:click="saveOptions">Antworten speichern</button>
                </div>
            </div>
        </div>
            
            
    </div>


    <script type="text/javascript">
    Vue.component('VeeValidate', VeeValidate);
    Vue.use(VeeValidate);

    new Vue({
        el: '#editItem',
        components: {
        },
        data: {
            config: {
                topErrorSaving: false,
                topErrorSavingMessage: "Daten konnten nicht gespeichert werden",
                topErrorSavingOptions: false,
                topErrorSavingOptionsMessage: "Antworten konnten nicht gespeichert werden",
                topErrorLoading: false,
                topErrorLoadingMessage: 'Antworten konnten nicht geladen werden.',
                getOptions: '/voting/get-or-save-votingoptions/<?= $model->id ?>',
                saveOptions: '/voting/get-or-save-votingoptions/<?= $model->id ?>',
                newId: 0
            },
            item: {
                id: <?= $model->id ?>,
                question: '<?= $model->question ?>',
                active: <?= $model->active ?>,
                showresults: <?= $model->showresults ?>,
                hasweighting: <?= $model->hasweighting ?>,
                votingtype_id: <?= $model->votingtype_id ?>,
                votingtype: {
                    name: '<?= $model->votingtype->name ?>'
                },
                votingoptions: [
                ]
            },
            allVotingtypes: <?= json_encode($allVotingtypes) ?>
            
        },
        mounted: function() {
            //options abrufen
            this.getOptions()
        },
        // define methods under the `methods` object
        methods: {
            saveQuestion() {
                //nur validieren, form wird abgeschickt
                self = this;
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        //console.log('submit')
                        
                        return true;
                    }
                });
            },
            addOption() {
                self = this
                this.$set(self.item.votingoptions, self.item.votingoptions.length, {
                    id: self.config.newId++,
                    value: 'Neuer Eintrag'
                })
            },
            removeOption(item) {
                //console.log('delete' + item.id)
                this.item.votingoptions = this.item.votingoptions.filter((e)=>e.id !== item.id )
            },
            getOptions() {
                //liste der VoetedWeights abrufen
                self = this;
                $.post(self.config.getOptions,
                {
                })
                .done(function(data) {
                    //console.log(data)
                    self.item.votingoptions = data.votingoptions
                    self.config.newId = -100

                    self.config.topErrorLoading = false
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error loading options" )
                });

            },            
            saveOptions() {
                self = this;

                //liste der VoetedWeights abschicken
                $.post(self.config.saveOptions,
                {
                    "Votingoptions": self.item.votingoptions
                })
                .done(function(data) {
                    //console.log(data)
                    self.config.newId = self.item.votingoptions.length+1

                    self.config.topErrorSavingOptions = false
                })
                .fail(function() {
                    self.config.topErrorSavingOptions = true
                    console.log( "error saving options" )
                });
            }
            
        }
   
    });
        
    </script>
    
