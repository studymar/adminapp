<?php

/* 
 * @param Object $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;
use app\models\content\Page;

use app\assets\FormAsset;
FormAsset::register($this);

?>
    <div class="title-block">
        <h3 class="title"> Navigationmanager - Hauptmenüpunkt erstellen </h3>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">

                    <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                        <?php if($topError){ ?>
                        <p class="alert alert-danger"><?= $topError ?></p>
                        <?php } ?>

                        <?= $form->field($model, 'name') ?>
                        <?= $form->field($pagetype, 'pagetype_id')->radioList(ArrayHelper::map(Page::getPagetypes(),"id","name"),
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
                                ?>

                        <?= $this->render('//partials/_releaseform',['release'=>$newRelease,'model'=>$model, 'form'=>$form]) ?>


                        <?= Html::submitButton('Speichern',['class'=>'btn btn-primary']) ?>
                        <?php if(isset($p)){ ?>
                        <?= Html::a('Zurück',['menu/subnavigationmanager','p'=>$p],['class'=>'btn btn-outline-primary']) ?>
                        <?php } else { ?>
                        <?= Html::a('Zurück',['menu/navigationmanager'],['class'=>'btn btn-outline-primary']) ?>
                        <?php } ?>

                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </section>




