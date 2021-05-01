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
                        <p class="text-center">PASSWORT VERGESSEN</p>
                            <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control underlined" name="ResetPasswordForm[email]" id="email" placeholder="Emailadresse">
                                    <?php if($model->hasErrors('email')){ ?><p class="help-block help-block-error "><?= $model->getErrors('email')[0] ?></p><?php } ?>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block btn-primary">Absenden</button>
                                </div>
                                <div class="form-group">
                                    <p class="text-muted text-center">Noch kein Account? <a href="<?= Url::toRoute(['account/registrate']) ?>">Registrieren</a></p>
                                </div>
                                <div class="images-container"><!--um js-Fehler Sortable zu verhindern--></div>
                            <?php ActiveForm::end() ?>
                    </div>
                    <div class="text-center">
                        <a href="<?= Url::toRoute(['account/index']) ?>" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Zurück zum Login </a>
                    </div>
        
<?php } else if(!Yii::$app->user->isGuest){ ?>
                    <div class="auth-content">
                        Sie sind bereits eingeloggt!
                    </div>
                    <div class="text-center">
                        <a href="<?= Url::toRoute(['account/index']) ?>" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Weiter zur Startseite </a>
                    </div>
                        
<?php } ?>


