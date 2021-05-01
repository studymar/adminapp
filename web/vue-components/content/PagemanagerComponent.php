<?php
/**
 * Shows a list for selecting one Page
 * Preconditions:
 * - Parent must have a function for closing the pagemanager
 *      closePagemanager()
 * - linkItem given as props
 */
if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/';
else $env_vuecomponent_dir = "vue-components/content/";

include_once($env_vuecomponent_dir.'PaginationComponent.php');

?>
<template id="pagemanagercomponent">
    <span>

    <!-- Pagemanager -->
    <h3>Seitenauswahl</h3>
    <div class="list">
        <p class="alert alert-danger" v-if="topError">{{topErrorMessage}}</p>
        
        <form v-on:submit.prevent="onSubmit" method="POST">
            <div class="row">
                <label class="control-label col-sm-3" for="searchstring">Suche</label>
                <div class="col-sm-9">
                    <input type="text" id="searchstring" class="form-control" name="Searchstring" v-model="form.searchstring" v-on:change="getItems" aria-required="true">
                    <p class="help-block help-block-error ">{{(form.errorMessages)?form.errorMessages.searchstring[0]:''}}</p>
                </div>
            </div>
        </form>
        
        <nav v-if="pagination">
            <div class="pagination pagination-sm justify-content-end" v-if="pagination">
                {{pagination.totalCount}} Ergebnisse
            </div>  
        </nav>
        <div class="" id="pagemanager-list">
            <div class="card mb-3" v-for="item in items" v-bind:key="item.id">
                <div class="card-doc-top"></div>
                <div class="card-body">
                    <h5 class="card-title">{{item.headline}}</h5>
                    <p class="card-text">
                        Erstellt: {{item.created}} von {{item.created_by}}
                    </p>
                    <a href="#" v-on:click.prevent="selectItem(item)" class="btn btn-primary saveLink" v-bind:id="'select-'+item.id">Auswählen</a>
                    <a v-bind:href="'/'+item.urlname" target="_blank" class="btn btn-light">Seite anzeigen</a>
                    <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
                </div>
            </div>
            <paginationcomponent v-bind:pagination="pagination"></paginationcomponent>
        </div>
        
        <button v-on:click="$parent.closePagemanager()" class="btn btn-outline-primary">Zurück</button>
        
    </div>

    </span>
</template>

<script type="text/javascript">
var pagemanagercomponent = {
    template: '#pagemanagercomponent',
    props: {
        linkItem: Object
    },
    components: {
        paginationcomponent
    },
    data: function(){
        return {
            topError: false,
            topErrorMessage: 'Die Daten konnten nicht geladen werden.',
            form: {
                errorMessages: null,
                searchstring: null,
                getLink: '/pagemanager/get-items'
            },
            items: null,
            pagination: {
                activePage: 0,
                pageSize: 20
            }
        }
    },
    mounted: function() {
        this.getItems()
    },
    methods: {
        getItems() {
            self = this;
            //load items
            $.post(self.form.getLink,
            {
                "PageFilterForm[searchstring]": this.form.searchstring,
                "PageFilterForm[pageno]": this.pagination.activePage,
                "PageFilterForm[pageSize]": this.pagination.pageSize
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
            //console.log("Selected: " + item.id)
            this.linkItem.targetPage            = {}
            this.linkItem.target_page_id        = item.id
            this.linkItem.targetPage.linkname   = item.linkname
            this.linkItem.targetPage.headline   = item.headline
            this.linkItem.targetPage.urlname    = item.urlname
            //pagemanager schließen
            this.$parent.closePagemanager()
        }
    }
}

</script>
