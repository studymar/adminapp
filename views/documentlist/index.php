<?php

/* 
 * Documentlist
 * @param $model Documentlist
 */
use yii\helpers\Url;
use app\models\helpers\DateConverter;

?>
    <section class="document-template">
        <?php foreach($model->documents as $doc){ ?>
        <div class="document-manager">

            <div class="">
                <div class="docItem" id="document-1">
                    <div class="list column">
                        <a href="" class="icon pdf"></a>
                        <div>
                            <a href="/content/documents/up/<?= $doc->filename ?>" target="_blank"><?= $doc->name ?></a>
                            <div class="infos"><span class="size"><?= $doc->size ?> kb</span></div>
                        </div>
                    </div>    
                </div>
            </div>

        </div>
        <?php } ?>
    </section>

