<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* 
 * Stellt die radio und input-form für ein Release-Eintrag dar
 * @param $model LinkItem
 * @param $form
 */
?>
        <?php if($model->target_page_id == null && $model->extern_url == null): ?>
        <div class="row link-manager">
            <label class="control-label col-sm-3">Verlinkung</label>
            <div class="col-sm-9 radios">
                <div>
                    <div class="radio">
                        <label><a href="../" class="Linktype-intern-select-link" data-url="<?= Url::toRoute(['pageselect/index']) ?>" data-saveurl="<?= $saveLink ?>"><input type="radio" name="LinkItem[type]" value="1"> Interne Seite auswählen</a></label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" id="Linktype-extern" name="LinkItem[type]" value="0"> oder externen Link eingeben</label>
                    </div>
                </div>
            </div>
            <!-- Nach Auswahl - aber bevor speichern -->
            <div class="col-sm-9 after-select">
                <div>
                    <a href="" target="_blank" id="after-select-link">Seite A</a>
                    <a href="#" id="after-select-remove-link" >(Verlinkung entfernen)</a>
                    <?= Html::hiddenInput('LinkItem[target_page_id]',"",['id'=>'LinkItemInput']) ?>
                </div>
            </div>
        </div>
        <div id="Linktype-extern-form">
            <?= $form->field($model, 'extern_url')->textInput(['placeholder'=>'http://']) ?>
        </div>
        <?php elseif($model->target_page_id): ?>
        <div class="row link-manager">
            <label class="control-label col-sm-3">Verlinkung Intern</label>
            <div class="col-sm-9">
                <div>
                    <a href="<?= Url::to("/".$model->targetPage->urlname) ?>" target="_blank"><?= Url::to($model->targetPage->headline) ?></a>
                    <a href="<?= $removeLink ?>" id="linkItem-delete-link" data-confirm="Achtung: Nicht gespeicherte Änderungen gehen verloren">(Verlinkung entfernen)</a>
                </div>
            </div>
        </div>
        <?php elseif($model->extern_url): ?>
        <div class="row link-manager">
            <label class="control-label col-sm-3">Verlinkung Extern</label>
            <div class="col-sm-9">
                <div>
                    <a href="<?= Url::to($model->extern_url) ?>" id="linkItem-extern-link">https://google.com</a>
                    <a href="<?= $removeLink ?>" id="linkItem-delete-link" data-confirm="Achtung: Nicht gespeicherte Änderungen gehen verloren">(Verlinkung entfernen)</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

