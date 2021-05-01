<?php

/* 
 * Liste
 * @param DataProvider $dataProvider
 * @param DocList $list
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
    <h3>Documentmanager</h3>
    <b>Bereits ausgewählt</b>
    <div class="list">
        <div class="row document-manager">
            <div class="col-sm documents selected">
                <div class="docItem demo">
                    <a href="" class="icon pdf" target="_blank"></a>
                    <div>
                        <a href="" class="openLink" target="_blank">Tolles Dokument</a>
                        <div class="infos">uidshjdnk-document.pdf (1024 kb)</div>
                        <div class="buttons">
                            <a href="" class="btn btn-primary selectLink" data-headline="hh" data-id="1">Auswählen</a>
                            <a href="" class="btn btn-danger removeLink">Entfernen</a>
                            <a href="" target="_blank" class="btn btn-light">Dokument anzeigen</a>
                        </div>
                    </div>
                </div>
                <!--
                <div class="docItem">
                    <a href="" class="icon pdf" target="_blank"></a>
                    <div>
                        <a href="" class="openLink" target="_blank">Tolles Dokument</a>
                        <div class="infos">uidshjdnk-document.pdf (1024 kb)</div>
                        <div class="buttons">
                            <a href="" class="btn btn-primary selectLink" data-headline="hh" data-id="1">Auswählen</a>
                            <a href="" class="btn btn-danger removeLink">Entfernen</a>
                            <a href="" target="_blank" class="btn btn-light">Dokument anzeigen</a>
                        </div>
                    </div>
                </div>
                -->
            </div>
        </div>
        <a href="" class="btn btn-primary saveLink">Übernehmen</a>
    </div>
    <b>Suche</b>
    <div class="list">
    <?php $form = ActiveForm::begin(FormHelper::getConfigArray('documentmanager-searchform',false));?>
        <?= $form->field($filterform, 'searchstring',['inputOptions'=>['id'=>'searchstringInput']]) ?>
        <a href="#" class="btn btn-primary ajax-load-search" data-url="<?= Url::toRoute(['documentmanager/index','p'=>$list->id]) ?>">Suchen</a>
    <?php ActiveForm::end() ?>
        
        <div class="row document-manager">
            <div class="col-sm documents selectable">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_documents',
                'itemOptions' => [
                    'tag'=>false,
                ],
                'viewParams' => [
                    'fullView' => true,
                ],
            ]);
            ?>
            </div>
        </div>
    </div>

    <?= Html::a('Zurück',[''],['class'=>'btn btn-outline-primary cancelLink']) ?>
    
    
    <script type="text/javascript">
        /* Bei hover über icon oder link auch den Button zum öffnen hovern,
         * um deutlich zu machen, welche Funktion dahinter steht 
         */
        $('.list .document-manager .docItem .icon, .list .document-manager .docItem .openLink').on('mouseenter mouseleave', function(event){
            $(this).parent().find('a.btn-light').toggleClass('openDocumentHoverByLink');
            return false;
        });
        
        /* Neuladen durch abschicken des Filters per Submit-Button */
        $('.ajax-load-search').on('click', function(event){
            loadToContent2(this.dataset.url,{
                'SearchFilterForm[searchstring]': $('#searchstringInput').val()
            });
            event.preventDefault();
            return false;
        });
        /* Neuladen durch abschicken des Filters per Enter */
        $('#documentmanager-searchform').on('submit', function(event){
            loadToContent2($('#documentmanager-searchform a').attr('data-url'),{
                'SearchFilterForm[searchstring]': $('#searchstringInput').val()
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
        /* Speichern der ausgewählten Docs, öffnet wieder Content1 und trägt ausgewählten IDs in Hidden-Feld ein
         * Speichern muss dann auf Zielseite erst geschehen
         */
        $('.saveLink').on('click', function(event){
            //after-select leeren (alle docItems, demo wird dabei mit gelöscht, 
            //daher vorher clonen)
            $demoItem = $('.document-manager .docItem.demo').clone();
            $('.document-manager .after-select .docItem').remove();
            $($demoItem).insertBefore('.document-manager .after-select .documentmanager-link');
            
            //alle ausgewählten Docs in Edit-Ansicht als after-select anzeigen
            $('.selected .docItem .btn-primary').each(function(  ) {
                //Eintrag wieder im Edit-Formular (ungespeichert)
                $docItem = $('.document-manager .docItem.demo').clone();
                $docItem.removeClass('demo');
                $docItem.find('input').val(this.dataset.id);
                $docItem.find('icon').addClass(this.dataset.class);
                $docItem.find('a').attr('href', this.dataset.filename);
                $docItem.find('div > a').html(this.dataset.name);
                $docItem.find('.infos .size').html(this.dataset.size);
                $docItem.find('.infos .created').html(this.dataset.created);
                $docItem.find('.infos .createdBy').html(this.dataset.createdBy);
                $($docItem).insertBefore('.document-manager .after-select .documentmanager-link');
            });

            //Bisher ausgewählte Documents ausblenden
            //$('.document-manager .documents').hide();
            //Neu Ausgewählte Documents anzeigen
            //$('.document-manager .after-select').show();
            showContent1();
            event.preventDefault();
            return false;
        });
        /* Cancel, ohne Speichern zurück zu Edit-Maske in Content1 */
        $('.cancelLink').on('click', function(event){
            showContent1();
            event.preventDefault();
            return false;
        });
        /* Auswahl eines Docs, wird damit in obere Liste der ausgwählten Docs übernommen
         */
        $('.selectLink').on('click', function(event){
            $item = $(this).parents('.docItem');
            $item.slideUp(function() {
                $('.selected').append($item);
                $item.slideDown();
            });
            event.preventDefault();
            return false;
        });
        /* Auswähltes Docs wieder entfernen
         * wird damit wieder in untere Liste unausgewählter Docs übernommen an oberster Stelle
         */
        $('.removeLink').on('click', function(event){
            $item = $(this).parents('.docItem');
            $item.slideUp(function() {
                $($item).insertAfter('.documents .list-view .summary');
                $item.slideDown();
            });
            event.preventDefault();
            return false;
        });
        
        
    </script>
