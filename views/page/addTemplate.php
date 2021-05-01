<?php

/* 
 * Templateauswahl.
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\helpers\FormHelper;

use app\assets\FormAsset;
FormAsset::register($this);
?>

<?php if(!Yii::$app->user->isGuest){ ?>
    <h2>Templateauswahl</h2>

    <?php $form = ActiveForm::begin(FormHelper::getConfigArray(null, false));?>
        <?php if($topError){ ?>
        <p class="alert alert-danger"><?= $topError ?></p>
        <?php } ?>

        <br/>
        <h3>Wählen Sie ein Template aus</h3>
        
        <?= $form->field($model, 'template_id')->radioList(
            \yii\helpers\ArrayHelper::map($templates,'id', 'type'),
            ['prompt'=>'Bitte auswählen'])->label(false) ?>
        
        <br/>
        
        <?= Html::submitButton('Erstellen',['class'=>'btn btn-primary']) ?>
        <?= Html::a('Zurück',['page/edit','p'=>$page->urlname],['class'=>'btn btn-outline-primary']) ?>
        
        
        
    <?php ActiveForm::end() ?>


<?php } ?>
