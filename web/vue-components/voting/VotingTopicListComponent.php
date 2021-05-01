<?php
use yii\helpers\Url;

/**
 * Liste der Veranstaltungen
 */

/* vue laden */
use app\assets\VueAsset;
VueAsset::register($this);

?>

<template id="votingtopiclist-component">
    
                <div>
                    <div class="d-flex justify-content-between title-block mb-1">
                        <h3 class="fs-6 m-1 onlySmall">Bitte wählen sie eine Veranstaltung aus:</h3>
                        <h3 class="fs-6 m-1 notOnSmall">Veranstaltungen:</h3>
                        <br/>
                        <div id="newVotingtopicButton">
                            <a v-on:click.prevent="create()" class="btn single btn-light" role="button" title="Veranstaltung anlegen"><i class="material-icons">add</i></a>
                        </div>
                    </div>
                    
                    
                    <div v-for="(item,index) in items" :key="item.id" class="card" v-bind:class="{ 'active': topic!=null && item.id==topic.id}">
                        <div class="card-body">
                            <h5 class="card-title fs-6">{{item.headline}}</h5>
                            <a href="" v-on:click.prevent="$parent.getTopic(item)" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
    
</template>

<script type="text/javascript">

var votingTopicListComponent = {
    template: '#votingtopiclist-component',
    props: ['topic'],
    components: {
    },
    data: function(){
        return {
            config: {
                topNoVotingTopics: 'Zur Zeit sind keine Abstimmungen aktiv.',
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                getLink: '/voting/get-all-topics/',
                getTopic: '<?= Url::toRoute(['voting/edit-topic-votings']).'/' ?>'
            },
            items: [
            ]
        }
    },
    mounted: function() {
        this.getItems()
    },
    methods: {
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
        create(){
            this.$parent.openCreate()
        }
    }
}

</script>
