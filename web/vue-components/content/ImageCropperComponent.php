<?php

/* 
 * Cropper
 */
use app\assets\VueAdvancedCropperAsset;
VueAdvancedCropperAsset::register($this);

?>

<template id="imagecroppercomponent">
<span>
    <h3>Image zuschneiden</h3>

    <cropper
        ref="cropper"
        classname="cropper"
      :src="'/content/images/up/' + item.filename"
      :stencil-props="{
		aspectRatio: 100/67
      }"
      @change="change"
    ></cropper>
    
    <br/>
    <button v-on:click="saveItem" class="btn btn-primary">Speichern</button>
    <button v-on:click="$parent.closeImagecropper()" class="btn btn-outline-primary">Abbrechen</button>
    <br/><br/>
    <p class="alert alert-danger" v-if="topError">{{topErrorMessage}}</p>
    
</span>
</template>

<script type="text/javascript">
var imagecroppercomponent = {
    template: '#imagecroppercomponent',
    props: {
        item: Object
    },
    components: {
    },
    data: function(){
        return {
            canvas: null,
            coordinates: {},
            cropLink: '/imagemanager/crop-image/',
            topError: false,
            topErrorMessage: 'Fehler: Image konnte nicht zugeschnitten werden.'
        }
    },
    mounted: function() {
    },
    methods: {
        change({coordinates, canvas}) {
           console.log(coordinates, canvas)
           this.canvas = canvas
           this.coordinates = coordinates
        },
        saveItem(){
            self = this

            $.post(self.cropLink + this.item.id,
            {
                "UploadedimageCropForm[left]": this.coordinates.left,
                "UploadedimageCropForm[top]": this.coordinates.top,
                "UploadedimageCropForm[width]": this.coordinates.width,
                "UploadedimageCropForm[height]": this.coordinates.height,
                "UploadedimageCropForm[areaWidth]": this.$refs.cropper.$refs.area.clientWidth,
                "UploadedimageCropForm[areaHeight]": this.$refs.cropper.$refs.area.clientHeight
            })
            .done(function(data) {
                if(!data.saved){
                    self.topError = true
                    console.log(data.errormessages)
                }
                else {
                    self.topError = false
                    self.$parent.closeImagecropper()
                    self.$parent.getItem()
                }
            })
            .fail(function(data) {
                self.topError = true
                console.log( data.responseText )
            });
            
        }        
    }
}

</script>


