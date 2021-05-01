<?php
/**
 * Shows an delete confirm modal
 * Aufruf im DOM mit  data-toggle="modal" data-target="#deleteModal"
 */


?>
<template id="confirmdeletecomponent">

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Sind Sie sicher, dass Sie {{objectType}} löschen möchten?</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        <div class="font-weight-bold">{{objectName}}</div>
                        <div class="font-italic" v-if="objectName!=objectDetail">{{objectDetail}}</div>
                    </p>
                </div>
                <div class="modal-footer">
                  <button type="button" v-on:click="$parent.deleteItem" data-dismiss="modal" class="btn btn-primary">Löschen</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                </div>
            </div>
        </div>
    </div> 

    
</template>

<script type="text/javascript">
var confirmdeletecomponent = {
    template: '#confirmdeletecomponent',
    props: {
        // z.b. "folgendes Image" oder "folgendes Dokument", für Headline
        objectType: String,
        // z.b. Überschrift des Objects, wird in erster Zeile angezeigt
        objectName: String,
        // z.b. filename , wird in in zweiter Zeile angezeigt [optional]
        objectDetail: String
    },
    components: {
    },
    data: function(){
        return {
        }
    },
    mounted: function() {
    },
    methods: {
    }
}

</script>
