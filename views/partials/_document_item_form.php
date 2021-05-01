<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\helpers\DateConverter;

/* 
 * Stellt die radio und input-form für ein Release-Eintrag dar
 * @param $documentList
 * @param $form
 */
?>
        <div class="row document-manager">
            <label class="control-label col-sm-3">Dokumente</label>
            <!-- Nach Auswahl - aber bevor speichern -->
            <div class="col-sm-9 after-select">
                <div>
                    <div class="docItem demo">
                        <?= Html::hiddenInput('document_ids[]',"",['class'=>'DocumentListInputField']) ?>
                        <a href="" class="icon pdf"></a>
                        <div>
                            <a href="" target="_blank">Mein File</a>
                            <div class="infos"><span class="size">24kb</span> / Hochgeladen am <span class="created">01.01.2019</span> von <span class="createdBy">Mark Worthmann</span></div>
                        </div>
                    </div>
                    <?php foreach($documentList->getDocumentItems()->all() as $docitem): ?>
                    <div class="docItem">
                        <a href="<?= Url::to("/".$docitem->document->filename) ?>" class="icon <?= Url::to($docitem->document->extensionname) ?>"></a>
                        <div>
                            <a href="" target="_blank"><?= Url::to($docitem->document->name) ?></a>
                            <div class="infos"><?= $docitem->document->size/1024 ?>kb / Hochgeladen am <?= DateConverter::convert($docitem->document->created) ?> von <?= ($docitem->document->created_by)?$docitem->document->createdBy->getName():'unbekannt' ?></div>
                        </div>
                    </div>
                    <?php endforeach;  ?>
                    <div class="documentmanager-link">
                        <a href="../" class="Documentmanager-link opener" data-url="<?= Url::toRoute(['documentmanager/index','p'=>$documentList->id]) ?>" data-saveurl="<?= $saveLink ?>">Dokumente hinzufügen/entfernen</a>
                    </div>
                </div>
            </div>
        </div>
