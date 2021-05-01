<?php

/* 
 * Meine Daten-Seite
 * @param Object $model
 * @param Object $passwordModel
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\helpers\FormHelper;

use app\assets\FormAsset;
FormAsset::register($this);

?>
<?php if(!Yii::$app->user->isGuest){ ?>
    <div class="title-block">
        <h3 class="title"> Meine Daten </h3>
    </div>

    <section class="section">
        <div class="row sameheight-container">
    
            <div class="col-md-6">
                <div class="card card-block sameheight-item">
                    <div class="title-block">
                        <h3 class="title">Meine Daten</h3>
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
                                <small class="form-text text-muted font-italic">Diese Email gilt auch für Ihren Login <span class="fa fa-info-circle"></span></small>
                            </div>
                        </div>
                        <div class="form-group row has-error">
                            <label for="user-organisation_id" class="col-sm-3 form-control-label">Verein</label>
                            <div class="col-sm-9">
                                <?= Html::activeDropDownList($model,'organisation_id',$allOrganisations, ['prompt'=>'Bitte auswählen','class'=>'form-control form-control-sm']) ?>
                                <?php if($model->hasErrors('organisation_id')){ ?><span class="has-error"><?= $model->getErrors('organisation_id')[0] ?></span><?php } ?>
                            </div>
                        </div>

                        <?= Html::submitButton('Speichern',['class'=>'btn btn-primary','id'=>'user-submit-button']) ?>
                    <?php ActiveForm::end() ?>

                    <br/><br/>
                    <div class="text">
                        <p>
                            <div class="information">Hinweis</div>
                            <small>Ihre Daten werden gespeichert und dazu verwendet, Ihren Account 
                                  zu Ihnen als Person zuzuordnen. Dadurch können Sie zu Funktionen 
                                  dieser Seite, die Ihren Account betreffen informiert werden.
                                  Mit der Eingabe erklären Sie sich mit der Speicherung der 
                                  Daten einverstanden.
                            </small>
                        </p>
                    </div>
                    
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-block sameheight-item">
                    <div class="title-block">
                        <h3 class="title"> Passwort ändern </h3>
                    </div>
                    <?php if($topSuccessPassword){ ?>
                    <p class="alert alert-primary"><?= $topSuccessPassword ?></p>
                    <?php } ?>
                    <?php if($topErrorPassword){ ?>
                    <p class="alert alert-danger"><?= $topErrorPassword ?></p>
                    <?php } ?>

                    <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>

                        <div class="form-group has-error">
                            <?= Html::activeInput('password',$passwordModel,'password', ['class'=>'form-control underlined','placeholder'=>'Neues Passwort']) ?>
                            <?php if($passwordModel->hasErrors('password')){ ?><span class="has-error"><?= $passwordModel->getErrors('password')[0] ?></span><?php } ?>
                            <br/>
                            <?= Html::activeInput('password',$passwordModel,'password_repeat', ['class'=>'form-control underlined','placeholder'=>'Neues Passwort (Wdh.)']) ?>
                            <?php if($passwordModel->hasErrors('password_repeat')){ ?><span class="has-error"><?= $passwordModel->getErrors('password_repeat')[0] ?></span><?php } ?>
                        </div>

                        <?= Html::submitButton('Passwort ändern',['class'=>'btn btn-primary','id'=>'changepasswordform-submit']) ?>

                    <?php ActiveForm::end() ?>
                    
                    <div class="text">
                        <p>
                            <div class="information">Hinweis</div>
                            <small>Nach dem ändern Ihres Passworts können Sie sich nur noch mit dem neuen
                                   Passwort einloggen.
                            </small>
                        </p>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </section>

<?php } ?>
