<?php

namespace app\models\mail;

use Yii;
use yii\db\Expression;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MailSender
 *
 * @author mwort
 */
class MailCollection {
    
    /**
     * 
     * @param User $user
     * @return type
     */
    public static function sendRegistrationMail($user){
        return Yii::$app->mailer->compose(
        [
            'html' => 'user-registrationmail-html',
            'text' => 'user-registrationmail-text',
        ],
        [
            'user'          => $user,
        ]) // a view rendering result becomes the message body here
        ->setFrom('no-reply@ttkv-harburg.de')
        ->setTo($user->email)
        ->setSubject('ttkv-harburg.de - Bestätigen Sie Ihre Registrierung')
        ->send();
        
    }

    /**
     * 
     * @param User $user
     * @return type
     */
    public static function sendResetPasswordMail($user, $newPassword){
        return Yii::$app->mailer->compose(
        [
            'html' => 'user-reset-passwordmail-html',
            'text' => 'user-reset-passwordmail-text',
        ],
        [
            'user'          => $user,
            'newPassword'   => $newPassword,
        ]) // a view rendering result becomes the message body here
        ->setFrom('no-reply@ttkv-harburg.de')
        ->setTo($user->email)
        ->setSubject('ttkv-harburg.de - Passwort vergessen')
        ->send();
        
    }

    
}
