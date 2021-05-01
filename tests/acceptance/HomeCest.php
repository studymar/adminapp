<?php

use yii\helpers\Url;

class HomeCest
{
    public function showHomePage(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/'));        
        $I->see('TTKV');
        
        //$I->seeLink('About');
        //$I->click('About');
        //$I->wait(2); // wait for page to be opened
        
       // $I->see('This is the About page.');
    }
}
