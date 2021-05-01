<?php
use yii\helpers\Url;
/**
 * Shows an Editline
 * Props:
 * @param array $buttons
 * @param string $buttons[url] Url des Buttons
 * @param string $buttons[name] Bezeichnung des Buttons
 * @param boolean $buttons[confirm] default true 
 * @param string $buttons[class] CSS-Class default= btn-light
 * @param string $releasestatus [released,notreleased,expired,waiting] default= inactive
 */


?>
<template id="editlinecomponent">

                <!-- Editline -->
                <div class="edit-line align-left">
                    <span v-for="button in buttons">
                        <!-- Edit-Button -->
                        <a v-bind:href="button.url + '/' + item.id" class="btn btn-light" v-if="button.edit" valt="Edit">
                            <span class="material-icons">edit</span>
                        </a>
                        <!-- Delete-Button -->
                        <a v-bind:href="button.url + '/' + item.id" class="btn btn-danger" v-if="button.delete" alt="Löschen" v-bind:data-confirm="'Sind Sie sicher, dass Sie die Umfrage ' + item.headline + ' löschen möchten?'">
                            <span class="material-icons">delete</span>
                        </a>
                        <!-- Sort-Button -->
                        <a v-bind:href="'<?= Url::toRoute(['voting/sort-up-topic']) ?>'+'/'+item.id" class="btn btn-light" v-if="button.sort && !first" alt="SortUp">
                            <span class="material-icons">arrow_upward</span>
                        </a>
                    </span>
                    <span v-if="releaseicons">
                        <!-- default: notreleased -->
                        <div class="release-icons right" v-bind:class="releasestatus">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </span>
                    <span>
                        <!-- beliebiges icon -->
                        <a v-bind:href="extraiconUrl" class="btn extra-icons right" v-bind:class="extraiconBtnClass">
                            <span class="material-icons">insert_chart_outlined</span>
                        </a>
                    </span>
                </div>
                <!-- -->
    
</template>

<script type="text/javascript">
var editlinecomponent = {
    template: '#editlinecomponent',
    props: {
        buttons: Array,
        first: Boolean,
        item: Object,
        releaseicons: Boolean,
        releasestatus: String, //CssClass
        extraiconBtnClass: String,
        extraiconUrl: String
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
        onClick(){
            
        }
    }
}

</script>
