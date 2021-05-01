<?php

/* 
 * Stellt die radio und input-form für ein Release-Eintrag dar
 * @param $model Object, das attribut release enthält
 * @param $form
 */
?>
        <div class="release-manager">
            <div class="release-boxes">
                <?= $form->field($release, 'is_released')->radioList(['1'=>'Ja','0'=>'Nein']) ?>
            </div>
            <div class="release-dates-opener-line"><a data-toggle="collapse" href="#release-dates">>> bei bedarf Veröffentlichungszeitraum eintragen</a></div>
            <div class="release-dates collapse <?= ($release->from_date||$release->to_date)?'show':'' ?>" id="release-dates">
                <?= $form->field($release, 'from_date',['inputOptions' => ['class'=>'form-control datepicker']]) ?>
                <?= $form->field($release, 'to_date',['inputOptions' => ['class'=>'form-control datepicker']]) ?>
            </div>
        </div>


