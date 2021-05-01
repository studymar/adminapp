<?php
/**
 * Shows an editor for an document
 * Preconditions:
 * - Parent must have a function for closing the editor
 *      closeDocumenteditor()
 */

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/';
else $env_vuecomponent_dir = "vue-components/content/";

include_once($env_vuecomponent_dir.'ConfirmDeleteComponent.php');

?>
<template id="documenteditorcomponent">
    <span>
    <h3>Documenteditor</h3>

    <p class="alert alert-success" v-if="form.topSuccess">{{form.topSuccessMessage}}</p>
    <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
    <p class="alert alert-danger" v-if="form.topErrorIntern">{{form.topErrorInternMessage}}</p>

    <div class="list card mb-3">
        <div class="row no-gutters">
                <div class="card-body">

                    <div class="row field-document-name">
                        <label class="control-label col-sm-6" for="document-name">Bezeichnung</label>
                        <div class="col-sm-6">
                            <input v-validate="''" type="text" id="name" class="form-control" name="name" v-model="item.name" aria-required="true">
                            <p class="help-block help-block-error " v-if="errors.has('name')">{{errors.first('name')}}</p>
                            <p class="help-block help-block-error " >{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.name[0]:''}}</p>
                        </div>
                    </div> 

                    <div class="row field-document-filename required">
                        <label class="control-label col-sm-6" for="document-filename">Filename</label>
                        <div class="col-sm-6">
                            {{item.filename}}
                        </div>
                    </div> 

                    <div class="row field-document-size">
                        <label class="control-label col-sm-6" for="document-size">Größe</label>
                        <div class="col-sm-6">
                            {{item.size}} kb
                        </div>
                    </div> 

                    <div class="row field-document-usage">
                        <label class="control-label col-sm-6" for="document-usage">Eingebunden auf Seite</label>
                        <div class="col-sm-6">
                            <div class="alert alert-info" v-if="!usingPages.list.length">{{usingPages.emptyText}}</div>
                            <ul>
                                <li v-for="item in usingPages.list"><a v-bind:href="'/'+getUrlname(item)" target="_blank">{{item.headline}}</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
        </div>
    </div>    
    
    <br/>
    <p class="alert alert-danger" v-if="form.topErrorDelete">{{form.topErrorDeleteMessage}}</p>
    <div class="button-line">
        <button v-on:click="saveItem" class="btn btn-primary">Speichern</button>
        <button v-on:click="$parent.closeDocumenteditor()" class="btn btn-outline-primary">Abbrechen</button>
        <button class="btn btn-danger right-aligned" v-bind:disabled="!usingPages.isDeletable" data-toggle="modal" data-target="#deleteModal">Dokument löschen</button>
    </div>

    <confirmdeletecomponent
        object-type="folgendes Dokument"
        v-bind:object-name="item.name"
        v-bind:object-detail="item.filename"
        >
    </confirmdeletecomponent>
    

    
    </span>
    
</template>

<script type="text/javascript">
var documenteditorcomponent = {
    template: '#documenteditorcomponent',
    props: {
        item: Object
    },
    components: {
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
                topErrorDeleteMessage: 'Dokument konnte nicht gelöscht werden.',
                errorMessages: {},
                saveLink: '/documentmanager/save-item/',
                getLink: '/documentmanager/get-item/',
                deleteLink: '/documentmanager/delete-item/'
            },
            usingPages: {
                getLink: '/documentmanager/get-using-pages/',
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
        saveItem(){
            this.$validator.validateAll().then((result) => {
            if (result) {
                //console.log('submit')
                self = this;

                $.post(self.form.saveLink + this.item.id,
                {
                    "DocumentEditForm[name]": this.item.name
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
                        self.$parent.closeDocumenteditor()
                        self.$parent.getItems()
                    }
                })
                .fail(function() {
                    self.form.topSuccess = false
                    self.form.topError = false
                    self.form.topErrorIntern = true
                    console.log( "error saving item" )
                });
            }})
        },
        deleteItem(){
            self = this;

            $.post(self.form.deleteLink + self.item.id)
            .done(function(data) {
                if(!data.success){
                    self.form.topErrorDelete = true
                }
                else {
                    self.form.topErrorDelete = false
                    //auch im manager neuladen
                    self.$parent.closeDocumenteditor()
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
                console.log( "could not load using pages" )
            })
        },
        getUrlname(item){
            if(item.urlname != null)
                return item.urlname
            else return ''
        }

    }
}

</script>
