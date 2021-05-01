<?php

/* 
 * Formular zum Editieren des Teasers
 * @param Teaser $item
 */
use yii\helpers\Url;
use yii\helpers\Html;

use app\assets\FormAsset;
FormAsset::register($this);
use app\assets\VueAsset;
VueAsset::register($this);
use app\assets\VueCKEditorAsset;
VueCKEditorAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir = "vue-components/content/";

//include_once(__DIR__.'/../../web/vue-components/content/LinkComponent.php');
include_once($env_vuecomponent_dir.'TextfieldComponent.php');
?>

    <div id="editItem" class="text-template">
        <validation-observer v-slot="{ handleSubmit }">
        <form v-on:submit.prevent="handleSubmit(onSubmit)" method="POST">
            <h3>Neuer Teaser</h3>

            <hr/>
            <div>
                
                <textfieldcomponent 
                    v-model="headline"
                    label="Überschrift"
                    v-bind:errors="errors"
                    v-bind:errormessages="form.errorMessages"
                    fieldname="TeaserCreateForm[headline]">
                </textfieldcomponent>
                
            </div>

            <button type="submit" id="submit-button" class="btn btn-primary">Erstellen</button>
            <a href="<?= Url::toRoute(['page/edit','p'=>$model->pageHasTemplate->page->urlname]) ?>" class="btn btn-outline-primary" id="cancel-button">Zurück</a>

            <br/><br/>
        </form>
        </validation-observer>
                        
    </div>

    <script type="text/javascript">
    Vue.component('validation-observer', VeeValidate.ValidationObserver);
    
    new Vue({
        el: '#editItem',
        components: {
            textfieldcomponent,
        },
        data: {
            errors: [],
            headline: "",
            form: {
                topError: false,
                topErrorMessage: 'Bitte überprüfen Sie Ihre Eingaben',
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                errorMessages: {},
                getLink: null,
                saveLink: '/teaserlist/create-save/<?= $model->id ?>',
                saveSuccessLink: '<?= Url::toRoute(['page/edit','p'=>$model->pageHasTemplate->page->urlname]) ?>'
            }
        },
        mounted: function() {
            //this.onLoad()
        },
        // define methods under the `methods` object
        methods: {
            onSubmit: function (event) {
                //console.log('submit')
                self = this;

                $.post(self.form.saveLink,
                {
                    "TeaserCreateForm[headline]": this.headline
                })
                .done(function(data) {
                    if(!data.saved){
                        //error
                        self.form.topError = true
                        self.form.errorMessages = data.errormessages
                    }
                    else {
                        //success
                        self.form.topError = false
                        self.form.errorMessages = null
                        document.location.href = self.form.saveSuccessLink
                    }
                })
                .fail(function() {
                  console.log( "error saving item" )
                });
            }
                        
        }
    });
        
    </script>
    
