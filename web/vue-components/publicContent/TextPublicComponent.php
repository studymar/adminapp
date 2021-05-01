<?php
use yii\helpers\Url;

/**
 * Shows on an editpage the text template
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

<template id="text-public-component" >
    <section class="text-template">
        <span v-if="item">
            <div class="text-headline" v-if="item.headline">
                <h3>{{item.headline}}</h3>
            </div>


            <div class="card mb-3">
              <div class="no-gutters">
                <div class="card-body">
                  <p class="card-text" v-html="item.text">
                  </p>
                </div>
              </div>
            </div>        
        </span>
    </section>
</template>

<script type="text/javascript">

var textPublicComponent = {
    template: '#text-public-component',
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
