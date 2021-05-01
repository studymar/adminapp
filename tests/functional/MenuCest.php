<?php

class MenuCest
{
    public function _fixtures()
    {
        return [
        ];
    }
    
    
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        $I->amOnPage('menu/index');
    }

    public function seeMainMenu(\FunctionalTester $I)
    {
        $I->see('Downloads');
    }

    public function seeNavigationmanager(\FunctionalTester $I)
    {
        $I->amOnRoute('menu/navigationmanager');
        $I->see('Sort');//Uberschrift
        $I->see('Downloads');
        
    }

    
}