<?php

/* 
 * Startseite der Votings für EDIT
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\assets\VueAsset;
VueAsset::register($this);


if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/editline/';
else $env_vuecomponent_dir = "vue-components/editline/";

include_once($env_vuecomponent_dir.'EditlineComponent.php');

?>

    <div id="listOfVotings" class="voting-list list">
        <h2>Aktuelle Umfrage/Abstimmungs-Themen</h2>

        <?= $this->render('@app/views/partials/_listeditline',[
            'buttons'   => [
                [
                    'name'    => 'Voting-Thema hinzufügen',
                    'url'     => Url::toRoute(['voting/create-topic']),
                    'icon'    => 'playlist_add',
                ],
            ]
        ]); //List-Edit-Buttons ?>
        
        <br/>
        
        <p class="alert alert-danger" v-if="config.topErrorLoading">{{config.topErrorLoadingMessage}}</p>
        <p class="alert alert-info" v-if="items == null || items.length == 0">{{config.topNoVotingTopics}}</p>

        <div class="list-group" v-if="items != null && items.length>0" >
           <span v-for="(item,index) in items" :key="item.id">
                <editlinecomponent
                    v-bind:first="isFirst(index)"
                    v-bind:btn-group1="[
                        {
                            icon: 'edit',
                            btnClass: 'btn-light',
                            btnAltText: 'Edit',
                            url: '<?= Url::toRoute(['voting/edit-topic']) ?>' + '/' + item.id
                        },
                        {
                            icon: 'delete',
                            btnClass: 'btn-danger',
                            btnAltText: 'Löschen',
                            confirm: 'Sind Sie sicher, dass Sie die Umfrage \'' + item.headline + '\' löschen möchten?',
                            url: '<?= Url::toRoute(['voting/delete-topic']) ?>' + '/' + item.id
                        },
                        {
                            icon: 'arrow_upward',
                            btnClass: 'btn-light',
                            btnAltText: 'Hoch sortieren',
                            url: '<?= Url::toRoute(['voting/sort-up-topic']) ?>' + '/' + item.id
                        }
                   ]"
                   v-bind:releasestatus="getReleasestatusOfTopic(item)"
                   >
                </editlinecomponent>
                <a v-bind:href="config.showTopic + item.id" class="list-group-item list-group-item-action flex-column align-items-start">
                   <div class="d-flex w-100 justify-content-between">
                      <h5 class="mb-1">{{item.headline}}</h5>
                   </div>
                 <p class="mb-1">{{item.description}}</p>
                </a>
           </span>
        </div>

        <br/><br/>
                    
    </div>

    <script type="text/javascript">    
    new Vue({
        el: '#listOfVotings',
        components: {
            editlinecomponent
        },
        data: {
            config: {
                topNoVotingTopics: 'Zur Zeit sind keine Abstimmungen aktiv.',
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                getLink: '/voting/get-all-topics/',
                showTopic: '<?= Url::toRoute(['voting/edit-topic-votings']).'/' ?>'
            },
            items: [
            ]
        },
        mounted: function() {
            //alle 10sec neuladen
            this.onLoad();
        },
        // define methods under the `methods` object
        methods: {
            onLoad() {
                //alle 10s aktualisieren
               this.getItems();
               setInterval(this.getItems, 10000);
            },
            getItems() {
                //item laden
                self = this;
                $.post(self.config.getLink,
                {
                })
                .done(function(data) {
                    self.items = data
                    self.config.topErrorLoading = false
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error loading item" )
                });
            },
            editItem(item){
                console.log("edit")
            },
            deleteItem(item){
                console.log("delete")
            },
            sortUpItem(item){
                console.log("sortUp")
            },
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
            },            
        }
    });
        
    </script>
    
