<?php

/* 
 * Formular zum Editieren der Inhalte einer ImageList
 * @param Object $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;
use app\models\helpers\DateConverter;
use app\models\user\User;


use app\assets\FormAsset;
FormAsset::register($this);
use app\assets\VueAsset;
VueAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir = "vue-components/content/";

//include_once(__DIR__.'/../../web/vue-components/content/LinkComponent.php');
include_once($env_vuecomponent_dir.'ImageComponent.php');
include_once($env_vuecomponent_dir.'ReleaseComponent.php');
include_once($env_vuecomponent_dir.'ImagemanagerComponent.php');
?>

    <div id="editItem" class="">
        <form v-on:submit.prevent="onSubmit" method="POST" v-if="view == 'form'">
            <h3>Image - Edit</h3>
            <div v-if="form.isChanged" class="alert alert-warning" role="alert">
                <strong>Achtung!</strong>
                <br>
                Diese Seite enthält noch ungespeicherte Elemente.
            </div>            

            <p class="alert alert-success" v-if="form.topSuccess">{{form.topSuccessMessage}}</p>
            <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
            <p class="alert alert-danger" v-if="form.topErrorLoading">{{form.topErrorLoadingMessage}}</p>

            <imagecomponent 
                v-if="item != null" 
                v-bind:image-list="item"
                v-on:changed="form.isChanged = true">
            </imagecomponent>
            
            <hr/>
            <releasecomponent 
                v-bind:release-item="item.pageHasTemplate && item.pageHasTemplate.release"
                v-on:changed="form.isChanged = true">
            </releasecomponent>
            
            <button type="submit" id="submit-button" class="btn" v-bind:class="{ 'btn-danger': form.isChanged, 'btn-primary': !form.isChanged}">Speichern</button>
            <?= Html::a('Zurück',['page/edit','p'=>$model->pageHasTemplate->page->urlname],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>

            <p class="card-text created-info"><small class="text-muted">
                    Erstellt <?= DateConverter::convert($model->created)."/".$model->createdBy->getName() ?>
                    <?php if(\app\models\helpers\DateCalculator::isUpdatedAnotherDay($model->created, $model->updated)){ ?>
                    <br/>Update <?= DateConverter::convert($model->updated)."/".$model->updatedBy->getName() ?>
                    <?php } ?>
                </small>
            </p>

            <br/><br/>
        </form>
        
        <div v-if="view == 'imagemanager'">
            <imagemanagercomponent
                v-bind:image-list="item"
                max-selecting=1>
            </imagemanagercomponent>
        </div>
            
    </div>

    <script type="text/javascript">
    Vue.component('flat-pickr', VueFlatpickr);
    Vue.component('VeeValidate', VeeValidate);
    Vue.use(VeeValidate);
    
    new Vue({
        el: '#editItem',
        components: {
            imagecomponent,
            releasecomponent,
            imagemanagercomponent
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
                getLink: '/imagelist/get-item/',
                saveLink: '/imagelist/save-item/',
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
                //item laden
                self = this;
                //$('#editItem').hide();
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
                //console.log('submit')
                self = this;

                //change uploadedimages to array of ids
                var imageListIds = []
                if(this.item.uploadedimages){
                    this.item.uploadedimages.forEach( function(image){
                        imageListIds.push(image.id)
                    })
                }

                $.post(self.form.saveLink + this.item.id,
                {
                    "Release[is_released]": this.item.pageHasTemplate.release.is_released,
                    "Release[from_date]": this.item.pageHasTemplate.release.from_date,
                    "Release[to_date]": this.item.pageHasTemplate.release.to_date,
                    "ImageList[uploadedimages_ids]": imageListIds
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
            },
            /* LinkItem-Funktionen */
            selectLinkFromPagemanager: function (event) {
                this.view = 'pagemanager'
            },
            closePagemanager(){
                this.view = 'form'
            },
            selectImageFromImagemanager() {
                this.view = 'imagemanager'
            },
            closeImagemanager(){
                this.view = 'form'
            },
            selectDocFromDocumentmanager() {
                this.view = 'documentmanager'
            },
            closeDocumentmanager(){
                this.view = 'form'
            }
        }
    });
        
    </script>
    
