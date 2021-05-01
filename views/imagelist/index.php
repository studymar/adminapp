<?php

/* 
 * ImageList (ohne Edit-Line)
 */
use yii\helpers\Url;
use app\models\helpers\DateConverter;

?>
    <section class="image-template">
        <?php if($model->countUploadedimages() > 1 ){ ?>
        <div class="d-flex flex-row flex-wrap align-self-stretch align-items-stretch align-content-stretch gallery">
            <?php foreach($model->uploadedimages as $item){ ?>
            <div class="flex-fill flex-wrap gallery-item img-thumbnail">
                <img src="<?= Url::to("@web/content/images/up/".$item->filename) ?>" class="" alt="...">
            </div>
            <?php } ?>
        </div>
        <?php } else if($model->countUploadedimages() == 1 ){ ?>
        <img src="<?= Url::to("@web/content/images/up/".$model->getFirstUploadedimage()->filename) ?>" class="img-fluid" alt="...">
        <?php }  ?>
    </section>

