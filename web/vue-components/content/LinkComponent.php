<?php
/**
 * 
 */
?>
<template id="linkcomponent">
    
    <span v-if="linkItem != null">
        <!-- Kein Link -->
        <div class="row link-manager" v-if="linkItem.target_page_id==null && linkItem.extern_url==null">
            <label class="control-label col-sm-3">Verlinkung</label>
            <div class="col-sm-9 radios">
                <div>
                    <div class="">
                        <label>
                            <input type="radio" class="radio squared" name="LinkItem[type]" v-model="showLinkExternInput" v-bind:value="false" v-on:click="selectLinkFromPagemanager" id="pagemanagerlink"> 
                            <span>Seitenauswahl</span>
                        </label>
                    </div>
                    <div class="">
                        <label>
                            <input type="radio" class="radio squared" id="Linktype-extern" name="LinkItem[type]" v-model="showLinkExternInput" v-bind:value="true">
                            <span>oder externen Link eingeben</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Show Intern Link -->
        <div class="row link-manager" v-else-if="linkItem.target_page_id!=null">
            <label class="control-label col-sm-3">Verlinkung Intern</label>
            <div class="col-sm-9">
                <div>
                    <a v-bind:href="'/'+linkItem.targetPage.urlname" target="_blank" id="targetpagelink">{{linkItem.targetPage.headline}}</a>
                    <a href="#" id="linkItem-delete-link" v-on:click.prevent="removeLink">(Verlinkung entfernen)</a>
                </div>
            </div>
        </div>

        <!-- Show Extern Link -->
        <div class="row link-manager" v-else-if="linkItem.extern_url!=null && showLinkExternInput == null">
            <label class="control-label col-sm-3">Verlinkung Extern</label>
            <div class="col-sm-9">
                <div>
                    <a v-bind:href="linkItem.extern_url" id="linkItem-extern-link" target="_blank">{{linkItem.extern_url}}</a>
                    <a href="#" id="linkItem-delete-link" v-on:click.prevent="removeLink">(Verlinkung entfernen)</a>
                </div>
            </div>
        </div>

        <!-- Extern Link Input Field -->
        <div class="row link-manager required" id="Linktype-extern-form" v-if="showLinkExternInput == true">
            <label class="control-label col-sm-3" for="teaser-headline">Verlinkung Extern</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="LinkItem[extern_url]" v-model="linkItem.extern_url" aria-required="true" placeholder='https://'>
                <p class="help-block help-block-error "></p>
            </div>
        </div>
        
        <hr/>
    </span>

    
</template>

<script type="text/javascript">

var linkcomponent = {
    template: '#linkcomponent',
    props: ['linkItem'],
    data: function(){
        return {
            showLinkExternInput: null /* InputFeld für externen Link anzeigen? */
        }
    },
    methods: {
        removeLink() {
            this.linkItem.extern_url        = null
            this.linkItem.target_page_id    = null
            this.linkItem.targetPage            = {}
            this.linkItem.targetPage.linkname   = null
            this.linkItem.targetPage.urlname    = null
            this.$emit('changed')
        },
        selectLinkFromPagemanager() {
            this.showLinkExternInput = false
            this.$emit('select-link-from-pagemanager')
        }
    }
}

</script>
