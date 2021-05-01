<?php

/* 
 * Liste
 * @param Object $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\helpers\FormHelper;
use yii\widgets\ListView;
use yii\grid\GridView;

use app\assets\FormAsset;
FormAsset::register($this);
use app\assets\TableAsset;
TableAsset::register($this);

?>
    <h3>Seitenauswahl</h3>
    <div class="list" id="pagemanager-list">
    <?php $form = ActiveForm::begin(FormHelper::getConfigArray('page-select-form',false));?>
        <?= $form->field($filterform, 'searchstring',['inputOptions'=>['id'=>'searchstringInput']]) ?>
        <a href="#" class="btn btn-primary ajax-load-pageselect" data-url="<?= Url::toRoute(['pageselect/index']) ?>">Suchen</a>


    <?php ActiveForm::end() ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_pages',
        'viewParams' => [
            'fullView' => true,
            'context' => 'main-page',
        ],
    ]);
    ?>
    </div>

    <script type="text/javascript">
        /* Neuladen durch abschicken des Filters per Submit-Button */
        $('.ajax-load-pageselect').on('click', function(event){
            loadToContent2(this.dataset.url,{
                'PageFilterForm[searchstring]': $('#searchstringInput').val()
            });
            event.preventDefault();
            return false;
        });
        /* Neuladen durch abschicken des Filters per Enter */
        $('#page-select-form').on('submit', function(event){
            loadToContent2($('#page-select-form a').attr('data-url'),{
                'PageFilterForm[searchstring]': $('#searchstringInput').val()
            });
            event.preventDefault();
            return false;
        });
        /* Neuladen durch Klick auf Pagination */
        $('.pagination a').on('click', function(event){
            loadToContent2($('#page-select-form a').attr('data-url') + '?page=' + $(this).html(),{
                'PageFilterForm[searchstring]': $('#searchstringInput').val()
            });
            event.preventDefault();
            return false;
        });
        /* Auswahl einer Seite, öffnet wieder Content1 und trägt ausgewählte ID in Hidden-Feld ein
         * Speichern muss dann auf Zielseite erst geschehen
         */
        $('.saveLink').on('click', function(event){
            $('#after-select-link').attr('href',this.href);
            $('#after-select-link').html(this.dataset.headline);
            $('#LinkItemInput').val(this.dataset.id);
            $('.radios').hide();
            $('.after-select').show();
            showContent1();
            event.preventDefault();
            return false;
        });        
    </script>
