<?php

/* 
 * Stellt die radio und input-form für ein Release-Eintrag dar
 * @param $model Object, das attribut release enthält
 * @param $form
 * @param $release [optional] wenn übergeben, dann wird statt $model->release dieser Wert verwendet (z.b. bei Neueinträgen)
 */
use app\assets\FormAsset;
FormAsset::register($this);

$releaseModel = (isset($release))? $release : $model->release;

?>
        <div class="release-manager">
            <div class="release-boxes">
                <?= $form->field($releaseModel, 'is_released')->radioList(['1'=>'Ja','0'=>'Nein'],
                        [
                        'item'=> function($index, $label, $name, $checked, $value) { 
                            $checked = $checked ? 'checked' : '';
                            return "
                                <div>
                                    <label>
                                        <input class='radio squared' name='{$name}' type='radio' value='{$value}' {$checked} >
                                        <span>{$label}</span>
                                    </label>
                                </div>
                            ";
                        }
                        ])
                        ->label('Öffentlich sichtbar<br>
                            <div class="release-dates-opener-line"><a data-toggle="collapse" href="#release-dates">>> Veröffentlichungszeitraum</a></div>
                        ')
                        ?>
            </div>
            <div class="release-dates collapse <?= ($releaseModel->from_date||$releaseModel->to_date)?'show':'' ?>" id="release-dates">
                <?= $form->field($releaseModel, 'from_date',['inputOptions' => ['class'=>'form-control flatpickr datepicker']]) ?>
                <?= $form->field($releaseModel, 'to_date',['inputOptions' => ['class'=>'form-control flatpickr datepicker']]) ?>
            </div>
            <script type="text/javascript">
                flatpickr(".flatpickr");
            </script>
        </div>


