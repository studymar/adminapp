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
            <h3>Thema editieren</h3>
            <br/>
            <p class="alert alert-danger" v-if="config.topErrorSaving">{{config.topErrorSavingMessage}}</p>
            <p class="alert alert-danger" v-if="config.topErrorLoading">{{config.topErrorLoadingMessage}}</p>

            <div class="row field-teaser-headline required">
                <label class="control-label col-sm-3" for="voting-headline">Thema</label>
                <div class="col-sm-9">
                    <input v-validate="'required'" id="voting-headline" type="text" id="voting-headline" class="form-control" name="Votingtopic[headline]" v-model="item.headline" aria-required="true">
                    <p class="help-block help-block-error " v-if="errors.has('headline')">{{errors.first('headline')}}</p>
                    <p class="help-block help-block-error " ><?= (!empty($model->getErrors()))?$model->getErrors('headline')[0]:'' ?></p>
                </div>
            </div> 
            <div class="row field-teaser-headline required">
                <label class="control-label col-sm-3" for="voting-description">Beschreibung</label>
                <div class="col-sm-9">
                    <input type="text" id="voting-description" class="form-control" name="Votingtopic[description]" v-model="item.description" aria-required="true">
                    <p class="help-block help-block-error " v-if="errors.has('description')">{{errors.first('description')}}</p>
                    <p class="help-block help-block-error " ><?= (!empty($model->getErrors()))?$model->getErrors('description')[0]:'' ?></p>
                </div>
            </div> 
            <div class="row field-teaser-headline required">
                <label class="control-label col-sm-3" for="voting-active">Aktiv/Sichtbar?</label>
                <div class="col-sm-9">
                    <input type="checkbox" id="voting-active" class="" name="Votingtopic[active]" v-model="item.active" v-bind:true-value="1" v-bind:false-value="0" aria-required="true">
                    <p class="help-block help-block-error " v-if="errors.has('active')">{{errors.first('active')}}</p>
                    <p class="help-block help-block-error " ><?= (!empty($model->getErrors()))?$model->getErrors('active')[0]:'' ?></p>
                </div>
            </div> 
            
            <button type="submit" id="submit-button" class="btn btn-primary" >Speichern</button>
            <?= Html::a('Zurück',['voting/edit'],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>
            
        <?php ActiveForm::end() ?>
            
        <br/><br/>

        <!-- Gewichtung eingeben -->
        <!-- Freitexteingabe ausgewählt -->
        <div class="form-group voting-weight-table">
            <label for="voteanswer">Bei Bedarf verschiedene Gewichtungen festlegen (User wählen vor Voting diese aus)?</label>
            <div class="mb-3 line">
                <div class="col-8">Auswählbarer Name (z.b.Verein)</div>
                <div class="col-1">Stimmen</div>
            </div>
            <div class="mb-3 line" v-for="item in votingweights" :key="item.id">
                <input type="text" class="form-control col-8" id="voteanswer" name="Votingweight[name]" v-model="item.name" >
                <input type="text" class="form-control col-1" id="voteanswer" name="Votingweight[stimmen]" v-model="item.stimmen" >
                <a class="btn btn-danger" v-on:click="removeVotingweight(item)"><span class="material-icons">delete</span></a>
            </div>
            <div class="mb-3 line">
                <a href="" class="col-8 new" v-on:click.prevent="addVotingweight">Neuer Eintrag</a>
            </div>
        </div>
        <!-- -->

        <div v-if="Object.keys(this.votingweights).length" ><!-- v-if="!(votingweights instanceof Array)" -->
            <div class="d-flex w-100 justify-content-between header">
                <div>
                    <button type="submit" class="btn btn-primary" v-on:click="saveVotingweightsList">Gewichtung speichern</button>
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
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                getVotingWeightsList: '/voting/get-or-save-votingweights-list-of-topic/<?= $model->id ?>',
                newId: 0
            },
            item: {
                id: <?= $model->id ?>,
                headline: '<?= $model->headline ?>',
                description: '<?= $model->description ?>',
                active: <?= $model->active ?>,
                created: '<?= $model->created ?>',
                created_by: '<?= $model->createdBy->getName() ?>'
            },
            votingweights: []
            
        },
        mounted: function() {
            //gewichtung abrufen
            this.getVotingweightsList()
        },
        // define methods under the `methods` object
        methods: {
            saveTopic() {
                //nur validieren, form wird auch so abgeschickt
                self = this;
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        //console.log('submit')
                        
                        return true;
                    }
                });
            },
            getVotingweightsList() {
                //liste der VoetedWeights abrufen
                self = this;
                $.post(self.config.getVotingWeightsList,
                {
                })
                .done(function(data) {
                    //console.log(data)
                    self.votingweights = data.votingweights

                    self.config.topErrorLoading = false
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error loading weights" )
                });

            },
            saveVotingweightsList() {
                self = this;

                //liste der VoetedWeights abschicken
                $.post(self.config.getVotingWeightsList,
                {
                    "Votingweights": self.votingweights
                })
                .done(function(data) {
                    //console.log(data)
                    self.votingweights = data.votingweights
                    //self.config.newId = Object.keys(this.votingweights).length+1
                    self.config.newId = self.votingweights.length+1

                    self.config.topErrorLoading = false
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error saving item" )
                });
            },
            addVotingweight() {
                self = this
                this.$set(self.votingweights, self.votingweights.length, {
                    id: self.votingweights.length,
                    name: 'Neuer Eintrag',
                    stimmen: 1
                })
            },
            removeVotingweight(item) {
                //console.log('delete' + item.id)
                this.votingweights = this.votingweights.filter((e)=>e.id !== item.id )
            }
        }
   
    });
        
    </script>
    
