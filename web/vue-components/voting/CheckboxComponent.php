<?php
/**
 * Shows an Voting Component
 * ggf. mit multi = true aufrufen
 */


?>
<template id="checkboxcomponent">

                <!-- Auswahl mehrerer Ergebnisse Checkboxes ausgewählt -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                      <div class="input-group-text" v-bind:class="{selected: $parent.myanswers.length>0 && $parent.answer.includes(item.value)}">
                        <input type="checkbox" v-bind:aria-label="item.value" v-model="$parent.answer" v-bind:value="item.value" name="voteanswer[]" v-bind:disabled="$parent.myanswers.length>0">
                    </div>
                  </div>
                    <div class="option form-control" v-bind:class="[{disabled: $parent.myanswers.length>0},{selected: $parent.myanswers.length>0 && $parent.answer.includes(item.value)}]">{{item.value}}</div>
                </div>
                <!-- -->
    
</template>

<script type="text/javascript">
var checkboxcomponent = {
    template: '#checkboxcomponent',
    props: {
        item: Object
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
        }
    }
}

</script>
