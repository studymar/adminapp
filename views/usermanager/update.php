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

use app\assets\FormAsset;
FormAsset::register($this);

$this->params ['contentCSS'] = "items-list-page";

?>
<?php if(!Yii::$app->user->isGuest){ ?>
    <div class="title-search-block">
        <div class="title-block">
            <h3 class="title">Usermanager</h3>
        </div>

    </div>

    <section class="section">
        <div class="row sameheight-container">
    
            <div class="col-md-6">
                <div class="card card-block sameheight-item">
                    <div class="title-block">
                        <h3 class="title">Edit User</h3>
                    </div>

                    <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                        <?php if($topSuccess){ ?>
                        <p class="alert alert-primary"><?= $topSuccess ?></p>
                        <?php } ?>
                        <?php if($topError){ ?>
                        <p class="alert alert-danger"><?= $topError ?></p>
                        <?php } ?>
                        
                        <div class="form-group row">
                            <label for="user-firstname" class="col-sm-3 form-control-label">Vorname</label>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text',$model,'firstname', ['class'=>'form-control']) ?>
                                <?php if($model->hasErrors('firstname')){ ?><span class="has-error"><?= $model->getErrors('firstname')[0] ?></span><?php } ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="user-lastname" class="col-sm-3 form-control-label">Nachname</label>
                            <div class="col-sm-9">
                                <?= Html::activeInput('text',$model,'lastname', ['class'=>'form-control']) ?>
                                <?php if($model->hasErrors('lastname')){ ?><span class="has-error"><?= $model->getErrors('lastname')[0] ?></span><?php } ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="user-email" class="col-sm-3 form-control-label">Email</label>
                            <div class="col-sm-9">
                                <?= Html::activeInput('email',$model,'email', ['class'=>'form-control','placeholder'=>'Email']) ?>
                                <?php if($model->hasErrors('email')){ ?><span class="has-error"><?= $model->getErrors('email')[0] ?></span><?php } ?>
                                <small class="form-text text-muted font-italic">Diese Email gilt auch für den Login <span class="fa fa-info-circle"></span></small>
                            </div>
                        </div>
                        <div class="form-group row has-error">
                            <label for="user-organisation_id" class="col-sm-3 form-control-label">Verein</label>
                            <div class="col-sm-9">
                                <?= Html::activeDropDownList($model,'organisation_id',$allOrganisations, ['prompt'=>'Bitte auswählen','class'=>'form-control form-control-sm']) ?>
                                <?php if($model->hasErrors('organisation_id')){ ?><span class="has-error"><?= $model->getErrors('organisation_id')[0] ?></span><?php } ?>
                            </div>
                        </div>
                
                        <?= Html::submitButton('Speichern',['class'=>'btn btn-primary']) ?>
                        <?= Html::a('Zurück zur Übersicht',['usermanager/index'],['class'=>'btn btn-outline-primary']) ?>
                        <?php ActiveForm::end() ?>

                </div>
            </div>
            <div class="col-md-6">
                <?= $this->render('_update-rights',['model'=>$model, 'allRoles'=>$allRoles, 'allRightGroups'=>$allRightGroups]) ?>
            </div>
                    
        </div>
    </section>

<?php } ?>


