<?php
use yii\helpers\Url;

/**
 * Sorts the teaserlist
 * parent muss closeSort() - Funktion haben
 */

/* vue laden */
use app\assets\VueAsset;
VueAsset::register($this);
use app\assets\VueSortableAsset;
VueSortableAsset::register($this);

?>

<template id="teaserlist-sort-component">
    <div class="teaserlist">
        <div class="edit-list-line">
            <a class="btn btn-outline-light material-icons" title="Speichern" v-on:click="save">save</a>
            <a class="btn btn-outline-light material-icons" title="Abbrechen" v-on:click="closeSort">close</a>
        </div>
        
        
        <div>
            <div class="alert alert-danger" role="status" v-if="loading.saveHasError">
                <span class="">{{loading.saveErrorMessage}}</span>
            </div>
            <draggable v-model="sortItems">
                <transition-group>
                    <div class="card mb-12 teaser" v-for="item in sortItems" :key="item.id">
                        <div class="row g-0">
                                <div class="overlay h-100 sort">
                                    <div class="position-absolute bottom-0 end-0 release-info"><span class="badge bg-danger">Teaser an die gewünschte Position ziehen und speichern</span></div>
                                </div>
                            <div class="col-sm-1 edit-column sort-icon" role="button">
                                <span class="fa fa-arrows-alt"></span>
                            </div>
                            <div class="col-sm-4" v-if="item.imageList.uploadedimages.length>0">
                                <img class="img-fluid" v-bind:src="'/content/images/up/'+item.imageList.uploadedimages[0].filename" alt="">
                            </div>
                            <div class="teaser-text" v-bind:class="(item.imageList.uploadedimages.length > 0) ? 'col-sm-7' : 'col-sm-11'" v-bind:class="{'col-md-11': item.imageList.uploadedimages.length == 0}" v-bind:class="{'col-md-7' : item.imageList.uploadedimages.length > 0}">
                              <div class="card-body">
                                <h5 class="card-title">{{item.headline}}</h5>
                                <p class="card-text" v-html="item.text"></p>
                                <p class="card-text" v-if="item.updated"><small class="text-muted">Last updated {{item.updated}}</small></p>
                              </div>
                            </div>
                        </div>
                    </div>
                </transition-group>
            </draggable>
        </div>
    </div>    
</template>

<script type="text/javascript">
    Vue.component('draggable', vuedraggable);

var teaserlistSortComponent = {
    template: '#teaserlist-sort-component',
    props: ['items'],
    data: function(){
        return {
            loading: {
                saveLink: '<?= Url::toRoute(['teaserlist/sort-save']) ?>/',
                saveHasError: false,
                saveErrorMessage: 'Sortierung konnte nicht gespeichert werden.',
            },
            sortItems: []
        }
    },
    mounted: function() {
        this.init()
    },
    methods: {
        init(){
            //zum aendern kopieren, um Orginal nihct zu ueberschreiben
            this.sortItems = this.items
        },
        save() {
            
                self = this;

                //get new array of sorted item-ids
                var ids = [];
                this.sortItems.forEach(function(item){
                    ids.push(item.id)
                });
                    
                //save
                $.post(self.loading.saveLink,
                {
                    "SortForm[ids]": ids
                })
                .done(function(data) {
                    if(!data.saved){
                        //error
                        self.loading.saveHasError = true
                    }
                    else {
                        //success
                        self.loading.saveHasError = false
                        
                        //parent neuladen und sort schließen
                        self.$parent.getItems()
                        self.closeSort()
                    }
                })
                .fail(function() {
                    self.loading.saveHasError = true
                    console.log( "error saving item" )
                });
            
            
        },
        closeSort() {
            this.$parent.closeSort()
        }
    }
}

</script>
