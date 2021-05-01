<?php

/* 
 * Startseite der Votings für EDIT
 * @params $allRoles
 * @params $model
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\assets\VueAsset;
VueAsset::register($this);

use yii\bootstrap\ActiveForm;
use app\models\helpers\FormHelper;
use app\assets\FormAsset;
FormAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/editline/';
else $env_vuecomponent_dir = "vue-components/editline/";

//include_once($env_vuecomponent_dir.'EditlineComponent.php');

?>

                <div class="card card-block sameheight-item" id="updaterights">
                    <div class="title-block">
                        <h3 class="title">Rechte des Users</h3>
                    </div>
                    <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>

                        <div class="form-group row has-error">
                            <label for="user-role_id" class="col-sm-3 form-control-label">Rolle</label>
                            <div class="col-sm-9">
                                <?= Html::activeDropDownList($model,'role_id',$allRoles, ['prompt'=>'Bitte auswählen','class'=>'form-control form-control-sm','id'=>'role_id']) ?>
                                <?php if($model->hasErrors('role_id')){ ?><span class="has-error"><?= $model->getErrors('role_id')[0] ?></span><?php } ?>
                            </div>
                        </div>

                        Die Rolle bedeutet folgende Rechte für den User:

                        <br/>
                        
                        <div class="form-group" v-for="group in allRightGroups">
                            <label class="control-label">{{group.name}}</label>
                            <div v-for="item in group.rights">
                                <label>
                                    <input class="checkbox" name="RoleHasRights[]" type='checkbox' v-model="rights" v-bind:value="item.id" >
                                    <span>{{item.name}}</span>
                                </label>
                            </div>
                            
                        </div>


                        <?= Html::submitButton("Speichern",['class'=>'btn btn-primary']) ?>
                        <?= Html::a('Zurück zur Übersicht',['usermanager/index'],['class'=>'btn btn-outline-primary']) ?>
                    <?php ActiveForm::end() ?>

                </div>


    <script type="text/javascript">    
    new Vue({
        el: '#updaterights',
        components: {
        },
        data: {
            config: {
                topErrorLoading: false,
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                getRights: '/rolemanager/get-rights-of-role/'
            },
            allRightGroups: [
            ],
            rights: [],
            role_id: <?= $model->role->id ?>
        },
        mounted: function() {
            this.onLoad();
        },
        // define methods under the `methods` object
        methods: {
            onLoad() {
               this.getRights(this.role_id)
               self = this
               $("#role_id").change(function(){self.getRights($("#role_id :selected").val())})
            },
            getRights(roleId) {
                //item laden
                self = this;
                $.get(self.config.getRights + roleId
                )
                .done(function(data) {
                    self.allRightGroups     = data.allRightGroups
                    self.rights             = data.rights
                    self.config.topErrorLoading = false
                })
                .fail(function() {
                    self.config.topErrorLoading = true
                    console.log( "error loading item" )
                });
            }
        }
    });
        
    </script>
    
