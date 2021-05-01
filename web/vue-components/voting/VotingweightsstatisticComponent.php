<?php
/**
 * Shows an Votingweight Statistic
 */

use yii\helpers\Url;

?>
<template id="votingweightsstatisticcomponent">

        <div class="list-group">
            <!-- Header -->
            <div class="d-flex w-100 justify-content-between header">
                <h5 class="mb-1">Gewichtung:</h5>
            </div>

            <!-- Items -->
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th>Verein</th>
                        <th>Anwesend</th>
                        <th>Stimmen</th>
                        <th>Abgestimmt</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in votingweightsstatistic" :key="item.id">
                        <td>{{item.name}}</td>
                        <td>{{ (item.active==1)?'Ja':'Nein'}}</td>
                        <td>{{item.stimmen}}</td>
                        <td>{{(item.votinganswers.length)?'Ja':'Nein'}}</td>
                        <td><a v-if="item.votinganswers.length > 0" v-bind:href="'<?= Url::toRoute(['voting/delete-votinganswer','p'=>$model->id]).'/' ?>' + item.id" v-bind:data-confirm="'Sind Sie sicher, dass die Antwort von '+  item.name +' gelöscht werden soll?'"><span class="material-icons">delete</span></a></td>
                    </tr>
                    </tbody>
                    <tfoot class="thead-light">
                    <tr>
                        <th>Summe</td>
                        <th>{{sumAnwesend}} / {{votingweightsstatistic.length}}</th>
                        <th>{{sumStimmen}} / {{sumStimmenGesamt}}</th>
                        <th>{{sumAbgestimmt}}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            
        <div>
        <!-- -->

    
</template>

<script type="text/javascript">
var votingweightsstatisticcomponent = {
    template: '#votingweightsstatisticcomponent',
    props: {
        votingweightsstatistic: Array,
        resultstatistics: Array,
        sumStimmen: String,
        sumAbgestimmt: String     
    },
    components: {
    },
    data: function(){
        return {
            sumAnwesend: 0,
            sumStimmenGesamt: 0
        }
    },
    mounted: function() {
        setTimeout(this.calculateSums, 500) //erstes laden
        //setInterval(this.calculateSums, 5000) //dann regelmäßig, wegen änderungen
    },
    methods: {
        calculateSums(){
            self = this
            self.sumAnwesend = 0,
            self.sumStimmenGesamt = 0,
            self.votingweightsstatistic.forEach(function(item,index) {
                if(item.active == 1){
                    self.sumAnwesend++
                }
                self.sumStimmenGesamt+= parseInt(item.stimmen)
            })
        }
    }
}

</script>
