<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user User User, der die Mail erhält */
/* @var $newPassword String Neues Passwort */

$text = "
        <p>Sie haben für Ihre Emailadresse unter ttkv-harburg.de das Zurücksetzen
        Ihres Passworts angefordert. Ihr Passwort wurde daher geändert. Bitte nutzen
        Sie folgendes neues Passwort, um sich einzuloggen. Unter \"Meine Daten\" können Sie
        danach Ihr Passwort wieder auf einen selbst gewählten Wert anpassen.<br/>
        <br/>
        Neues Passwort: ".$newPassword."
        <br/><br/>
        Ihr TTKV Harburg-Land e.V.<br/>
        ttkv-harburg.de
        </p>
";

?>

        <?= $this->render('partials/_text-ohne-bild', [
            'text' => $text,
            'headline' => "Ihr Passwort auf ttkv-harburg.de",
        ]) ?>




