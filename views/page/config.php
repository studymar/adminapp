<?php

/* 
 * Formular zum Konfigurieren der Seite
 * @param Object $model
 */
use yii\helpers\Html;
use app\models\user\User;
use app\models\role\Right;

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
                    <validation-observer v-slot="{ handleSubmit }">
                    <form v-on:submit.prevent="handleSubmit(onSubmit)" method="POST" class="">
                        <p class="alert alert-danger" v-if="form.topError">{{form.topErrorMessage}}</p>
                        
                        <div class="row required">
                            <label class="control-label col-sm-3" for="page-headline">Headline</label>
                            <div class="col-sm-9">
                                <validation-provider rules="required" v-slot="{ errors }">
                                <input type="text" id="page-headline" class="form-control" name="Headline" v-model="item.headline" aria-required="true">
                                <span class="invalid">{{ errors[0] }}</span>
                                </validation-provider>
                            </div>
                        </div>
                        <div class="row required">
                            <label class="control-label col-sm-3" for="page-urlname">Urlname</label>
                            <div class="col-sm-9">
                                <validation-provider rules="required|alpha_dash" v-slot="{ errors }">
                                <input type="text" id="page-urlname" class="form-control" name="Urlname" v-model="item.urlname" aria-required="true">
                                <span class="invalid">{{ errors[0] }}</span>
                                </validation-provider>
                            </div>
                        </div> 
                        <div class="row required">
                            <label class="control-label col-sm-3" for="page-linkname">Linkname</label>
                            <div class="col-sm-9">
                                <validation-provider rules="required" v-slot="{ errors }">
                                <input type="text" id="page-linkname" class="form-control" name="Linkname" v-model="item.linkname" aria-required="true">
                                <span class="invalid">{{ errors[0] }}</span>
                                </validation-provider>
                            </div>
                        </div>
                        <?php if(User::checkRight(Right::PAGE_ADMIN_ASADMIN)){ ?>
                        <div class="row required">
                            <label class="control-label col-sm-3" for="page-onlyadmin">Einstellungen zur Seite</label>
                            <div class="col-sm-9">
                                <div>
                                    <label>
                                        <input type="checkbox" id="page-details_admin_only" class="checkbox" name="details_admin_only" v-model="item.details_admin_only" aria-required="true">
                                        <span>Seite Konfigurieren nur mit Admin-Rechten</span>
                                    </label>
                                    <p class="help-block help-block-error" v-if="form.errorMessages && form.errorMessages.item">{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.details_admin_only[0]:''}}</p>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" id="page-content_admin_only" class="checkbox" name="content_admin_only" v-model="item.content_admin_only" aria-required="true">
                                        <span>Content ändern nur mit Admin-Rechten</span>
                                    </label>
                                    <p class="help-block help-block-error" v-if="form.errorMessages && form.errorMessages.item">{{(form.errorMessages && form.errorMessages.item)?form.errorMessages.item.content_admin_only[0]:''}}</p>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        
                        <button type="submit" id="submit-button" class="btn btn-primary">Speichern</button>
                        <?= Html::a('Zurück',['page/edit','p'=>$model->urlname],['class'=>'btn btn-outline-primary','id'=>'cancel-button']) ?>
                        
                    </form>
                    </validation-observer>
                    
                    
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">

    new Vue({
        el: '#pageconfig',
        components: {
        },
        data: {
            form: {
                topError: false,
                topErrorMessage: 'Daten konnten nicht gespeichert werden',
                errorMessages: {},
                saveLink: '/page/config/',
                backLink: '/page/edit/'
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
            },
            onSubmit: function (event) {
                //console.log('submit')
                self = this
                
                $.post(self.form.saveLink + '<?= $model->urlname ?>',
                {
                    "Page[headline]": self.item.headline,
                    "Page[urlname]": self.item.urlname,
                    "Page[linkname]": self.item.linkname,
                    "Page[details_admin_only]": self.item.details_admin_only,
                    "Page[content_admin_only]": self.item.content_admin_only
                })
                .done(function(data) {
                    //console.log(data)
                    if(data.saved == true){
                        //erfolgreich
                        document.location.href = self.form.backLink + self.item.urlname
                    }
                    else {
                        //Fehler
                        console.log('Fehler beim Speichern der Daten')
                        self.form.topError = true
                    }
                })
                .fail(function() {
                    self.form.topError = true
                    console.log( "error saving item" )
                });
                
                return false
            },
            closePagemanager(){
            }
        }
    });
        
    </script>
    
