<?php
/**
 * Shows an Voting Component
 * ggf. mit multi = true aufrufen
 */


?>
<template id="chooseweightingusercomponent">

        <!-- Auswahl eines Users für die Gewichtung -->
        <div class="list-group">
            <!-- Header -->
            <div class="d-flex w-100 justify-content-between header">
                <h4 class="mb-1">Für wen wollen Sie Ihre Stimme abgeben?</h4>
            </div>

            <p class="alert alert-danger" v-if="config.topErrorLoading">{{config.topErrorLoadingMessage}}</p>

            <form v-on:submit.prevent="setUser">
                <!-- Items -->
                <div class="input-group mb-3" v-for="item in topic.votingweights" :key="item.id">
                    <div class="input-group-text">
                        <input type="radio" v-bind:aria-label="item.name" v-model="selecteduser" v-bind:value="item.id" name="votingweights[]">
                    </div>
                    <div class="option form-control" >{{item.name}}</div>
                </div>

                <button type="submit" class="btn btn-primary" v-bind:class="{disabled: !selecteduser}">Abstimmen</button>
            </form>
            
        <div>
        <!-- -->

    
</template>

<script type="text/javascript">
var chooseweightingusercomponent = {
    template: '#chooseweightingusercomponent',
    props: {
        topic: Object,
        weightuser: Object
    },
    components: {
    },
    data: function(){
        return {
            selecteduser: null,
            config: {
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht ausgewählt werden',
                getLink: '/voting/get-votingweights/'
            }
        }
    },
    mounted: function() {
    },
    methods: {
            setUser() {
                self = this;
                $.post(self.config.getLink + self.topic.id + '/' + self.selecteduser,
                {
                })
                .done(function(data) {
                    self.$parent.weightuser = data.votingweights

                    self.config.topErrorLoading = false
                    self.$parent.getVoting()
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error loading user" )
                });
            }
    }
}

</script>
