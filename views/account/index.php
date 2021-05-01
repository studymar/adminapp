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
                        <p class="text-center">LOGIN TO CONTINUE</p>
                            <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                                <div class="form-group">
                                    <label for="username">Email oder Username</label>
                                    <input type="text" class="form-control underlined" name="LoginForm[username]" id="username" placeholder="Your email address">
                                    <?php if($model->hasErrors('username')){ ?><p class="help-block help-block-error "><?= $model->getErrors('username')[0] ?></p><?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control underlined" name="LoginForm[password]" id="password" placeholder="Your password">
                                    <?php if($model->hasErrors('password')){ ?><p class="help-block help-block-error "><?= $model->getErrors('password')[0] ?></p><?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="remember">
                                        <input class="checkbox" id="remember" type="checkbox" name="LoginForm[rememberMe]">
                                        <span>Remember me</span>
                                    </label>
                                    <a href="<?= Url::toRoute(['account/reset-password']) ?>" class="forgot-btn pull-right">Passwort vergessen?</a>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block btn-primary">Login</button>
                                </div>
                                <div class="form-group">
                                    <p class="text-muted text-center">Noch kein Account? <a href="<?= Url::toRoute(['account/registrate']) ?>">Registrieren</a></p>
                                </div>
                                <div class="images-container"><!--um js-Fehler Sortable zu verhindern--></div>
                            <?php ActiveForm::end() ?>
                    </div>

    
<?php } else { ?>
                    <div class="auth-content">
                        <p class="text-center">
                            Sie sind bereits eingeloggt </br>
                            <a href="<?= Url::toRoute(['account/logout']) ?>">Logout</a>
                        </p>
                    </div>
<?php } ?>




