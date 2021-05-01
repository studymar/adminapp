<?php
/**
 * Shows a list for selecting one or more documents
 * Preconditions:
 * - Parent must have a function for closing the manager
 *      closeDocumentmanager()
 * - documentList given as props
 */
use app\assets\Vue2DropzoneAsset;
Vue2DropzoneAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/';
else $env_vuecomponent_dir = "vue-components/content/";

include_once($env_vuecomponent_dir.'PaginationComponent.php');
include_once($env_vuecomponent_dir.'DocumenteditorComponent.php');

?>
<template id="documentmanagercomponent">
<span>
    <span v-if="view == 'documentmanager'">
        <!-- Imagemanager -->
        <h3>Ausgewählte Dateien</h3>
        <div class="list document-manager" id="list-selected">
            <div class="noImage-text" v-if="itemsSelected.length == 0">Keine Datei ausgewählt</div>
            <div class="docItem" v-for="item in itemsSelected" v-bind:key="item.id" v-bind:id="'document-'+item.id">
                <div class="list column">
                    <a href="" class="icon" v-bind:class="item.extensionname"></a>
                    <div>
                        <a v-bind:href="'/content/documents/up/' + item.filename" target="_blank">{{item.name}}</a>
                        <div class="infos"><span class="size">{{item.size}}kb</span> <span class="created" v-if="item.created">/ Hochgeladen am {{item.created}}</span></div>
                        <a href="#" v-on:click.prevent="removeItem(item)" class="btn btn-danger material-icons" v-bind:id="'unselect-' + item.id" data-id="">remove</a>
                        <a href="#" v-on:click.prevent="editItem(item)" class="btn btn-outline-primary material-icons" v-bind:id="'selected-edit-' + item.id">edit</a>
                    </div>
                </div>    
            </div>
            
            <!--
            <div class="card image mb-3" v-for="item in itemsSelected" v-bind:key="item.id">
                <div class="card-img-top-wrapper">
                    <img class="card-img-top" v-bind:src="'/content/documents/up/' + item.filename">
                </div>
                <div class="card-body">
                    <p class="card-text text-muted">
                        {{item.name}}
                    </p>
                    <a href="#" v-on:click.prevent="removeItem(item)" class="btn btn-danger material-icons" data-id="" v-bind:id="'unselect-' + item.id">remove</a>
                    <a href="#" v-on:click.prevent="editItem(item)" class="btn btn-outline-primary material-icons" v-bind:id="'edit-' + item.id">edit</a>
                </div>
            </div>
            -->
            <br/>
        </div>
        <button v-on:click="acceptItems()" class="btn btn-outline-primary" id="accept-button">Auswahl übernehmen</button>

        <hr/>
        <h3>Verfügbare Dateien</h3>
        <div class="image-item">
            <form v-on:submit.prevent method="POST">
                <div class="row">
                    <label class="control-label col-sm-3" for="searchstring">Suche</label>
                    <div class="col-sm-9">
                        <validation-provider rules="ShortText" v-slot="{ errors }" name="Suche">
                            <input type="text" id="searchstring" class="form-control" name="Searchstring" v-model="form.searchstring" v-on:change="getItems" aria-required="true">
                            <span class="invalid">{{ errors[0] }}</span>
                            <p class="help-block help-block-error " >{{(form.errormessages && form.errormessages.text)?form.errorMessages.searchstring[0]:''}}</p>
                        </validation-provider>
                    </div>
                </div>
            </form>

            <nav v-if="pagination">
                <div class="pagination pagination-sm justify-content-end" v-if="pagination && items!= null">
                    {{pagination.totalCount}} Ergebnisse
                </div>  
            </nav>
            <p class="alert alert-danger" v-if="topError">{{topErrorMessage}}</p>
            <p class="alert alert-danger" v-if="maxSelectingError">{{maxSelectingErrorMessage}}</p>
            <div class="list document-manager" id="list-unselected">
                <div class="docItem" v-for="item in items" v-bind:key="item.id" v-bind:id="'document-'+item.id">
                    <div class="list column">
                        <a href="" class="icon" v-bind:class="item.extensionname"></a>
                        <div>
                            <a v-bind:href="'/content/documents/up/' + item.filename" target="_blank">{{item.name}}</a>
                            <div class="infos"><span class="size">{{item.size}}kb</span> <span class="created" v-if="item.created">/ Hochgeladen am {{item.created}}</span></div>
                            <a href="#" v-on:click.prevent="selectItem(item)" class="btn btn-primary material-icons" v-bind:id="'select-' + item.id" data-id="">add</a>
                            <a href="#" v-on:click.prevent="editItem(item)" class="btn btn-outline-primary material-icons" v-bind:id="'edit-' + item.id">edit</a>
                        </div>
                    </div>    
                </div>
                <!--
                <div class="card image mb-3" v-for="item in items" v-bind:key="item.id" v-if="!itemsSelected.includes(item)">
                    <div class="card-img-top-wrapper">
                        <img class="card-img-top" v-bind:src="'/content/images/up/' + item.filename">
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">
                            {{item.name}}
                        </p>
                        <a href="#" v-on:click.prevent="selectItem(item)" class="btn btn-primary material-icons" v-bind:id="'select-' + item.id" data-id="">add</a>
                        <a href="#" v-on:click.prevent="editItem(item)" class="btn btn-outline-primary material-icons" v-bind:id="'edit-' + item.id">edit</a>
                    </div>
                </div>
                -->
                <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"></vue-dropzone>

                <paginationcomponent v-bind:pagination="pagination" v-if="pagination && items != null && items.length > 0"></paginationcomponent>
            </div>
            <p class="alert alert-danger" v-if="uploadErrorMessage">{{uploadErrorMessage}}</p>
            <br/>
            <button v-on:click="$parent.closeDocumentmanager()" class="btn btn-outline-primary">Abbrechen</button>

        </div>
    </span>
    
    <div v-if="view == 'documenteditor'">
        <documenteditorcomponent
            v-bind:item="editorItem"
            >
        </documenteditorcomponent>
    </div>
    
</span>
</template>

<script type="text/javascript">
var documentmanagercomponent = {
    template: '#documentmanagercomponent',
    props: {
        documentList: Object,
        maxSelecting: null
    },
    components: {
        paginationcomponent,
        vueDropzone: vue2Dropzone,
        documenteditorcomponent
    },
    data: function(){
        return {
            view: 'documentmanager', //kann zu imageeditor geändert werden
            editorItem: null,            
            topError: false,
            topErrorMessage: 'Die Daten konnten nicht geladen werden.',
            maxSelectingError: false,
            maxSelectingErrorMessage: 'Sie können maximal ' + this.maxSelecting +' Image' + ((this.maxSelecting>1)?'s':'') +' auswählen',
            form: {
                errorMessages: null,
                searchstring: null,
                getLink: '/documentmanager/get-items'
            },
            itemsSelected: [
            ],
            items: [],
            pagination: {
                activePage: 0,
                pageSize: 20
            },
            uploadErrorMessage: null,
            dropzoneOptions: {
                url: '/documentmanager/upload',
                paramName: 'DocumentUploadForm[documentFiles]',
                //thumbnailWidth: 150,
                //maxFilesize: 0.5,
                uploadMultiple: true,
                //resizeHeight: 480,
                maxFiles: 10,
                acceptedFiles: 'application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx',
                success: this.afterUpload,
                error: this.errorUpload
            }
        }
    },
    mounted: function() {
        this.itemsSelected = JSON.parse(  JSON.stringify(this.documentList.documents))
        this.getItems()
    },
    methods: {
        getItems() {
            self = this;
            //load items
            //push already selected docids to an array
            //excludeItems will be excluded from selectable list in v-for
            var excludeItems = []
            this.itemsSelected.forEach(function(elem){
                //console.log(elem.id)
                excludeItems.push(elem.id) 
            })
            //get-items
            $.post(self.form.getLink,
            {
                "SearchFilterForm[searchstring]": this.form.searchstring,
                "SearchFilterForm[pageno]": this.pagination.activePage,
                "SearchFilterForm[pageSize]": this.pagination.pageSize,
                "SearchFilterForm[exclude][]": excludeItems
            })
            .done(function(data) {
                if(data.items){
                    self.topError           = false
                    self.form.errorMessages = null

                    self.items      = data.items
                    self.pagination = data.pagination
                }
                else {
                    self.topError           = true
                    self.form.errorMessages = data.errormessages
                }
            })
            .fail(function() {
                self.topError = true
                self.form.errorMessages = null
                console.log( "error loading items" )
            });
        },
        selectItem(item){
            if(this.maxSelecting == null || this.itemsSelected.length < this.maxSelecting){
                //console.log("Selected: " + item.id
                this.itemsSelected.push(item)
                this.getItems()
            }
            else {
                this.maxSelectingError = true;
            }
        },
        removeItem(item){
            this.maxSelectingError = false;
            //remove from itemsSelected by putting all others in new array
            //have not found a correct remove function of javascript (splice has not worked)
            var newSelected = []
            this.itemsSelected.forEach(function(elem){
                if(elem.id != item.id)
                    newSelected.push(elem)
            })
            this.itemsSelected = newSelected
            
            this.getItems()
        },
        //hochladen
        afterUpload(file, response){
            console.log('afterUpload: ' + response)
            console.log('afterUpload: ' + file)
            if(response.messageerrors.length > 0){
                //fehler
                this.$refs.myVueDropzone.removeAllFiles()
                this.uploadErrorMessage = response.messageerrors[0]['documentFiles'][0]
            }
            else {
                //erfolgreich
                this.$refs.myVueDropzone.removeAllFiles()
                this.uploadErrorMessage = null
                this.getItems()
            }
        },
        errorUpload(file, response){
            this.$refs.myVueDropzone.removeAllFiles()
            this.uploadErrorMessage = response
            console.log('errorUpload: ' + response)
        },
        acceptItems(){
            this.documentList.documents = this.itemsSelected
            this.$parent.closeDocumentmanager();
        },
        editItem(item){
            this.editorItem = item
            this.view = "documenteditor"
        },
        closeDocumenteditor(){
            this.view = "documentmanager"
        }     
    }
}

</script>
