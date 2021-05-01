<?php

/* 
 * Formular zum Sortieren der Templates
 * @param Page $page
 * @param [] $pageHasTemplates
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;
use app\models\content\LinkItem;
use app\models\content\DocumentList;

use app\assets\VueAsset;
VueAsset::register($this);
use app\assets\VueSortableAsset;
VueSortableAsset::register($this);

?>

        <section class="form" id="SortTemplates">
            <div class="d-flex justify-content-between title-block">
                <h2>Templates - Inhalte löschen</h2>
            </div>
            <div class="content-block">
                <form v-on:submit.prevent="onSubmit" method="POST">
                    <div class="section-headline">
                        Bitte sortieren Sie die Inhalte in die gewünschte Reihenfolge:
                    </div>

                    <draggable v-model="pageHasTemplates">
                        <transition-group>
                            <div v-for="elem in pageHasTemplates" :key="elem.id" class="card card-selection">
                                <div class="card-body">
                                    <div class="">
                                        <label>
                                            <span class="fa fa-arrows-alt"></span>
                                            <span>{{elem.template.type}}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </transition-group>
                    </draggable>
                    
                    <div class="text-danger" v-if="form.topError">{{form.topErrorMessage}}</div>
                    <button type="submit" class="btn btn-primary">Speichern</button>
                    <a href="<?= Url::toRoute(['page/edit','p'=>$page->urlname]) ?>" class="btn btn-outline-primary">Abbrechen</a>
                </form>
            </div>

        </section>



    <script type="text/javascript">
    Vue.component('draggable', vuedraggable);
    //Vue.use( draggable );
    
    new Vue({
        el: '#SortTemplates',
        components: {
        },
        data: {
            editLists: {
            },
            pageHasTemplates: <?= \yii\helpers\Json::encode($pageHasTemplates) ?>,
            errors: [],
            form: {
                isChanged: false,
                topError: false,
                topErrorLoading: false,
                topSuccess: false,
                topSuccessMessage: 'Gespeichert',
                topErrorMessage: 'Fehler: Daten konnten nicht gespeichert werden.',
                topErrorLoadingMessage: 'Daten konnten nicht geladen werden.',
                errorMessages: {},
                getLink: '',
                saveLink: "/page/save-sort-templates/<?= $page->urlname ?>",
                saveSuccessLink: '/page/edit/<?= $page->urlname ?>'
            }
        },
        mounted: function() {
            //this.onLoad()
        },
        // define methods under the `methods` object
        methods: {
            drag: function (){
                console.log('halloi')
            },
            onSubmit: function (event) {
                //console.log('submit')
                self = this;

                //change uploadedimages to array of ids
                var ids = [];
                this.pageHasTemplates.forEach(function(item){
                    ids.push(item.id)
                });

                $.post(self.form.saveLink,
                {
                    "SortPageHasTemplateForm[ids]": ids
                })
                .done(function(data) {
                    if(!data.saved){
                        //error
                        self.form.topSuccess = false
                        self.form.topError = true
                        self.form.errorMessages = data.errors
                    }
                    else {
                        //success
                        self.form.topSuccess = true
                        self.form.topError = false
                        self.form.errorMessages = null
                        document.location.href = self.form.saveSuccessLink
                    }
                    //UnSaved markierung entfernen
                    self.form.isChanged = false;                        
                })
                .fail(function() {
                  console.log( "error saving item" )
                });
            }
            
        }
    });
        
    </script>  


