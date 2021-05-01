<?php
/**
 * Shows on an editpage the text field
 * Preconditions:
 * - text given as props
 */
use app\assets\VueCKEditorAsset;
VueCKEditorAsset::register($this);

?>

<template id="textcomponent">
        <div>
            <div class="row field-teaser-headline">
                <label class="control-label col-sm-3" for="text-headline">Textüberschrift (optional)</label>
                <div class="col-sm-9">
                    <validation-provider rules="ShortText" v-slot="{ errors }" name="Headline">
                        <input type="text" id="text-headline" class="form-control" name="Headline" v-model="text.headline" aria-required="false">
                        <span class="invalid">{{ errors[0] }}</span>
                        <p class="help-block help-block-error " >{{(errormessages && errormessages.text)?errormessages.text.headline[0]:''}}</p>
                    </validation-provider>
                </div>
            </div> 
            <div class="row field-text required">
                <label class="control-label col-sm-3" for="text-text">Text</label>
                <div class="col-sm-9">
                    <validation-provider rules="required|LongText" v-slot="{ errors }" name="Text">
                        <ckeditor :editor="editor" v-model="text.text" :config="editorConfig" name="Text"></ckeditor>
                        <span class="invalid">{{ errors[0] }}</span>
                        <p class="help-block help-block-error " >{{(errormessages && errormessages.text)?errormessages.text.text[0]:''}}</p>
                    </validation-provider>
                </div>
            </div>
            <hr/>
        </div>
    
</template>

<script type="text/javascript">
    Vue.use( CKEditor );

var textcomponent = {
    template: '#textcomponent',
    props: ['text', 'errormessages', 'errors'],
    data: function(){
        return {
            editor: ClassicEditor,
            editorConfig: {
                toolbar: [ 'bold', 'italic', 'strikethrough', 'subscript','link','outdent', 'indent','bulletedList', 'numberedList', 'undo', 'redo']
            }
        }
    },
    methods: {
    }
}

</script>
