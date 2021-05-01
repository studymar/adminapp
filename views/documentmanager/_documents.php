<?php
use yii\helpers\Url;
use app\models\helpers\DateConverter;
/* 
 * Auflistung der Items
 * @param $model
 */

?>

                <div class="docItem">
                    <a href="" class="icon <?= $model->extensionname ?>" target="_blank"></a>
                    <div>
                        <a href="<?= Url::to("/".$model->name) ?>" class="openLink" target="_blank"><?= Url::to($model->name) ?></a>
                        <div class="infos"><?= $model->filename ?> (<?= round($model->size/1024) ?> kb)</div>
                        <div class="buttons">
                            <a href="<?= Url::to(['/'.$model->filename]) ?>" class="btn btn-primary selectLink" data-class="<?= $model->extensionname ?>" data-filename="<?= $model->filename ?>" data-name="<?= $model->name ?>" data-id="<?= $model->id ?>" data-size="<?= $model->size ?>" data-created="<?= $model->created ?>" data-createdBy="<?= $model->createdBy ?>">Auswählen</a>
                            <a href="" class="btn btn-danger removeLink">Entfernen</a>
                            <a href="<?= Url::to(['/'.$model->filename]) ?>" target="_blank" class="btn btn-light">Dokument anzeigen</a>
                        </div>
                    </div>
                </div>

