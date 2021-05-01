<?php
/**
 * Shows on an editpage the field for one or many Images
 * Preconditions:
 * - imageList given as props
 */

?>

<template id="imagecomponent">
    
    <span v-if="imageList != null">
        <!-- Kein Image -->
        <div class="row image-item" v-if="imageList.uploadedimages && imageList.uploadedimages.length == 0">
            <label class="control-label col-sm-3">Image</label>
            <div class="col-sm-9 radios">
                <div>
                    <div>
                        <div class="noImage-text">Kein image</div>
                        <a href="#" id="imagemanagerlink" v-on:click.prevent="selectImageFromImagemanager(imageList)"> Image auswählen</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Show Image -->
        <div class="row image-item" v-else-if="imageList.uploadedimages && imageList.uploadedimages.length > 0">
            <label class="control-label col-sm-3">
                Image<br>
                <a href="#" id="imagemanagerlink" v-on:click.prevent="selectImageFromImagemanager(imageList)"> Imagemanager</a>
            </label>
            <div class="col-sm-9 list column">
                <div class="card image mb-3" v-for="item in imageList.uploadedimages" v-bind:key="item.id" v-bind:id="'image-'+item.id">
                    <div class="card-img-top-wrapper">
                        <img class="card-img-top" v-bind:src="'/content/images/up/' + item.filename">
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">
                            {{item.name}}
                        </p>
                    </div>
                </div>

                <!-- /*
                <div>
                    <img v-bind:src="'/content/images/up/' + imageList.uploadedimages[0].filename" target="_blank"/>
                    <a href="#" id="linkItem-delete-link" v-on:click.prevent="removeImage">(Image entfernen)</a>
                </div>
                */ -->
            </div>
        </div>
        <hr/>

    </span>
    
    
</template>

<script type="text/javascript">

var imagecomponent = {
    template: '#imagecomponent',
    props: ['imageList'],
    data: function(){
        return {
        }
    },
    methods: {
        removeImage() {
            this.$parent.$parent.changed()
        },
        selectImageFromImagemanager(imageList) {
            this.$parent.$parent.selectImageFromImagemanager(imageList)
        }
    }
}

</script>
