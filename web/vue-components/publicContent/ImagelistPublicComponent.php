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

<template id="imagelist-public-component">

    <section class="image-template container">
        <div class="d-flex flex-row flex-wrap align-self-stretch align-items-stretch align-content-stretch gallery" v-if="item.uploadedimages.length > 1">
            <div class="flex-fill flex-wrap gallery-item img-thumbnail" v-for="image in item.uploadedimages" :key="image.id">
                <img v-bind:src="'/content/images/up/' + image.filename" class="" alt="">
            </div>
        </div>
        <img v-bind:src="'/content/images/up/' + item.uploadedimages[0].filename" v-if="item.uploadedimages.length == 1" class="img-fluid" alt="">
    </section>

</template>

<script type="text/javascript">

var imagelistPublicComponent = {
    template: '#imagelist-public-component',
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

