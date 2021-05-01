<?php

/* 
 * Formular zum Editieren der Liste
 * @param Object $model
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
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir = "vue-components/content/";

include_once($env_vuecomponent_dir.'ReleaseComponent.php');
?>

    <div id="editItem">
        <form v-on:submit.prevent="onSubmit" method="POST" v-if="view == 'form'">
            <h3>Liste - Edit</h3>
            <div v-if="form.isChanged" class="alert alert-warning" role="alert">
                <strong>Achtung!</strong>
                <br>
                Diese Seite enthält noch ungespeicherte Elemente.
            </div>            

            <p class="alert alert-success" v-if="form.topSuccess">{{form.topSuccessMessage}}</p>
            <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
            <p class="alert alert-danger" v-if="form.topErrorLoading">{{form.topErrorLoadingMessage}}</p>

            <releasecomponent v-if="item.pageHasTemplate"
                v-bind:release-item="item.pageHasTemplate.release"
                v-on:changed="form.isChanged = true">
            </releasecomponent>
            
            <button type="submit" id="submit-button" class="btn" v-bind:class="{ 'btn-danger': form.isChanged, 'btn-primary': !form.isChanged}">Speichern</button>
            <?= Html::a('Zurück',['page/edit','p'=>$model->pageHasTemplate->page->urlname],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>

            <br/><br/>
        </form>
                    
    </div>

    <script type="text/javascript">
    Vue.component('flat-pickr', VueFlatpickr);
    Vue.component('VeeValidate', VeeValidate);
    Vue.use(VeeValidate);
    
    new Vue({
        el: '#editItem',
        components: {
            releasecomponent,
        },
        data: {
            view: 'form',
            form: {
                isChanged: false,
                topError: false,
                topErrorLoading: false,
                topSuccess: false,
                topSuccessMessage: 'Gespeichert',
                topErrorMessage: 'Bitte überprüfen Sie Ihre Eingaben',
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                errorMessages: {},
                getLink: '/teaserlist/get-list/',
                saveLink: '/teaserlist/save-list/',
                saveSuccessLink: '/page/edit/<?= $model->pageHasTemplate->page->urlname ?>'
            },
            item: {
                id: '<?= $model->id ?>'
            }
        },
        mounted: function() {
            this.onLoad()
        },
        // define methods under the `methods` object
        methods: {
            onLoad() {
                //vee-validate auf language de einstellen
                this.$validator.localize('de');
                console.log('load')
                //item laden
                self = this;
                $.post(self.form.getLink + this.item.id,
                {
                })
                .done(function(data) {
                    self.item = data
                    self.form.topErrorLoading = false
                })
                .fail(function() {
                    self.form.topErrorLoading = true
                    console.log( "error loading item" )
                });
            },
            onSubmit: function (event) {
                this.$validator.validateAll().then((result) => {
                if (result) {
                    //console.log('submit')
                    self = this;

                    $.post(self.form.saveLink + this.item.id,
                    {
                        "Release[is_released]": this.item.pageHasTemplate.release.is_released,
                        "Release[from_date]": this.item.pageHasTemplate.release.from_date,
                        "Release[to_date]": this.item.pageHasTemplate.release.to_date
                    })
                    .done(function(data) {
                        if(!data.saved){
                            //error
                            self.form.topSuccess = false
                            self.form.topError = true
                            self.form.errorMessages = data.errormessages
                        }
                        else {
                            //success
                            self.form.topSuccess = true
                            self.form.topError = false
                            self.form.errorMessages = null
                            document.location.href = self.form.saveSuccessLink
                        }
                        //UnSaved markierung entfernen
                        self.form.isChanged = false;                        
                    })
                    .fail(function() {
                      console.log( "error saving item" )
                    });
                }});
            }
        }
    });
        
    </script>
    
