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

?>

<template id="votingbar-component">
    
            <div class="bar h-100 collapse" id="voting-topic-overlay" v-if="topic">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0" aria-label="Close" data-bs-toggle="collapse" data-bs-target="#voting-topic-overlay"></button>
                <div class="d-flex justify-content-between title-block">
                    <h2 class="fs-5">{{topic.headline}}</h2>
                </div>
                <ul>
                    <li class="">
                        <a href="" v-on:click.prevent="$parent.openTopicConfiguration()" data-toggle="collapse" data-target="#voting-topic-overlay">
                            <i class="fa fa-edit"></i> Veranstaltung konfigurieren 
                        </a>
                    </li>
                    <li class="">
                        <a href="" data-toggle="collapse" data-target="#topic-delete-confirmation">
                            <i class="material-icons">delete</i> Veranstaltung löschen 
                        </a>
                        <ul id="topic-delete-confirmation" class="collapse">
                            <li class="shortdescription">
                                <p class="description">
                                    "{{topic.headline}}"
                                    <br/>
                                    <span class="font-italic">Wirklich löschen?</span>
                                </p>
                                <ul>
                                    <li class="border confirmation-button mb-1">
                                        <a href="" v-on:click.prevent="deleteTopic()">Ja</a>
                                    </li>
                                    <li class="border confirmation-button">
                                        <a href="#" data-toggle="collapse" data-target="#topic-delete-confirmation">Abbrechen</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="" v-on:click="$parent.openTopicQuestionCreate()" data-toggle="collapse" data-target="#voting-topic-overlay">
                            <i class="material-icons">create</i> Einzelne Abstimmung hinzufügen
                        </a>
                    </li>
                </ul>
            </div>
    
</template>

<script type="text/javascript">

var votingbarComponent = {
    template: '#votingbar-component',
    props: ['topic'],
    components: {
    },
    data: function(){
        return {
            config: {
                deleteLink: '<?= Url::toRoute('voting/delete-topic').'/' ?>',
                deleteError: false,
                deleteErrorMessage: ''
            }
        }
    },
    mounted: function() {
    },
    methods: {
        deleteTopic() {
            self = this
            $.post(self.config.deleteLink + self.topic.id,
            {
            })
            .done(function(data) {
                if(!data.deleted){
                    //error
                    self.deleteError = true
                }
                else {
                    //success
                    self.deleteError = false
                    self.$parent.$refs.votingTopicList.getItems()
                    self.$parent.clear()
                }
            })
            .fail(function() {
              console.log( "error saving item" )
            });
            
        }
    }
}

</script>
