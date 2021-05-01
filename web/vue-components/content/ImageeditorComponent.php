<?php
/**
 * Shows an editor for an image
 * Preconditions:
 * - Parent must have a function for closing the editor
 *      closeImageeditor()
 */

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/';
else $env_vuecomponent_dir = "vue-components/content/";

//include_once($env_vuecomponent_dir.'PaginationComponent.php');
//include_once($env_vuecomponent_dir.'ImageeditorComponent.php');
if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/';
else $env_vuecomponent_dir = "vue-components/content/";

include_once($env_vuecomponent_dir.'ImageCropperComponent.php');
include_once($env_vuecomponent_dir.'ConfirmDeleteComponent.php');

?>
<template id="imageeditorcomponent">
    <span>
    <span  v-if="view == null">
    <h3>Imageeditor</h3>

    <p class="alert alert-success" v-if="form.topSuccess">{{form.topSuccessMessage}}</p>
    <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
    <p class="alert alert-danger" v-if="form.topErrorIntern">{{form.topErrorInternMessage}}</p>

    <div class="list card mb-3">
        <div class="row no-gutters">
            <div>
                <div class="card image md-4">
                    <div class="card-img-top-wrapper">
                        <img class="card-img-top" v-bind:src="'/content/images/up/' + item.filename">
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">
                            {{item.name}}
                        </p>
                    </div>
                </div>
                <div class="image-wrap-button">
                    <button v-on:click="view='imagecropper'" class="btn btn-outline-primary" v-bind:id="'crop-' + item.id">Zuschneiden</button>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-body">

                    <div class="row field-image-name">
                        <label class="control-label col-sm-3" for="image-name">Bezeichnung</label>
                        <div class="col-sm-9">
                            <validation-provider rules="ShortText" v-slot="{ errors }">
                                <input type="text" id="text-headline" class="form-control" name="name" v-model="item.name" aria-required="false">
                                <span class="invalid">{{ errors[0] }}</span>
                                <p class="help-block help-block-error " >{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.name[0]:''}}</p>
                            </validation-provider>

                        </div>
                    </div> 

                    <div class="row field-image-filename required">
                        <label class="control-label col-sm-3" for="image-filename">Filename</label>
                        <div class="col-sm-9">
                            {{item.filename}}
                        </div>
                    </div> 

                    <div class="row field-image-dimension">
                        <label class="control-label col-sm-3" for="image-dimension">BreiteXHöhe</label>
                        <div class="col-sm-9">
                            {{item.width}}x{{item.height}}
                        </div>
                    </div> 

                    <div class="row field-image-size">
                        <label class="control-label col-sm-3" for="image-size">Größe</label>
                        <div class="col-sm-9">
                            {{item.size}} kb
                        </div>
                    </div> 

                    <div class="row field-image-usage">
                        <label class="control-label col-sm-3" for="image-usage">Eingebunden auf Seite</label>
                        <div class="col-sm-9">
                            <div class="alert alert-info" v-if="!usingPages.list.length">{{usingPages.emptyText}}</div>
                            <ul>
                                <li v-for="item in usingPages.list"><a v-bind:href="'/'+getUrlname(item)" target="_blank">{{item.headline}}</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>    
    
    <br/>
    <p class="alert alert-danger" v-if="form.topErrorDelete">{{form.topErrorDeleteMessage}}</p>
    <div class="button-line">
        <button v-on:click="saveItem" class="btn btn-primary">Speichern</button>
        <button v-on:click="$parent.closeImageeditor()" class="btn btn-outline-primary">Abbrechen</button>
        <button class="btn btn-danger right-aligned" v-bind:disabled="!usingPages.isDeletable" data-toggle="modal" data-target="#deleteModal">Bild löschen</button>
    </div>

    <confirmdeletecomponent
        object-type="folgendes Image"
        v-bind:object-name="item.name"
        v-bind:object-detail="item.filename"
        >
    </confirmdeletecomponent>
     
    </span>

    <div v-if="view == 'imagecropper'">
        <imagecroppercomponent
            v-bind:item="item"
            >
        </imagecroppercomponent>
    </div>    
    </span>
    
</template>

<script type="text/javascript">
var imageeditorcomponent = {
    template: '#imageeditorcomponent',
    props: {
        item: Object
    },
    components: {
        imagecroppercomponent,
        confirmdeletecomponent
    },
    data: function(){
        return {
            view: null,
            form: {
                topError: false,
                topErrorIntern: false,
                topSuccess: false,
                topSuccessMessage: 'Gespeichert',
                topErrorMessage: 'Bitte überprüfen Sie Ihre Eingaben',
                topErrorInternMessage: 'Daten konnten nicht gespeichert werden.',
                topErrorDelete: false,
                topErrorDeleteMessage: 'Image konnte nicht gelöscht werden.',
                errorMessages: {},
                saveLink: '/imagemanager/save-item/',
                getLink: '/imagemanager/get-item/',
                deleteLink: '/imagemanager/delete-item/'
            },
            usingPages: {
                getLink: '/imagemanager/get-using-pages/',
                list: [],
                isDeletable: false,
                emptyText: "bisher auf keiner Seite eingebunden",
                loadingError: false,
                loadingErrorMessage: "Seiten konnten nicht geladen werden"
            }
        }
    },
    mounted: function() {
        this.getUsingPages()
    },
    methods: {
        /**
         * getItem after crop 
         */
        getItem() {
            self = this
            //load item
            $.post(self.form.getLink + self.item.id)
            .done(function(data) {
                if(data.item){
                    self.form.topErrorIntern = false
                    self.$parent.editorItem       = data.item
                    //auch im manager neuladen
                    self.$parent.getItems()
                }
                else {
                    self.form.topErrorIntern = true
                    self.form.errorMessages = data.errormessages
                }
            })
            .fail(function() {
                self.form.topErrorIntern = true
                console.log( "error getting item" )
            })
        },
        saveItem(){
            self = this;

            $.post(self.form.saveLink + this.item.id,
            {
                "UploadedimageEditForm[name]": this.item.name
            })
            .done(function(data) {
                if(!data.saved){
                    self.form.topSuccess = false
                    self.form.topError = true
                    self.form.topErrorIntern = false
                    self.form.errorMessages = data.errormessages
                }
                else {
                    self.form.topSuccess = true
                    self.form.topError = false
                    self.form.topErrorIntern = false
                    self.form.errorMessages = null
                    self.$parent.closeImageeditor()
                    self.$parent.getItems()
                }
            })
            .fail(function() {
                self.form.topSuccess = false
                self.form.topError = false
                self.form.topErrorIntern = true
                console.log( "error saving item" )
            });
        },
        deleteItem(){
            self = this;
            $.post(self.form.deleteLink + this.item.id)
            .done(function(data) {
                if(!data.success){
                    self.form.topErrorDelete = true
                }
                else {
                    self.form.topErrorDelete = false
                    //auch im manager neuladen
                    self.$parent.closeImageeditor()
                    self.$parent.getItems()
                }
            })
            .fail(function() {
                self.form.topErrorDelete = true
                console.log( "error deleting item" )
            });
        },   
        getUsingPages(){
            self = this
            $.get(self.usingPages.getLink + self.item.id)
            .done(function(data) {
                //console.log(data.length)
                self.usingPages.list = data //im zweifel empty array bei unbenutzt
                self.usingPages.loadingError = false
                if(self.usingPages.list.length === 0)
                    self.usingPages.isDeletable = true
            })
            .fail(function() {
                self.usingPages.loadingError = true
                self.usingPages.isDeletable = false
                console.log( "could not load using pages" )
            })
        },
        getUrlname(item){
            if(item.urlname != null)
                return item.urlname
            else return ''
        },
        closeImagecropper(){
            this.view = null
        }     

    }
}

</script>
