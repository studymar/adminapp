<?php

/* 
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
    <div class="title-block">
        <h3 class="title"> Navigationmanager - Update </h3>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-block">

                    <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                        <?php if($topSuccess){ ?>
                        <p class="alert alert-success"><?= $topSuccess ?></p>
                        <?php } ?>
                        <?php if($topError){ ?>
                        <p class="alert alert-danger"><?= $topError ?></p>
                        <?php } ?>

                        <?= $form->field($model, 'name') ?>
                        <?= (!isset($model->page))? $form->field($model, 'path') : '' ?>

                        <?= $this->render('//partials/_releaseform',['model'=>$model, 'form'=>$form]) ?>


                        <?= Html::submitButton('Speichern',['class'=>'btn btn-primary']) ?>
                        <?php if(!$p2){ ?>
                        <?= Html::a('Zurück',['menu/navigationmanager'],['class'=>'btn btn-outline-primary']) ?>
                        <?php } else { ?>
                        <?= Html::a('Zurück',['menu/subnavigationmanager','p'=>$p2],['class'=>'btn btn-outline-primary']) ?>
                        <?php } ?>

                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </section>


<?php } ?>


