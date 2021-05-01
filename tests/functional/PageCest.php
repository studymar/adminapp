<?php

class PageCest
{
    public function _fixtures()
    {
        return [

        ];
    }
    
    
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage('/');
    }

    public function seeNoEditButtonWithoutLogin(\FunctionalTester $I)
    {
        $I->dontSee('Zum Edit-Modus wechseln');
    }

    public function seeEditButtonAfterLogin(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        $I->amOnPage('/');
        $I->see('Zum Edit-Modus wechseln');
    }

    public function seeCloseEditModusButtonOnEditPage(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        $I->amOnPage('page/edit');
        $I->see('Edit-Modus beenden');
    }

    

}