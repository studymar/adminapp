<?php
/* 
 * Liste der Seiteninhalte
 * @param Page $page
 */

use yii\helpers\Url;
use app\models\user\User;
use app\models\role\Right;
$this->params['relatedButtons'] = [];

/* Pagebar anzeigen, wenn Recht vorhanden */
use app\assets\PagebarAsset;
PagebarAsset::register($this);

//Pagemenü-HTML, wenn Recht dazu vorhanden auf Seite einbauen (wird in main-Layout includiert)
if(isset($page) && User::hasRightToOpenPageMenu($page)){
    $this->params['pagebar'] = true;
}

/* vue laden */
use app\assets\VueAsset;
VueAsset::register($this);

if(YII_ENV_TEST)
    $env_vuecomponent_dir = __DIR__.'/../../web/vue-components/publicContent/';
else $env_vuecomponent_dir = "vue-components/publicContent/";

/* vue components laden */
include_once($env_vuecomponent_dir.'TeaserlistPublicComponent.php');
include_once($env_vuecomponent_dir.'TextPublicComponent.php');
include_once($env_vuecomponent_dir.'ImagelistPublicComponent.php');
include_once($env_vuecomponent_dir.'DocumentlistPublicComponent.php');
?>

    <div class="page-edit">
        <div class="d-flex justify-content-between title-block">
            <h2>{{page.headline}}</h2>
            <br/>
            <?php if(User::hasRightToOpenPageMenu($page) ){ ?>
            <div id="pagebar-open-btn">
                <a class="btn single btn-light"><i class="material-icons">settings</i></a>
            </div>
            <?php } ?>
        </div>
        <div class="content-block">
            <!--
            <div class="template-headline">
                <a v-bind:href="links.editPageLink" class="btn btn-primary">Inhalte bearbeiten</a>
            </div>
            -->
            <div v-for="pageHasTemplate in pageHasTemplates" :key="pageHasTemplate.id">
                <imagelist-public-component
                    v-bind:item="pageHasTemplate.imageLists[0]"
                    v-if="pageHasTemplate.template.controllername == 'imagelist'"
                    >
                </imagelist-public-component>
                
                <documentlist-public-component
                    v-bind:item="pageHasTemplate.documentlists[0]"
                    v-if="pageHasTemplate.template.controllername == 'documentlist'"
                    >
                </documentlist-public-component>

                <teaserlist-public-component
                    v-bind:item="pageHasTemplate.teaserlists[0]"
                    v-if="pageHasTemplate.template.controllername == 'teaserlist'"
                    >
                </teaserlist-public-component>
                
                <text-public-component
                    v-bind:item="pageHasTemplate.texts[0]"
                    v-if="pageHasTemplate.template.controllername == 'text'"
                    >
                </text-public-component>
                
            </div>
        </div>

    </div>


    <script type="text/javascript">
    
    new Vue({
        el: '.page-edit',
        components: {
            teaserlistPublicComponent,
            textPublicComponent,
            imagelistPublicComponent,
            documentlistPublicComponent
        },
        data: {
            loading: {
                getTextLink: '<?= Url::toRoute(['text/get']) ?>/',
                getHasError: false,
                getErrorMessage: 'Daten konnten nicht geladen werden.'
            },
            links: {
                editPageLink: '<?= Url::toRoute(['page/edit-form','p'=>$page->urlname]) ?>'
            },
            page: {
                headline: '<?= $page->headline ?>',
                release: <?= json_encode($page->release->attributes) ?>
            },
            pageHasTemplates: <?= json_encode($pageHasTemplates) ?>,
            errors: []
        },
        mounted: function() {
            //this.getItems()
        },
        // define methods under the `methods` object
        methods: {
        }
    });
        
    </script>
    
