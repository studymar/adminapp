<?php

/* 
 * Formular zum Erstellen eines Topics
 * @param Object $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;

use app\assets\FormAsset;
FormAsset::register($this);

?>
<?php if(!Yii::$app->user->isGuest){ ?>
    <h3>Votingthema erstellen</h3>

    <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
        <?php if($topError){ ?>
        <p class="alert alert-danger"><?= $topError ?></p>
        <?php } ?>
    
        <?= $form->field($model, 'headline') ?>
        <?= $form->field($model, 'description') ?>
        <div class="form-group row nopadding">
            <div class="col-3">Aktiv/Sichtbar?</div>
            <div class="col-9">
                <?= $form->field($model, 'active')->checkbox()->label(false) ?>
            </div>
        </div>
                
        <?= Html::submitButton('Erstellen',['class'=>'btn btn-primary']) ?>
        <?= Html::a('Zurück',['voting/edit'],['class'=>'btn btn-outline-primary']) ?>
        
        
        
    <?php ActiveForm::end() ?>


<?php } ?>


