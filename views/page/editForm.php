<?php

/* 
 * Formular zum Editieren der Inhalte
 * @param Page $page
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;
use app\models\content\LinkItem;
use app\models\content\DocumentList;

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
include_once($env_vuecomponent_dir.'TextComponent.php');
include_once($env_vuecomponent_dir.'LinkComponent.php');
include_once($env_vuecomponent_dir.'ImageComponent.php');
include_once($env_vuecomponent_dir.'DocumentComponent.php');
include_once($env_vuecomponent_dir.'ReleaseComponent.php');
include_once($env_vuecomponent_dir.'PagemanagerComponent.php');
include_once($env_vuecomponent_dir.'ImagemanagerComponent.php');
include_once($env_vuecomponent_dir.'DocumentmanagerComponent.php');
?>

    <div id="editItem" class="text-template">
        <validation-observer v-slot="{ handleSubmit }">
        <form v-on:submit.prevent="handleSubmit(onSubmit)" method="POST" v-if="view == 'form'">
            <h3>Page - Edit</h3>
            <div v-if="form.isChanged" class="alert alert-warning" role="alert">
                <strong>Achtung!</strong>
                <br>
                Diese Seite enthält noch ungespeicherte Elemente.
            </div>

            <p class="alert alert-success" v-if="form.topSuccess">{{form.topSuccessMessage}}</p>
            <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
            <p class="alert alert-danger" v-if="form.topErrorLoading">{{form.topErrorLoadingMessage}}</p>


            <hr/>
            <div v-for="item in pageHasTemplates" :key="item.id">
                
                <imagecomponent 
                    v-if="item != null && item.template.objectname == 'imageList'" 
                    v-bind:image-list="item.imageLists[0]"
                    v-on:changed="form.isChanged = true">
                </imagecomponent>
                
                <documentcomponent 
                    v-if="item != null && item.template.objectname == 'documentlist'"
                    v-bind:document-list="item.documentlists[0]"
                    v-on:changed="form.isChanged = true">
                </documentcomponent>

                <textcomponent 
                    v-if="item != null && item.template.objectname == 'text'"
                    v-bind:text="item.texts[0]"
                    v-bind:errors="errors"
                    v-bind:errormessages="form.errorMessages"
                    v-on:changed="form.isChanged = true">
                </textcomponent>
                
            </div>

            <releasecomponent
                v-if="page != null"
                v-bind:release-item="release"
                v-on:changed="form.isChanged = true">
            </releasecomponent>
            
            <button type="submit" id="submit-button" class="btn" v-bind:class="{ 'btn-danger': form.isChanged, 'btn-primary': !form.isChanged}">Speichern</button>
            <?= Html::a('Zurück',['page/edit','p'=>$page->urlname],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>

            <br/><br/>
        </form>
        </validation-observer>
        
        <div v-if="view == 'documentmanager'">
            <documentmanagercomponent
                v-bind:document-list="editLists.documentlist">
            </documentmanagercomponent>
        </div>
        <div v-if="view == 'imagemanager'">
            <imagemanagercomponent
                v-bind:image-list="editLists.imageList"
                max-selecting=99>
            </imagemanagercomponent>
        </div>
            
    </div>

    <script type="text/javascript">
Vue.component('validation-observer', VeeValidate.ValidationObserver);
    Vue.component('flat-pickr', VueFlatpickr);
    Vue.use( CKEditor );
    
    new Vue({
        el: '#editItem',
        components: {
            textcomponent,
            linkcomponent,
            imagecomponent,
            documentcomponent,
            releasecomponent,
            pagemanagercomponent,
            imagemanagercomponent,
            documentmanagercomponent
        },
        data: {
            editLists: {
              imageList: null,
              documentlist: null  
            },
            view: 'form',
            page: <?= json_encode($page->attributes) ?>,
            release: <?= json_encode($page->release->attributes) ?>,
            pageHasTemplates: <?= $pageHasTemplates ?>,
            errors: [],
            form: {
                isChanged: false,
                topError: false,
                topErrorLoading: false,
                topSuccess: false,
                topSuccessMessage: 'Gespeichert',
                topErrorMessage: 'Bitte überprüfen Sie Ihre Eingaben',
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                errorMessages: {},
                getLink: '/text/get-item/',
                saveLink: '/page/save-edit-form/',
                saveSuccessLink: '/page/edit/<?= $page->urlname ?>'
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

                //change uploadedimages to array of ids
                this.pageHasTemplates.forEach(function(item){
                    if(item.hasOwnProperty('imageLists')){
                        item.imageLists.map(function(imageList){
                        var imageListIds = []
                            if(imageList.uploadedimages){
                                imageList.uploadedimages.forEach( function(image){
                                    imageListIds.push(image.id)
                                })
                            }
                            imageList.uploadedimages_ids = imageListIds
                        })
                    }
                    if(item.hasOwnProperty('documentlists')){
                        item.documentlists.map(function(documentlist){
                        var documentlistIds = []
                            if(documentlist.documents){
                                documentlist.documents.forEach( function(document){
                                    documentlistIds.push(document.id)
                                })
                            }
                            documentlist.document_ids = documentlistIds
                        })
                    }
                });

                $.post(self.form.saveLink + this.page.id,
                {
                    "Release[is_released]": this.release.is_released,
                    "Release[from_date]": this.release.from_date,
                    "Release[to_date]": this.release.to_date,
                    "PageHasTemplate": this.pageHasTemplates
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
            
            selectImageFromImagemanager(imageList) {
                this.editLists.imageList = imageList
                this.view = 'imagemanager'
            },
            closeImagemanager(){
                this.editLists.imageList = null
                this.view = 'form'
            },
            selectDocFromDocumentmanager(documentlist) {
                this.editLists.documentlist = documentlist
                this.view = 'documentmanager'
            },
            closeDocumentmanager(){
                this.editLists.documentlist = null
                this.view = 'form'
            }
        }
    });
        
    </script>
    
