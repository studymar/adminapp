

<template id="releasecomponent">
    
    <!-- Releasemanager -->
    <div class="release-manager" v-if="releaseItem != null">
        <div class="release-boxes">
            <div class="row field-release-is_released">
                <label class="control-label col-sm-3">Öffentlich sichtbar</label>
                <div class="col-sm-9">
                    <label>
                        <input class='radio squared' type='radio' name="Release[is_released]" value="1" v-model="releaseItem.is_released" >
                        <span>Ja</span>
                    </label>
                    <label>
                        <input class='radio squared' type='radio' name="Release[is_released]" value="0" v-model="releaseItem.is_released" >
                        <span>Nein</span>
                    </label>
                </div>
                
            </div>
        </div>
        <div class="release-dates-opener-line">
            <a data-toggle="collapse" href="#release-dates">>> bei bedarf Veröffentlichungszeitraum eintragen</a>
        </div>
        <div class="release-dates collapse" v-bind:class="{show: releaseItem.from_date}" id="release-dates">
            <div class="row field-release-from_date">
                <label class="control-label col-sm-3" for="release-from_date">Sichtbar ab</label>
                <div class="col-sm-3">
                    <flat-pickr v-model="releaseItem.from_date" :config="config" class="form-control" placeholder="Select a date" name="releaseItem.from_date"></flat-pickr>
                    <!--<input type="text" id="release-from_date" class="form-control datepicker flatpickr-input" name="Release[from_date]" v-model="releaseItem.from_date" >-->
                    <!-- data-provide="datepicker" -->
                    <p class="help-block help-block-error "></p>
                </div>
            </div>
            <div class="row field-release-to_date">
                <label class="control-label col-sm-3" for="release-to_date">Sichtbar bis</label>
                <div class="col-sm-3">
                    <flat-pickr v-model="releaseItem.to_date" :config="config" class="form-control"  placeholder="Select a date" name="releaseItem.to_date"></flat-pickr>
                    <!--<input type="text" id="release-to_date" class="form-control datepicker flatpickr-input" name="Release[to_date]" v-model="releaseItem.to_date" readonly="readonly">-->
                    <p class="help-block help-block-error "></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
var releasecomponent = {
    template: '#releasecomponent',
    props: ['releaseItem'],
    components: {
        
    },
    data: function(){
        return {
            release: {
                from_date: '' //default-Wert setzen
            },
            config: {
                altFormat: "d.m.Y", //"d.m.Y" Anzeigeformat - unabhängig vom Format des Feldes
                dateFormat: "d.m.Y", //"Y-m-d"Format für Variable (so wird es gespeichert)
                altInput: false,
                weekNumbers: true,
                allowInput: true //manuelle Eingabe?
            }
        }
    },
    mounted: function() {
        this.onLoad()
    },
    methods: {
        onLoad() {
        },
        getDate: function (event) {
            this.$emit('changed')
        }
    }
}

</script>
