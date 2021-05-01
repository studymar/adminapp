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

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/publicContent/';
else $env_vuecomponent_dir = "vue-components/publicContent/";

//include_once($env_vuecomponent_dir.'TeaserlistSortComponent.php');

?>

<template id="documentlist-public-component">

    <section class="document-template">
        <div class="document-manager" v-for="document in item.documents" :key="document.id">

            <div class="">
                <div class="docItem" id="document-1">
                    <div class="list column">
                        <a v-bind:href="'/content/documents/up/' + document.filename" class="icon" v-bind:class="document.extensionname"></a>
                        <div>
                            <a v-bind:href="'/content/documents/up/' + document.filename" target="_blank">{{document.name}}</a>
                            <div class="infos"><span class="size">{{document.size}} kb</span></div>
                        </div>
                    </div>    
                </div>
            </div>

        </div>
    </section>

</template>

<script type="text/javascript">

var documentlistPublicComponent = {
    template: '#documentlist-public-component',
    props: ['item'],
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

