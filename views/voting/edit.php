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
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/voting/';
else $env_vuecomponent_dir = "vue-components/voting/";

include_once($env_vuecomponent_dir.'VotingTopicListComponent.php');
include_once($env_vuecomponent_dir.'VotingTopicComponent.php');
include_once($env_vuecomponent_dir.'VotingbarComponent.php');
include_once($env_vuecomponent_dir.'VotingTopicConfigComponent.php');
include_once($env_vuecomponent_dir.'VotingTopicCreateComponent.php');
include_once($env_vuecomponent_dir.'VotingquestionCreateComponent.php');
include_once($env_vuecomponent_dir.'VotingquestionEditComponent.php');
include_once($env_vuecomponent_dir.'VotingquestionResultComponent.php');

?>

    <div class="" id="voting">
        <div class="d-flex justify-content-between title-block">
            <h2>Votings</h2>
            <br/>
            <div id="pagebar-open-btn">
                <a class="btn single btn-light"><i class="material-icons">settings</i></a>
            </div>
        </div>
        <div class="content-block">
        
            <votingbar-component
                v-bind:topic="topic"
                >
            </votingbar-component>
            
            <div class="col-sm-12 h-100 p-0 row">
                <div class="col-sm-4 voting-topic-list" v-bind:class="{ 'hiddenOnSmall': view != null }" >
                    <voting-topic-list-component ref="votingTopicList"
                        v-bind:topic="topic"
                        >
                    </voting-topic-list-component>
                </div>
                <div class="col-sm-8" v-bind:class="{ 'hiddenOnSmall': view == null }" >
                    <p class="alert alert-light mt-1 notOnSmall" v-if="view == null && topic == null">Bitte wählen Sie eine Veranstaltung aus</p>
                    
                    <voting-topic-component
                        v-if="view == 'topic' && topic != null"
                        v-bind:topic="topic"
                        ref="topic"
                        >
                    </voting-topic-component>

                    <voting-topic-config-component
                        v-if="view == 'topic-config' && topic != null"
                        v-bind:topic="topic"
                        >
                    </voting-topic-config-component>
                    
                    <voting-topic-create-component
                        v-if="view == 'create'"
                        >
                    </voting-topic-create-component>
                    
                    <voting-question-create-component
                        v-if="view == 'question-create'"
                        v-bind:topic="topic"
                        >
                    </voting-question-create-component>
                    <voting-question-edit-component
                        ref="questionedit"
                        v-if="view == 'question-edit'"
                        v-bind:topic="topic"
                        v-bind:question="question"
                        >
                    </voting-question-edit-component>
                    
                    <voting-question-result-component
                        v-if="view == 'question-result'"
                        v-bind:topic="topic"
                        v-bind:question="question"
                        >
                    </voting-question-result-component>
                </div>
            </div>
        </div>
        <br/><br/>

    </div>

    <script type="text/javascript">    
    new Vue({
        el: '#voting',
        components: {
            votingTopicListComponent,
            votingTopicCreateComponent,
            votingTopicConfigComponent,
            votingTopicComponent,
            votingQuestionCreateComponent,
            votingQuestionEditComponent,
            votingQuestionResultComponent,
            votingbarComponent
        },
        data: {
            view: null,
            items: [
            ],
            topic: null,
            question: null
        },
        mounted: function() {
        },
        // define methods under the `methods` object
        methods: {
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
            /*
            isFirst(index){
                if(index == 0)
                    return true
                else return false
            },
            */
            getTopic(item) {
                this.topic = item
                this.question = null
                this.view = 'topic'
            },
            openCreate() {
                this.view = 'create'
            },
            openTopicConfiguration() {
                this.view = 'topic-config'
                //topic belassen
            },
            openTopicQuestionCreate() {
                this.view = 'question-create'
            },
            openTopicQuestionEdit(item) {
                this.question = item
                this.view = 'question-edit'
            },
            openTopicQuestionResult(item){
                this.question = item
                this.view = 'question-result'
                //topic belassen
            },
            closeTopicConfiguration() {
                this.view = 'topic'
                //topic belassen
            },
            closeTopicQuestionCreate() {
                this.view = 'topic'
                //topic belassen
            },
            closeTopicQuestionEdit() {
                this.question = null
                this.view = 'topic'
                //topic belassen
            },
            closeCreate() {
                this.view = null
                this.topic = null
            },
            closeTopic() {
                this.topic = null
                this.view = null
            },
            clear() {
                this.view = null
                this.topic = null
            }
            
        }
    });
        
    </script>
    
