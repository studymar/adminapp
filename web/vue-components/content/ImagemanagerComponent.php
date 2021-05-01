<?php
/**
 * Shows a list for selecting one or more Images
 * Preconditions:
 * - Parent must have a function for closing the manager
 *      closeImagemanager()
 * - imageList given as props
 */
use app\assets\Vue2DropzoneAsset;
Vue2DropzoneAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/';
else $env_vuecomponent_dir = "vue-components/content/";

include_once($env_vuecomponent_dir.'PaginationComponent.php');
include_once($env_vuecomponent_dir.'ImageeditorComponent.php');

?>
<template id="imagemanagercomponent">
<span>
    <span v-if="view == 'imagemanager'">
        <!-- Imagemanager -->
        <h3>Ausgewählte Images</h3>
        <div class="list column image-item" id="list-selected">
            <div class="noImage-text" v-if="itemsSelected.length == 0">Kein Image ausgewählt</div>

            <div class="card image mb-3" v-for="item in itemsSelected" v-bind:key="item.id">
                <div class="card-img-top-wrapper">
                    <img class="card-img-top" v-bind:src="'/content/images/up/' + item.filename">
                </div>
                <div class="card-body">
                    <p class="card-text text-muted">
                        {{item.name}}
                    </p>
                    <a href="#" v-on:click.prevent="removeItem(item)" class="btn btn-danger material-icons" data-id="" v-bind:id="'unselect-' + item.id">remove</a>
                    <a href="#" v-on:click.prevent="editItem(item)" class="btn btn-outline-primary material-icons" v-bind:id="'edit-' + item.id">edit</a>
                </div>
            </div>
            <br/>
        </div>
        <button v-on:click="acceptItems()" class="btn btn-outline-primary" id="accept-button">Auswahl übernehmen</button>

        <hr/>
        <h3>Verfügbare Images</h3>
        <div class="image-item">
            <form v-on:submit.prevent method="POST">
                <div class="row">
                    <label class="control-label col-sm-3" for="searchstring">Suche</label>
                    <div class="col-sm-9">
                        <validation-provider rules="ShortText" v-slot="{ errors }">
                            <input type="text" id="searchstring" class="form-control" name="Searchstring" v-model="form.searchstring" v-on:change="getItems" aria-required="true">
                            <span class="invalid">{{ errors[0] }}</span>
                            <p class="help-block help-block-error ">{{(form.errorMessages)?form.errorMessages.searchstring[0]:''}}</p>
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
            <div class="list column image-item" id="list-unselected">
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
                <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"></vue-dropzone>

            </div>
            <paginationcomponent v-bind:pagination="pagination" v-if="pagination && items != null && items.length > 0"></paginationcomponent>

            <p class="alert alert-danger" v-if="uploadErrorMessage">{{uploadErrorMessage}}</p>
            <br/>
            <button v-on:click="$parent.closeImagemanager()" class="btn btn-outline-primary">Abbrechen</button>

        </div>
    </span>
    
    <div v-if="view == 'imageeditor'">
        <imageeditorcomponent
            v-bind:item="editorItem"
            >
        </imageeditorcomponent>
    </div>
    
</span>
</template>

<script type="text/javascript">
var imagemanagercomponent = {
    template: '#imagemanagercomponent',
    props: {
        imageList: Object,
        maxSelecting: null
    },
    components: {
        paginationcomponent,
        vueDropzone: vue2Dropzone,
        imageeditorcomponent
    },
    data: function(){
        return {
            view: 'imagemanager', //kann zu imageeditor geändert werden
            editorItem: null,            
            topError: false,
            topErrorMessage: 'Die Daten konnten nicht geladen werden.',
            maxSelectingError: false,
            maxSelectingErrorMessage: 'Sie können maximal ' + this.maxSelecting +' Image' + ((this.maxSelecting>1)?'s':'') +' auswählen',
            form: {
                errorMessages: null,
                searchstring: null,
                getLink: '/imagemanager/get-items'
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
                url: '/imagemanager/upload',
                paramName: 'UploadImageForm[imageFiles]',
                thumbnailWidth: 150,
                //maxFilesize: 0.5,
                uploadMultiple: true,
                resizeHeight: 480,
                maxFiles: 10,
                acceptedFiles: 'image/jpeg,image/gif,image/png',
                success: this.afterUpload,
                error: this.errorUpload
            }
        }
    },
    mounted: function() {
        this.itemsSelected = JSON.parse(  JSON.stringify(this.imageList.uploadedimages))
        this.getItems()
    },
    methods: {
        getItems() {
            self = this;
            //load items
            //exclude already inserted items
            var excludeItems = []
            this.itemsSelected.forEach(function(elem){
                //console.log(elem.id)
                excludeItems.push(elem.id) 
            })
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
            //console.log('afterUpload: ' + response)
            //console.log('afterUpload: ' + file)
            this.$refs.myVueDropzone.removeAllFiles()
            this.uploadErrorMessage = null
            this.getItems()
        },
        errorUpload(file, response){
            this.$refs.myVueDropzone.removeAllFiles()
            this.uploadErrorMessage = response
            console.log('errorUpload: ' + response)
        },
        acceptItems(){
            this.imageList.uploadedimages = this.itemsSelected
            this.$parent.closeImagemanager();
        },
        editItem(item){
            //console.log('edit'+item.id)
            this.editorItem = item
            this.view = "imageeditor"
        },
        closeImageeditor(){
            this.view = "imagemanager"
        }     
    }
}

</script>
