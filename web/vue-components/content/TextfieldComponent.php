<?php
/**
 * Shows on an editpage the text field
 * Preconditions:
 * - text given as props
 */
use app\assets\VueCKEditorAsset;
VueCKEditorAsset::register($this);

?>

<template id="textfieldcomponent">
        <div>
            <div class="row field-teaser-headline">
                <label class="control-label" v-bind:class="[{'col-sm-3': inline != 'false'},{'col-sm-12': inline == 'false'}]" v-bind:for="fieldname">{{label}}</label>
                <div v-bind:class="[{'col-sm-9': inline != 'false'},{'col-sm-12': inline == 'false'}]">
                    <validation-provider rules="ShortText|required" v-slot="{ errors }" v-bind:name="label">
                        <input type="text" id="textfield" class="form-control" 
                               v-bind:name="fieldname" 
                               v-bind:value="value"
                               v-on:input="$emit('input',$event.target.value)" 
                               aria-required="false">
                        <span class="invalid">{{ errors[0] }}</span>
                        <p class="help-block help-block-error" v-if="servererror">{{servererror}}</p>
                    </validation-provider>
                </div>
            </div>
        </div>
    
</template>

<script type="text/javascript">
    Vue.use( CKEditor );

var textfieldcomponent = {
    template: '#textfieldcomponent',
    props: ['value', 'label','fieldname', 'errormessages', 'errors', 'servererror', 'inline'],
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
