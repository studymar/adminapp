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

/*
if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/voting/';
else $env_vuecomponent_dir = "vue-components/voting/";
*/
//include_once($env_vuecomponent_dir.'TeaserlistSortComponent.php');

?>

<template id="voting-topic-component">
    <div id="votingTopicComponent" class="p-2">
        
        <div class="d-flex justify-content-between title-block">
            <h3 class="fs-5">
                <a href="" class="onlySmall" v-on:click.prevent="$parent.closeTopic()">
                    <i class="material-icons">keyboard_arrow_left</i>
                </a>
                {{topic.headline}}
            </h3>
            <br/>
            <div id="pagebar-open-btn">
                <a class="btn single btn-light" data-bs-toggle="collapse" data-bs-target="#voting-topic-overlay" role="button"><i class="material-icons">settings</i></a>
            </div>
        </div>

        <p class="alert alert-danger" v-if="config.topErrorLoading">{{config.topErrorLoadingMessage}}</p>
        <p class="alert alert-light" v-if="items == null || items.length == 0">{{config.topNoVotings}}</p>

        <div class="list-group" v-if="items != null && items.length>0" >
           <span v-for="(item,index) in items" :key="item.id">
                <div class="list-group-item rounded mb-1">
                    <div class="d-flex w-100 justify-content-between">
                      <div class="mb-1 fs-5">{{item.question}}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-1 bigicon p-0">
                            <span>
                                <span class="material-icons green" v-if="item.active == 1">play_circle_outline</span>
                                <span class="material-icons red" v-else >pause_circle_outline</span>
                            </span>
                        </div>
                        <div class="col-sm-10 mb-1">
                            <ul class="property-list">
                                <li v-if="item.votingtype">
                                    <span class="material-icons">east</span>
                                    {{item.votingtype.description}}
                                </li>
                                <li>
                                    <span class="material-icons" v-bind:class="[{'green':item.active == 1},{'red':item.active != 1}]">{{(item.active == 1)?'check':'close' }}</span>
                                    {{(item.active == 1)?'Umfrage läuft...':'Umfrage nicht aktiv' }}
                                </li>
                                <li>
                                    <span class="material-icons" v-bind:class="[{'green':item.showresults == 1},{'red':item.showresults != 1}]">{{(item.showresults == 1)?'check':'close'}}</span>
                                    {{(item.showresults == 1)?'Ergebnis für alle sichtbar':'Ergebnis ist nur für Admins' }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-1 p-0">
                            <a href="" class="btn single btn-light" v-on:click.prevent="$parent.openTopicQuestionEdit(item)">
                                <span class="material-icons">edit</span>
                            </a>
                            <a href="" class="btn single btn-light" v-on:click.prevent="$parent.openTopicQuestionResult(item)">
                                <span class="material-icons">stacked_bar_chart</span>
                            </a>
                        </div>
                    </div>
                </div>
           </span>
        </div>

        <br/><br/>
    </div>
</template>

<script type="text/javascript">

var votingTopicComponent = {
    template: '#voting-topic-component',
    props: ['topic'],
    components: {
    },
    data: function(){
        return {
            config: {
                topNoVotings: 'Zur Zeit sind keine Abstimmungen vorhanden.',
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                getLink: '<?= Url::toRoute(['voting/get-all-votings']).'/' ?>',
                showTopic: '<?= Url::toRoute(['voting/edit-topic-votings']).'/' ?>',
                showResults: '<?= Url::toRoute(['voting/show-results']).'/' ?>'
            },
            items: [
            ]
        }
    },
    mounted: function() {
        this.getItems()
    },
    watch: {
        topic: function(newVal, oldVal) { 
            this.getItems()
        }
    },
    methods: {
        /*
            onLoad() {
                //alle 10s aktualisieren
               this.getItems();
               setInterval(this.getItems, 10000);
            },
        */
            getItems() {
                //item laden
                self = this;
                $.post(self.config.getLink + this.topic.id,
                {
                })
                .done(function(data) {
                    if(data.items){
                        self.items = data.items
                        self.config.topErrorLoading = false
                    }
                    else {
                        self.items = []
                        self.config.topErrorLoading = true
                    }
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error loading item" )
                });
            },
            /*
            editItem(item){
                console.log("edit")
            },
            deleteItem(item){
                console.log("delete")
            },
            sortUpItem(item){
                console.log("sortUp")
            },
             */
            getReleasestatusOfTopic(item){
               if(item.active)
                   return 'released'
               else 
                   return 'notreleased'
            },
            getShowResultsCssClass(item){
               if(item.showresults == 1)
                   return 'btn-light'
               else 
                   return 'btn-danger'
            },
            isFirst(index){
                if(index == 0)
                    return true
                else return false
            }
    }
}

</script>
