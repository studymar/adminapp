<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user User User, der die Mail erhält */

$text = "
        <p>Unter Ihrer Emailadresse wurde soeben ein Account unter ttkv-harburg.de
        registriert. Um die Registrierung abzuschließen, müssen Sie bestätigen,
        dass Sie diese Email erhalten haben und Ihre Emailadresse korrekt ist.
        Dazu rufen Sie bitte folgenden Link auf:<br/>
        <br/>
        <a href=\"".Yii::$app->params['domain']."/account/registration-finished/".$user->validationtoken."\">Jetzt bestätigen</a>
        <br/><br/>
        Ihr TTKV Harburg-Land e.V.<br/>
        ttkv-harburg.de
        </p>
";

?>

        <?= $this->render('partials/_text-ohne-bild', [
            'text' => $text,
            'headline' => "Ihre Registrierung auf ttkv-harburg.de",
        ]) ?>




