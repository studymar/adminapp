<?php
use yii\helpers\Url;

/**
 * Shows on an editpage the teaserlist component
 * Preconditions:
 * - pageHasTemplate given as props
 */
/* vue laden */
use app\assets\VueAsset;
VueAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/publicContent/';
else $env_vuecomponent_dir = "vue-components/publicContent/";

include_once($env_vuecomponent_dir.'TeaserlistSortComponent.php');

?>

<template id="teaserlist-public-component">
    <div class="teaserlist">
        <span v-if="view == 'edit'">
            <div class="edit-list-line">
                <a v-on:click="view = 'sort'" class="btn btn-outline-light fa fa-sort-amount-desc" v-if="items.length > 0"></a>
                <a v-bind:href="'<?= Url::toRoute(['teaserlist/create']) ?>'+'/'+ item.page_has_template_id" class="btn btn-outline-light material-icons">add</a>
            </div>

            <div class="spinner-border spinner-border-sm" role="status" v-if="loading.getIsRunning">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="alert alert-danger" role="status" v-if="loading.getHasError">
                <span class="">{{loading.getErrorMessage}}</span>
            </div>

            <div v-if="loading.getIsRunning == false && loading.getHasError == false">
                <div class="alert alert-dismissible" role="status" v-if="items.length == 0">
                    <span class="">{{loading.getEmptyMessage}}</span>
                </div>
                <div class="card mb-12 teaser" v-for="item in items" :key="item.id">
                    <div class="row g-0">
                        <div class="overlay h-100 collapse" v-bind:id="'overlay' + item.id">
                            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0" aria-label="Close" data-bs-toggle="collapse" v-bind:data-bs-target="'#overlay' + item.id"></button>
                            <div class="overlay-buttons align-middle">
                                <a v-bind:href="'<?= Url::toRoute(['teaserlist/edit']) ?>'+'/'+item.id" class="btn btn-outline-light">Editieren</a><br/>
                                <a v-bind:href="'<?= Url::toRoute(['teaserlist/delete']) ?>'+'/'+item.id" class="btn btn-outline-light">Löschen</a><br/>
                            </div>
                            <div class="position-absolute bottom-0 end-0 release-info"><span class="badge bg-success" v-if="item.release.is_released == 1">sichtbar</span></div>
                            <div class="position-absolute bottom-0 end-0 release-info"><span class="badge bg-danger" v-if="item.release.is_released == 0">nicht sichtbar</span></div>
                        </div>
                        <div class="col-sm-1 edit-column" data-bs-toggle="collapse" v-bind:data-bs-target="'#overlay' + item.id" role="button">
                            <div class="position-absolute bottom-0 end-0 visible-badge"><span class="badge bg-success" v-if="item.release.is_released == 1">S</span></div>
                            <div class="position-absolute bottom-0 end-0 visible-badge"><span class="badge bg-danger" v-if="item.release.is_released == 0">N</span></div>
                        </div>
                        <div class="col-sm-4" v-if="item.imageList.uploadedimages.length>0">
                            <img class="img-fluid" v-bind:src="'/content/images/up/'+item.imageList.uploadedimages[0].filename" alt="">
                            <a v-bind:href="item.linkItem.targetPage.urlname" v-if="item.linkItem.targetPage" class="stretched-link"></a>
                        </div>
                        <div class="teaser-text" v-bind:class="(item.imageList.uploadedimages.length > 0) ? 'col-sm-7' : 'col-sm-11'" v-bind:class="{'col-md-11': item.imageList.uploadedimages.length == 0}" v-bind:class="{'col-md-7' : item.imageList.uploadedimages.length > 0}">
                          <div class="card-body">
                            <a v-bind:href="item.linkItem.targetPage.urlname" v-if="item.linkItem.targetPage" class="stretched-link"></a>
                            <h5 class="card-title">{{item.headline}}</h5>
                            <p class="card-text" v-html="item.text"></p>
                            <p class="card-text" v-if="item.updated"><small class="text-muted">Last updated {{item.updated}}</small></p>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </span>
        <span v-if="view == 'sort'">
            <teaserlist-sort-component
                v-bind:items="items"
                >
            </teaserlist-sort-component>
        </span>
    </div>    
</template>

<script type="text/javascript">

var teaserlistPublicComponent = {
    template: '#teaserlist-public-component',
    props: ['item'],
    components: {
        teaserlistSortComponent
    },
    data: function(){
        return {
            view: 'edit',
            loading: {
                getLink: '<?= Url::toRoute(['teaserlist/get-all']) ?>/',
                getIsRunning: true,
                getHasError: false,
                getErrorMessage: 'Daten konnten nicht geladen werden.',
                getEmptyMessage: 'Zur Zeit gibt es keine Neuigkeiten.'
            },
            items: []
        }
    },
    mounted: function() {
        this.getItems()
    },
    methods: {
        getItems() {
            self = this;
            //load items
            $.get(self.loading.getLink + this.item.page_has_template_id)
            .done(function(data) {
                if(data.items){
                    self.loading.getIsRunning = false
                    self.loading.getHasError  = false

                    self.items      = data.items
                }
                else {
                    self.loading.getIsRunning = false
                    self.loading.getHasError  = true
                }
            })
            .fail(function() {
                self.loading.getIsRunning = false
                self.loading.getHasError  = true
                console.log( "error loading items" )
            });
        },
        getLink(linkItem) {
            if(linkItem.extern_url)
                return linkItem.extern_url
            else 
                return false
        },
        closeSort() {
            this.view = 'edit'
        }
    }
}

</script>
