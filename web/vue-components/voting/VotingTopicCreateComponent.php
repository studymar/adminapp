<?php
use yii\helpers\Url;

/**
 * Liste der Veranstaltungen
 */

/* vue laden */
use app\assets\FormAsset;
FormAsset::register($this);
use app\assets\VueAsset;
VueAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir_tc = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir_tc = "vue-components/content/";

include_once($env_vuecomponent_dir_tc.'TextfieldComponent.php');

?>

<template id="voting-topic-create-component">

        <div>
            <div class="d-flex justify-content-between title-block">
                <h3 class="fs-5">Veranstaltung anlegen</h3>
            </div>

            <p class="alert alert-danger" v-if="config.saveError">{{config.saveErrorMessage}}</p>

            <validation-observer v-slot="{ handleSubmit }">
            <form v-on:submit.prevent="handleSubmit(onSubmit)" method="POST">
                <textfieldcomponent
                    v-model="item.headline"
                    label="Name"
                    fieldname="VotingTopic[headline]"
                    v-bind:errors="errors"
                    v-bind:servererror="getErrormessage('headline')"
                    >
                </textfieldcomponent>
                
                <button type="submit" id="submit-button" class="btn btn-primary">Speichern</button>
                <a href="" v-on:click.prevent="$parent.closeCreate()" class="btn btn-outline-primary" id="cancel-button">Zurück</a>
                
            </form>
            </validation-observer>

        </div>
    
</template>

<script type="text/javascript">
Vue.component('validation-observer', VeeValidate.ValidationObserver);

var votingTopicCreateComponent = {
    template: '#voting-topic-create-component',
    props: ['topic'],
    components: {
        textfieldcomponent
    },
    data: function(){
        return {
            config: {
                saveLink: '<?= Url::toRoute(['voting/create-topic']).'/' ?>',
                saveError: false,
                saveErrormessage: 'Fehler: Veranstaltung konnte nicht gespeichert werden'
            },
            item: {
                headline: "",
                description: ""
            },
            errors: [],
            servererrors: []
            
        }
    },
    computed: {
    },
    mounted: function() {
    },
    methods: {
        getErrormessage(fieldname){
            if(this.servererrors && this.servererrors.hasOwnProperty(fieldname))
                return this.servererrors[fieldname];
            else false;
        },
        onSubmit(){
            self = this
            $.post(self.config.saveLink,
            {
                "Votingtopic[headline]": this.item.headline
            })
            .done(function(data) {
                if(!data.saved){
                    //error
                    self.saveError = true
                    self.errorMessages = data.errormessages
                }
                else {
                    //success
                    self.saveError      = false
                    self.servererrors   = []
                    self.$parent.$refs.votingTopicList.getItems()
                    self.$parent.closeCreate()
                }
            })
            .fail(function() {
              console.log( "error saving item" )
            });
            
        }
    }
}

</script>
