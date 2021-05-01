<?php

/* 
 * Formular zum Editieren der Inhalte eines Teasers
 * @param Object $model
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

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir = "vue-components/content/";

//include_once(__DIR__.'/../../web/vue-components/content/LinkComponent.php');
include_once($env_vuecomponent_dir.'LinkComponent.php');
include_once($env_vuecomponent_dir.'ImageComponent.php');
include_once($env_vuecomponent_dir.'DocumentComponent.php');
include_once($env_vuecomponent_dir.'ReleaseComponent.php');
include_once($env_vuecomponent_dir.'PagemanagerComponent.php');
include_once($env_vuecomponent_dir.'ImagemanagerComponent.php');
include_once($env_vuecomponent_dir.'DocumentmanagerComponent.php');
?>

    <div id="editItem">
        <form v-on:submit.prevent="onSubmit" method="POST" v-if="view == 'form'">
            <h3>Teaser - Edit</h3>
            <div v-if="form.isChanged" class="alert alert-warning" role="alert">
                <strong>Achtung!</strong>
                <br>
                Diese Seite enthält noch ungespeicherte Elemente.
            </div>            

            <p class="alert alert-success" v-if="form.topSuccess">{{form.topSuccessMessage}}</p>
            <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
            <p class="alert alert-danger" v-if="form.topErrorLoading">{{form.topErrorLoadingMessage}}</p>

            <div class="row field-teaser-headline required">
                <label class="control-label col-sm-3" for="teaser-headline">Headline</label>
                <div class="col-sm-9">
                    <input v-validate="'required'" type="text" id="teaser-headline" class="form-control" name="Headline" v-model="item.headline" aria-required="true">
                    <p class="help-block help-block-error " v-if="errors.has('Headline')">{{errors.first('Headline')}}</p>
                    <p class="help-block help-block-error " >{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.headline[0]:''}}</p>
                </div>
            </div> 
            <div class="row field-teaser-text required">
                <label class="control-label col-sm-3" for="teaser-text">Text</label>
                <div class="col-sm-9">
                    <textarea v-validate="{required:true, regex: /^([0-9A-Za-z,.\-;:_#+*\\ß?=)(\/&%$§! ]+)$/}" id="teaser-text" v-model="item.text" class="form-control" name="Text" aria-required="true">
                    </textarea>
                    <p class="help-block help-block-error " v-if="errors.has('Text')">{{errors.first('Text')}}</p>
                    <p class="help-block help-block-error " >{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.text[0]:''}}</p>
                </div>
            </div>

            <hr/>
            <linkcomponent 
                v-if="item.linkItem != null" 
                v-bind:link-item="item.linkItem"
                v-on:select-link-from-pagemanager="selectLinkFromPagemanager"
                v-on:changed="form.isChanged = true">
            </linkcomponent>

            <hr/>
            <imagecomponent 
                v-if="item.imageList != null" 
                v-bind:image-list="item.imageList"
                v-on:changed="form.isChanged = true">
            </imagecomponent>
            <hr/>
            
            <hr/>
            <documentcomponent 
                v-if="item.documentList != null" 
                v-bind:document-list="item.documentList"
                v-on:changed="form.isChanged = true">
            </documentcomponent>
            
            <hr/>
            <releasecomponent 
                v-bind:release-item="item.release"
                v-on:changed="form.isChanged = true">
            </releasecomponent>
            
            <button type="submit" id="submit-button" class="btn" v-bind:class="{ 'btn-danger': form.isChanged, 'btn-primary': !form.isChanged}">Speichern</button>
            <?= Html::a('Zurück',['page/edit','p'=>$model->teaserlist->pageHasTemplate->page->urlname],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>

            <br/><br/>
        </form>
        
        <div v-if="view == 'pagemanager'">
            <pagemanagercomponent
                v-bind:link-item="item.linkItem"
                v-on:show-form="view='form'">
            </pagemanagercomponent>
        </div>
        <div v-if="view == 'imagemanager'">
            <imagemanagercomponent
                v-bind:image-list="item.imageList"
                max-selecting=1>
            </imagemanagercomponent>
        </div>
        <div v-if="view == 'documentmanager'">
            <documentmanagercomponent
                v-bind:document-list="item.documentList">
            </documentmanagercomponent>
        </div>
            
    </div>

    <script type="text/javascript">
    Vue.component('flat-pickr', VueFlatpickr);
    Vue.component('VeeValidate', VeeValidate);
    Vue.use(VeeValidate);
    
    new Vue({
        el: '#editItem',
        components: {
            linkcomponent,
            imagecomponent,
            documentcomponent,
            releasecomponent,
            pagemanagercomponent,
            imagemanagercomponent,
            documentmanagercomponent
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
                getLink: '/teaserlist/get-item/',
                saveLink: '/teaserlist/save-item/',
                saveSuccessLink: '/page/edit/<?= $model->teaserlist->pageHasTemplate->page->urlname ?>'
            },
            item: {
                id: '<?= $model->id ?>',
                linkItem: null
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
                //console.log('load')
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

                    //change uploadedimages to array of ids
                    var imageListIds = []
                    if(this.item.imageList.uploadedimages){
                        this.item.imageList.uploadedimages.forEach( function(image){
                            imageListIds.push(image.id)
                        })
                    }
                    //change documents to array of ids
                    var documentListIds = []
                    if(this.item.documentList.documents){
                        this.item.documentList.documents.forEach( function(document){
                            documentListIds.push(document.id)
                        })
                    }


                    $.post(self.form.saveLink + this.item.id,
                    {
                        "Teaser[headline]": this.item.headline,
                        "Teaser[text]": this.item.text,
                        "Release[is_released]": this.item.release.is_released,
                        "Release[from_date]": this.item.release.from_date,
                        "Release[to_date]": this.item.release.to_date,
                        "LinkItem[extern_url]": this.item.linkItem.extern_url,
                        "LinkItem[target_page_id]": this.item.linkItem.target_page_id,
                        "ImageList[uploadedimages_ids]": imageListIds,
                        "DocumentList[document_ids]": documentListIds
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
                            self.form.showLinkExternInput = false
                            document.location.href = self.form.saveSuccessLink
                        }
                        //UnSaved markierung entfernen
                        self.form.isChanged = false;                        
                    })
                    .fail(function() {
                      console.log( "error saving item" )
                    });
                }});
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
    
