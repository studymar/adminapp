<?php
/**
 * Shows an Voting Component
 */


?>
<template id="textcomponent">

                <!-- Freitexteingabe ausgewählt -->
                <div class="form-group">
                    <label for="voteanswer">Deine Eingabe:</label>
                    <input type="text" class="form-control saved" v-for="item in myanswers" :key="item.id" v-bind:value="item.value" disabled>
                    <input type="text" class="form-control" id="voteanswer" name="voteanswer[]" value="" v-model="$parent.answer" >
                </div>
                <!-- -->
    
</template>

<script type="text/javascript">
var textcomponent = {
    template: '#textcomponent',
    props: {
        item: Object,
        myanswers: Array
    },
    components: {
    },
    data: function(){
        return {
        }
    },
    mounted: function() {
    },
    methods: {
    }
}

</script>
