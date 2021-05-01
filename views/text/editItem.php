<?php

/* 
 * Formular zum Editieren der Inhalte
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
use app\assets\VueCKEditorAsset;
VueCKEditorAsset::register($this);

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

    <div id="editItem" class="text-template">
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

            <div class="row field-teaser-headline">
                <label class="control-label col-sm-3" for="text-headline">Textüberschrift (optional)</label>
                <div class="col-sm-9">
                    <validation-provider rules="ShortText" v-slot="{ errors }">
                        <input type="text" id="text-headline" class="form-control" name="Headline" v-model="item.headline" aria-required="false">
                        <span class="invalid">{{ errors[0] }}</span>
                    </validation-provider>
                </div>
            </div> 
            <div class="row field-text required">
                <label class="control-label col-sm-3" for="text-text">Text</label>
                <div class="col-sm-9">
                    <!--validation-provider :rules="{ regex: /^[a-zA-ZäÄüÜöÖ0-9!§$%&/()=?ß\[\]*+_\-:\.;,@ ]+$/ }" v-slot="{ errors }"-->
                    <validation-provider rules="required|LongText" v-slot="{ errors }">
                        <ckeditor :editor="editor" v-model="item.text" :config="editorConfig" name="Text"></ckeditor>
                        <span class="invalid">{{ errors[0] }}</span>
                        <p class="help-block help-block-error " >{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.text[0]:''}}</p>
                    </validation-provider>
                </div>
            </div>

            <hr/>
            <documentcomponent 
                v-if="item.documentList != null" 
                v-bind:document-list="item.documentList"
                v-on:changed="form.isChanged = true">
            </documentcomponent>
            
            <hr/>
            <releasecomponent
                v-if="item.pageHasTemplate != undefined"
                v-bind:release-item="item.pageHasTemplate.release"
                v-on:changed="form.isChanged = true">
            </releasecomponent>
            
            <button type="submit" id="submit-button" class="btn" v-bind:class="{ 'btn-danger': form.isChanged, 'btn-primary': !form.isChanged}">Speichern</button>
            <?= Html::a('Zurück',['page/edit','p'=>$model->pageHasTemplate->page->urlname],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>

            <br/><br/>
        </form>
        
        <div v-if="view == 'documentmanager'">
            <documentmanagercomponent
                v-bind:document-list="item.documentList">
            </documentmanagercomponent>
        </div>
            
    </div>

    <script type="text/javascript">
    Vue.component('flat-pickr', VueFlatpickr);
    Vue.use( CKEditor );
    
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
            editor: ClassicEditor,
            editorConfig: {
                toolbar: [ 'bold', 'italic', 'strikethrough', 'subscript','link','outdent', 'indent','bulletedList', 'numberedList', 'undo', 'redo']                
            },
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
                saveLink: '/text/save-item/',
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

                //change documents to array of ids
                var documentListIds = []
                if(this.item.documentList.documents){
                    this.item.documentList.documents.forEach( function(document){
                        documentListIds.push(document.id)
                    })
                }


                $.post(self.form.saveLink + this.item.id,
                {
                    "Text[headline]": this.item.headline,
                    "Text[text]": this.item.text,
                    "Release[is_released]": this.item.pageHasTemplate.release.is_released,
                    "Release[from_date]": this.item.pageHasTemplate.release.from_date,
                    "Release[to_date]": this.item.pageHasTemplate.release.to_date,
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
                        document.location.href = self.form.saveSuccessLink
                    }
                    //UnSaved markierung entfernen
                    self.form.isChanged = false;                        
                })
                .fail(function() {
                  console.log( "error saving item" )
                });
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
    
