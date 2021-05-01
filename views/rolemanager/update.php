<?php

/* 
 * Userverwaltung Update-Seite
 * @param Object $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;
use app\models\role\Right;

use app\assets\FormAsset;
FormAsset::register($this);

?>
<?php if(!Yii::$app->user->isGuest){ ?>
    <div class="title-block">
        <h3 class="title"> Rollemanager - Rolle ändern </h3>
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

                    <!-- Name der Rolle ändern -->
                    <?= $form->field($model, 'name') ?>

                    <?php foreach($allRightGroups as $group){ ?>
                    <div class="form-list">
                        <hr/>
                        <div class="form-list-headline font-weight-bold mb-2"><?= $group->name ?></div>
                        <?= Html::checkboxList('RoleHasRights',  ArrayHelper::map( $model->getRights()->asArray()->all(),'id','id'),ArrayHelper::map( $group->getRights()->asArray()->all(),'id','name'),
                                ['itemOptions'=>[
                                    'disabled'=>'disabled'
                                ],
                                'item'=> function($index, $label, $name, $checked, $value) { 
                                    $checked = $checked ? 'checked' : '';
                                    $right   = Right::find()->where(['id'=>$value])->one();
                                    return "
                                        <div>
                                            <label>
                                                <input class='checkbox' name='{$name}' type='checkbox' value='{$value}' {$checked} >
                                                <span>{$label}</span>
                                                <span class='font-weight-normal font-italic'>".$right->info."</span>
                                            </label>
                                        </div>
                                    ";
                                }
                            ])

                            ?>
                        
                    </div>
                    <?php } ?>


                    <?= Html::submitButton('Speichern',['class'=>'btn btn-primary']) ?>
                    <?= Html::a('Zurück',['rolemanager/index'],['class'=>'btn btn-outline-primary']) ?>
                <?php ActiveForm::end() ?>

                </div>
            </div>
        </div>
    </section>

<?php } ?>


