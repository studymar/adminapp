<?php
/**
 * Shows on an editpage the text field
 * Preconditions:
 * - text given as props
 */
use app\assets\VueCKEditorAsset;
VueCKEditorAsset::register($this);

?>

<template id="textareacomponent">
        <div>
            <div class="row field-text required">
                <label class="control-label col-sm-3" for="text-text">{{label}}</label>
                <div class="col-sm-9">
                    <validation-provider rules="required|LongText" v-slot="{ errors }" name="Text">
                        <ckeditor :editor="editor" 
                            v-bind:value="value"
                            v-on:input="$emit('input',$event)"
                            :config="editorConfig" 
                            v-bind:name="fieldname">
                        </ckeditor>
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

var textareacomponent = {
    template: '#textareacomponent',
    props: ['value', 'errormessages', 'errors', 'label', 'fieldname'],
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
