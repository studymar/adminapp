<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* 
 * Stellt die radio und input-form für ein Release-Eintrag dar
 * @param $model LinkItem
 * @param $form
 */
?>
    <span v-if="item.linkItem != null">
        <!-- Kein Link -->
        <div class="row link-manager" v-if="item.linkItem.targetpage_id==null && item.linkItem.extern_url==null">
            <label class="control-label col-sm-3">Verlinkung</label>
            <div class="col-sm-9 radios">
                <div>
                    <div class="radio">
                        <label><input type="radio" name="LinkItem[type]" v-model="form.showLinkExternInput" v-bind:value="false" v-on:click="selectFromPagemanager"> Interne Seite auswählen</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" id="Linktype-extern" name="LinkItem[type]" v-model="form.showLinkExternInput" v-bind:value="true"> oder externen Link eingeben</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Intern Link -->
        <div class="row link-manager" v-else-if="item.linkItem.targetpage_id!=null">
            <label class="control-label col-sm-3">Verlinkung Intern</label>
            <div class="col-sm-9">
                <div>
                    <a v-bind:href="item.linkItem.targetpage_id" target="_blank">{{item.linkItem.targetpage.linkname}}</a>
                    <a href="<?= $removeLink ?>" id="linkItem-delete-link" data-confirm="Achtung: Nicht gespeicherte Änderungen gehen verloren">(Verlinkung entfernen)</a>
                </div>
            </div>
        </div>

        <!-- Extern Link -->
        <div class="row link-manager" v-else-if="item.linkItem.extern_url!=null && form.showLinkExternInput == false">
            <label class="control-label col-sm-3">Verlinkung Extern</label>
            <div class="col-sm-9">
                <div>
                    <a v-bind:href="item.linkItem.extern_url" id="linkItem-extern-link">{{item.linkItem.extern_url}}</a>
                    <a href="#" id="linkItem-delete-link" v-on:click.prevent="removeLink">(Verlinkung entfernen)</a>
                </div>
            </div>
        </div>

        <!-- Extern Link Input Field -->
        <div class="row link-manager required" id="Linktype-extern-form" v-if="form.showLinkExternInput == true">
            <label class="control-label col-sm-3" for="teaser-headline">Verlinkung Extern</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="LinkItem[extern_url]" v-model="item.linkItem.extern_url" aria-required="true" placeholder='https://'>
                <p class="help-block help-block-error "></p>
            </div>
        </div> 
    </span>

