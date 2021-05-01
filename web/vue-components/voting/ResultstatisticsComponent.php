<?php
/**
 * Shows an Voting Component
 */


?>
<template id="resultstatisticscomponent">

                <!-- Statistik Ergebnisse -->
                <div class="resultlist">
                    <br>
                    <h5>Ergebnisse</h5>
                    <div class="input-group mb-3" v-for="result in resultstatistics">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                              {{result.percent}}% / {{result.anz}} Stimme
                          </div>
                        </div>
                        <div class="option form-control">{{result.value}}</div>
                    </div>
                    <!-- Summenzeile -->
                    <br>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                              100% / {{sumValues}} Stimme{{(sumValues>1)?'n':''}}
                          </div>
                        </div>
                        <div class="option form-control">Gesamt</div>
                    </div>
                </div>
                <!-- -->
    
</template>

<script type="text/javascript">
var resultstatisticscomponent = {
    template: '#resultstatisticscomponent',
    props: {
        item: Object,
        resultstatistics: Array,
        sumValues: String
    },
    components: {
    },
    data: function(){
        return {
        }
    },
    mounted: function() {
        this.onLoad()
    },
    methods: {
        onLoad() {
            self = this
        }
    }
}

</script>
