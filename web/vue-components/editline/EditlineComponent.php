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
                <div class="edit-line" v-bind:class="{'releasestatus-first': releasestatusFirst}">
                    <span class="btn-group-1" v-if="btnGroup1">
                        <span v-for="(button,index) in btnGroup1">
                            <!-- Edit-Button -->
                            <a v-bind:href="button.url" class="btn" v-bind:class="[{ 'isSortButton': (button.icon === 'arrow_upward' && first)} , button.btnClass]"  v-bind:alt="button.btnAltText" v-if="!button.confirm">
                                <span class="material-icons">{{button.icon}}</span>
                            </a>
                            <!-- Confirm-Button -->
                            <a v-bind:href="button.url" class="btn btn-danger" v-bind:alt="button.btnAltText" v-if="button.confirm" v-bind:data-confirm="button.confirm">
                                <span class="material-icons">{{button.icon}}</span>
                            </a>
                        </span>
                    </span>
                    <span class="btn-group-2" v-if="btnGroup2">
                        <span v-for="button in btnGroup2">
                            <!-- Edit-Button -->
                            <a v-bind:href="button.url" class="btn" v-bind:class="button.btnClass" v-bind:alt="button.btnAltText" v-if="!button.confirm">
                                <span class="material-icons">{{button.icon}}</span>
                            </a>
                            <!-- Confirm-Button -->
                            <a v-bind:href="button.url" class="btn btn-danger" v-bind:alt="button.btnAltText" v-if="button.confirm" v-bind:data-confirm="button.confirm">
                                <span class="material-icons">{{button.icon}}</span>
                            </a>
                        </span>                        
                    </span>
                    
                    <span v-if="releasestatus">
                        <!-- default: notreleased -->
                        <div class="release-icons right" v-bind:class="releasestatus">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </span>

                </div>
                <!-- -->
    
</template>

<script type="text/javascript">
var editlinecomponent = {
    template: '#editlinecomponent',
    props: {
        btnGroup1: Array,
        btnGroup2: Array,
        first: Boolean,
        releasestatus: String, //CssClass
        releasestatusFirst: Boolean
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
