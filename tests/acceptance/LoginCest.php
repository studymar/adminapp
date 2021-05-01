<?php

use yii\helpers\Url;

class LoginCest
{

    public function login(AcceptanceTester $I)
    {
        $I->amOnPage(Url::to('/account/index'));
        $I->fillField('#loginform-username', 'admin');
        $I->fillField('#loginform-password', 'admin');
        //$I->click('#form button[type=submit]');
        //$I->click('Login','#login-button');
        $I->clickWithLeftButton('#login-button');
        $I->wait(2);
        $I->see('Mein Menü');
    }
    
}
