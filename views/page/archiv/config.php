<?php

/* 
 * Formular zum Konfigurieren der Seite
 * @param Object $model
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;
use app\models\content\LinkItem;
use app\models\content\DocumentList;

use app\assets\FormAsset;
FormAsset::register($this);
use app\assets\VueAsset;
VueAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/content/';
else $env_vuecomponent_dir = "vue-components/content/";

?>

    <section class="section" id="pageconfig">
        <div class="row sameheight-container">
    
            <div class="col-md-12">
                <div class="title-block">
                    <h3 class="title">Seite konfigurieren</h3>
                </div>

                <div class="card card-block sameheight-item">                    
                    <form v-on:submit.prevent="onSubmit" method="POST" class="">
                        <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
                        
                        <div class="row required">
                            <label class="control-label col-sm-3" for="page-headline">Headline</label>
                            <div class="col-sm-9">
                                <input v-validate="'required'" type="text" id="page-headline" class="form-control" name="Headline" v-model="item.headline" aria-required="true">
                                <p class="help-block help-block-error " v-if="errors.has('Headline')">{{errors.first('Headline')}}</p>
                                <p class="help-block help-block-error " >{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.headline[0]:''}}</p>
                            </div>
                        </div>
                        <div class="row required">
                            <label class="control-label col-sm-3" for="page-urlname">Urlname</label>
                            <div class="col-sm-9">
                                <ValidationProvider v-slot="{ errors }">
                                <input v-validate="'required'" type="text" id="page-urlname" class="form-control" name="Urlname" v-model="item.urlname" aria-required="true">
                                <p class="help-block help-block-error " v-if="errors.has('Urlname')">{{errors.first('Urlname')}}</p>
                                <p class="help-block help-block-error " >{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.urlname[0]:''}}</p>
                                <div class="invalid-feedback">{{ errors[0] }}</div>
                                </ValidationProvider>
                            </div>
                        </div> 
                        <div class="row required">
                            <label class="control-label col-sm-3" for="page-linkname">Linkname</label>
                            <div class="col-sm-9">
                                <input v-validate="'required'" type="text" id="page-linkname" class="form-control" name="Linkname" v-model="item.linkname" aria-required="true">
                                <p class="help-block help-block-error " v-if="errors.has('Linkname')">{{errors.first('Linkname')}}</p>
                                <p class="help-block help-block-error " >{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.linkname[0]:''}}</p>
                            </div>
                        </div>
                        <div class="row required">
                            <label class="control-label col-sm-3" for="page-onlyadmin">Einstellungen zur Seite</label>
                            <div class="col-sm-9">
                                <div>
                                    <label>
                                        <input v-validate="" type="checkbox" id="page-details_admin_only" class="checkbox" name="details_admin_only" v-model="item.details_admin_only" aria-required="true">
                                        <span>Seite Konfigurieren nur mit Admin-Rechten</span>
                                    </label>
                                    <p class="help-block help-block-error" v-if="errors.has('details_admin_only')">{{errors.first('details_admin_only')}}</p>
                                    <p class="help-block help-block-error" v-if="form.errorMessages && form.errorMessages.item">{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.details_admin_only[0]:''}}</p>
                                </div>
                                <div>
                                    <label>
                                        <input v-validate="" type="checkbox" id="page-content_admin_only" class="checkbox" name="content_admin_only" v-model="item.content_admin_only" aria-required="true">
                                        <span>Content ändern nur mit Admin-Rechten</span>
                                    </label>
                                    <p class="help-block help-block-error " v-if="errors.has('content_admin_only')">{{errors.first('content_admin_only')}}</p>
                                    <p class="help-block help-block-error" v-if="form.errorMessages && form.errorMessages.item">{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.content_admin_only[0]:''}}</p>
                                </div>
                            </div>
                        </div>
                        
                        
                        <button type="submit" id="submit-button" class="btn btn-primary">Speichern</button>
                        <?= Html::a('Zurück',['page/edit','p'=>$model->urlname],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>
                        
                    </form>
                    
                    
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
    //Vue.component('flat-pickr', VueFlatpickr);
    //Vue.component('VeeValidate', VeeValidate);
    //Vue.use(VeeValidate);
    Vue.component('validation-provider', VeeValidate.ValidationProvider);

    new Vue({
        el: '#pageconfig',
        components: {
        },
        data: {
            form: {
                topError: false,
                topErrorMessage: 'Bitte überprüfen Sie Ihre Eingaben',
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                errorMessages: {},
                saveLink: '/teaserlist/save-item/'
            },
            item: {
                id: '<?= $model->id ?>',
                headline: '<?= $model->headline ?>',
                urlname: '<?= $model->urlname ?>',
                linkname: '<?= $model->linkname ?>',
                details_admin_only: '<?= $model->details_admin_only ?>',
                content_admin_only: '<?= $model->content_admin_only ?>'
            }
        },
        mounted: function() {
            this.onLoad()
        },
        // define methods under the `methods` object
        methods: {
            onLoad() {
                //vee-validate auf language de einstellen
                this.$validator.localize('de');
                //console.log('load')
            },
            onSubmit: function (event) {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        self = this;
                        console.log('validated');
                    }
                })
            },
            closePagemanager(){
            }
        }
    });
        
    </script>
    
