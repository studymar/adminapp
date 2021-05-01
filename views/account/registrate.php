<?php

/* 
 * Login-Seite
 * @param Object $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\helpers\FormHelper;

use app\assets\FormAsset;
FormAsset::register($this);

?>
<?php if(Yii::$app->user->isGuest){ ?>
                    <div class="auth-content">
                        <p class="text-center">REGISTRIEREN</p>
                        <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                            <div class="form-group">
                                <label for="firstname">Vor-/Nachname</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?= Html::activeInput('text',$model,'firstname', ['class'=>'form-control underlined','placeholder'=>'Vorname']) ?>
                                        <?php if($model->hasErrors('firstname')){ ?><p class="help-block help-block-error "><?= $model->getErrors('firstname')[0] ?></p><?php } ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= Html::activeInput('text',$model,'lastname', ['class'=>'form-control underlined','placeholder'=>'Nachname']) ?>
                                        <?php if($model->hasErrors('lastname')){ ?><p class="help-block help-block-error "><?= $model->getErrors('lastname')[0] ?></p><?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <?= Html::activeInput('email',$model,'email', ['class'=>'form-control underlined','placeholder'=>'Email']) ?>
                                <?php if($model->hasErrors('email')){ ?><p class="help-block help-block-error "><?= $model->getErrors('email')[0] ?></p><?php } ?>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?= Html::activePasswordInput($model, 'password', ['class'=>'form-control underlined','placeholder'=>'Passwort']) ?>
                                        <?php if($model->hasErrors('password')){ ?><p class="help-block help-block-error "><?= $model->getErrors('password')[0] ?></p><?php } ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= Html::activePasswordInput($model, 'password_repeat', ['class'=>'form-control underlined','placeholder'=>'Passwort wiederholen']) ?>
                                        <?php if($model->hasErrors('password_repeat')){ ?><p class="help-block help-block-error "><?= $model->getErrors('password_repeat')[0] ?></p><?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname">Verein</label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?= Html::activeDropDownList($model,'organisation_id',$allOrganisations, ['prompt'=>'Bitte auswählen','class'=>'form-control']) ?>
                                        <?php if($model->hasErrors('organisation_id')){ ?><p class="help-block help-block-error "><?= $model->getErrors('organisation_id')[0] ?></p><?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="agree">
                                    <?= Html::activeCheckbox($model, 'agree',['label'=>false,'class'=>'checkbox','id'=>'agree']) ?>
                                    <span class="lead small">Ich stimme zu, dass meine Daten gespeichert und dazu verwendet, meinen Account 
                                            zu mir als Person zuzuordnen. Mit der Kontaktaufnahme betreffend der Funktionen 
                                            dieser Seite betreffend meines Accounts bin ich einverstanden.</span>
                                    <?php if($model->hasErrors('agree')){ ?><p class="help-block help-block-error "><?= $model->getErrors('agree')[0] ?></p><?php } ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">Registrieren</button>
                            </div>
                            <div class="form-group">
                                <p class="text-muted text-center">Zurück zum <a href="<?= Url::toRoute(['account/index']) ?>">Login</a></p>
                            </div>
                            <div class="images-container"><!--um js-Fehler Sortable zu verhindern--></div>
                        
                        <?php ActiveForm::end() ?>
                    </div>




<?php }



