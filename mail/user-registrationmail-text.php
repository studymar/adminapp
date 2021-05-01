<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user User User, der die Mail erhält */
$validationlink = Yii::$app->params['domain']."/account/registration-finished/".$user->validationtoken;
?>
        Ihre Registrierung bei ttkv-harburg.de</h2

        Unter Ihrer Emailadresse wurde soeben ein Account unter ttkv-harburg.de
        registriert. Um die Registrierung abzuschließen, müssen Sie bestätigen,
        dass Sie diese Email erhalten haben und Ihre Emailadresse korrekt ist.
        Dazu rufen Sie bitte folgenden Link auf:
        
        <?= $validationlink ?>

        Ihr TTKV Harburg-Land e.V.<br/>
        ttkv-harburg.de
