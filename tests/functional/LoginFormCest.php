<?php
use app\tests\fixtures\ReleaseFixture;
use app\tests\fixtures\PageFixture;

class LoginFormCest
{
    public function _fixtures()
    {
        return [
        ];
    }
    
    
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('account/index');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Login', 'h3');

    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin',
        ]);
        $I->dontSeeElement('form#form');              
    }
    
    // demonstrates `amLoggedInAs` method
    public function seeHomepageAfterLogin(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        //$I->amOnRoute('/');
        //$I->see('Teaserliste');
        $I->amOnPage('/');
        //$I->see('Logout (admin)');
    }

    public function errorByLoginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#form', []);
        $I->expectTo('see validations errors');
        $I->see('Sie haben nicht alle Felder ausgefüllt');
    }

    public function errorByLoginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Username oder Passwort nicht korrekt');
    }


}