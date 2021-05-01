<?php
/**
 * Shows on an editpage the field for one or many Documents
 * Preconditions:
 * - documentList given as props
 */

?>

<template id="documentcomponent">
    
    <span v-if="documentList != null">
        <!-- Kein Doc -->
        <div class="row image-item" v-if="documentList.documents.length == 0">
            <label class="control-label col-sm-3">Dokumente</label>
            <div class="col-sm-9 radios">
                <div>
                    <div>
                        <div class="noImage-text">Kein Dokument</div>
                        <a href="#" id="documentmanagerlink" v-on:click.prevent="selectDocFromDocumentmanager(documentList)"> Datei auswählen</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Show Doc -->
        <div class="row document-manager" v-else-if="documentList.documents.length > 0">
            <label class="control-label col-sm-3">
                Dokument<br>
                <a href="#" id="documentmanagerlink" v-on:click.prevent="selectDocFromDocumentmanager(documentList)"> Dokumentenmanager</a>
            </label>
            <div class="col-sm-9">
                <div class="docItem" v-for="item in documentList.documents" v-bind:key="item.id" v-bind:id="'document-'+item.id">
                    <div class="list column">
                        <a href="" class="icon" v-bind:class="item.extensionname"></a>
                        <div>
                            <a v-bind:href="'/content/documents/up/' + item.filename" target="_blank">{{item.name}}</a>
                            <div class="infos"><span class="size">{{item.size}}kb</span> / Hochgeladen am <span class="created" v-if="item.created">{{item.created}}</span></div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
        <hr/>

    </span>
    
    
</template>

<script type="text/javascript">

var documentcomponent = {
    template: '#documentcomponent',
    props: ['documentList'],
    data: function(){
        return {
        }
    },
    methods: {
        removeImage() {
            this.$parent.$parent.changed()
        },
        selectDocFromDocumentmanager(documentList) {
            this.$parent.$parent.selectDocFromDocumentmanager(documentList)
        }
    }
}

</script>
