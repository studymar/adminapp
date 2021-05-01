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
include_once($env_vuecomponent_dir.'TextareaComponent.php');
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
            <h3>Teaser - Edit</h3>
            <div v-if="form.isChanged" class="alert alert-warning" role="alert">
                <strong>Achtung!</strong>
                <br>
                Diese Seite enthält noch ungespeicherte Elemente.
            </div>

            <p class="alert alert-success" v-if="form.topSuccess">{{form.topSuccessMessage}}</p>
            <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
            <p class="alert alert-danger" v-if="form.topErrorLoading">{{form.topErrorLoadingMessage}}</p>

            <hr/>
            <div>
                
                <imagecomponent 
                    v-bind:image-list="imageList"
                    v-on:changed="form.isChanged = true">
                </imagecomponent>
                
                <textfieldcomponent 
                    v-model="item.headline"
                    label="Überschrift"
                    v-bind:errors="errors"
                    v-bind:errormessages="form.errorMessages"
                    v-on:changed="form.isChanged = true"
                    fieldname="Teaser[headline]">
                </textfieldcomponent>

                <textareacomponent 
                    v-model="item.text"
                    label="Text"
                    v-bind:errors="errors"
                    v-bind:errormessages="form.errorMessages"
                    v-on:changed="form.isChanged = true"
                    fieldname="Teaser[headline]">
                </textareacomponent>
                
                <documentcomponent 
                    v-bind:document-list="documentList"
                    v-on:changed="form.isChanged = true">
                </documentcomponent>

                <linkcomponent 
                    v-bind:link-item="linkItem"
                    v-on:select-link-from-pagemanager="selectLinkFromPagemanager"
                    v-on:changed="form.isChanged = true">
                </linkcomponent>
                
            </div>

            <releasecomponent
                v-bind:release-item="release"
                v-on:changed="form.isChanged = true">
            </releasecomponent>
            
            <button type="submit" id="submit-button" class="btn" v-bind:class="{ 'btn-danger': form.isChanged, 'btn-primary': !form.isChanged}">Speichern</button>
            <a href="<?= Url::toRoute(['page/edit','p'=>$item->teaserlist->pageHasTemplate->page->urlname]) ?>" class="btn btn-outline-primary" id="cancel-button">Zurück</a>

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
            
        <div v-if="view == 'pagemanager'">
            <pagemanagercomponent
                v-bind:link-item="linkItem"
                v-on:changed="form.isChanged = true">
            </pagemanagercomponent>
        </div>
                
    </div>

    <script type="text/javascript">
    Vue.component('validation-observer', VeeValidate.ValidationObserver);
    Vue.component('flat-pickr', VueFlatpickr);
    Vue.use( CKEditor );
    
    new Vue({
        el: '#editItem',
        components: {
            textareacomponent,
            textfieldcomponent,
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
                imageList: <?= json_encode($imageList) ?>,
                documentList: <?= json_encode($documentList) ?>
            },
            view: 'form',
            item: <?= json_encode($item->attributes) ?>,
            imageList: <?= json_encode($imageList) ?>,
            documentList: <?= json_encode($documentList) ?>,
            linkItem: <?= json_encode($linkItem) ?>,
            release: <?= json_encode($release->attributes) ?>,
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
                getLink: null,
                saveLink: '/teaserlist/edit-save/',
                saveSuccessLink: '<?= Url::toRoute(['page/edit','p'=>$item->teaserlist->pageHasTemplate->page->urlname]) ?>'
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
                var imageListIds = []
                if(this.imageList.uploadedimages){
                    this.imageList.uploadedimages.forEach( function(image){
                        imageListIds.push(image.id)
                    })
                }
                this.imageList.uploadedimages_ids = imageListIds

                var documentlistIds = []
                if(this.documentList.documents){
                    this.documentList.documents.forEach( function(document){
                        documentlistIds.push(document.id)
                    })
                }
                this.documentList.document_ids = documentlistIds


                $.post(self.form.saveLink + this.item.id,
                {
                    "Release[is_released]": this.release.is_released,
                    "Release[from_date]": this.release.from_date,
                    "Release[to_date]": this.release.to_date,
                    "ImageList[uploadedimages_ids]": this.imageList.uploadedimages_ids,
                    "DocumentList[document_ids]": this.documentList.document_ids,
                    "LinkItem[target_page_id]": this.linkItem.target_page_id,
                    "LinkItem[extern_url]": this.linkItem.extern_url,
                    "Teaser[headline]": this.item.headline,
                    "Teaser[text]": this.item.text
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
                //zurücksetzen
                this.editLists.imageList = null
                this.view = 'form'
            },
            selectDocFromDocumentmanager(documentlist) {
                this.editLists.documentlist = documentlist
                this.view = 'documentmanager'
            },
            closeDocumentmanager(){
                //zurücksetzen
                this.editLists.documentlist = null
                this.view = 'form'
            },
            /* LinkItem-Funktionen */
            selectLinkFromPagemanager: function (event) {
                this.view = 'pagemanager'
            },
            closePagemanager(){
                this.view = 'form'
            }
            
        }
    });
        
    </script>
    
