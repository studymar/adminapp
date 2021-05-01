<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user User User, der die Mail erhält */
/* @var $newPassword String Neues Passwort */

?>
        Ihre Registrierung bei ttkv-harburg.de</h2

        Sie haben für Ihre Emailadresse unter ttkv-harburg.de das Zurücksetzen
        Ihres Passworts angefordert. Ihr Passwort wurde daher geändert. Bitte nutzen
        Sie folgendes neues Passwort, um sich einzuloggen. Unter "Meine Daten" können Sie
        danach Ihr Passwort wieder auf einen selbst gewählten Wert anpassen.
        
        Neues Passwort: <?= $newPassword; ?>

        Ihr TTKV Harburg-Land e.V.<br/>
        ttkv-harburg.de
