<?php
use yii\helpers\Url;
use app\models\helpers\DateConverter;
/* 
 * Auflistung der Items
 * @param $model
 */

?>


        <div class="card mb-3">
            <div>
                <img class="card-doc-top">
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $model->headline ?></h5>
                <p class="card-text">
                    <?= DateConverter::convert($model->created, DateConverter::DATETIME_FORMAT_VIEW)  ?> Autor: Mark Worthmann
                </p>
                <a href="<?= Url::to(['/'.$model->urlname]) ?>" class="btn btn-primary saveLink" data-headline="<?= $model->headline ?>" data-id="<?= $model->id ?>">Auswählen</a>
                <a href="<?= Url::to(['/'.$model->urlname]) ?>" target="_blank" class="btn btn-light">Seite anzeigen</a>
                <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
            </div>
        </div>



