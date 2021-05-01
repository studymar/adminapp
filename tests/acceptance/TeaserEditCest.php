<?php

use yii\helpers\Url;

class TeaserEditCest
{

    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage(Url::to('/account/index'));
        $I->fillField('#loginform-username', 'admin');
        $I->fillField('#loginform-password', 'admin');
        //$I->click('#form button[type=submit]');
        //$I->click('Login','#login-button');
        $I->clickWithLeftButton('#login-button');
        $I->wait(2);
    }
    
    
    public function showTeaserEdit(AcceptanceTester $I)
    {
        $I->amOnPage(Url::to(['/page/edit']));
        $I->see('Card Headline 997 - not released - for Edit-Tests');
        
        $I->clickWithLeftButton('#edit997');
        $I->wait(2);
        $I->see('Teaser - Edit');
        $I->see('Card Headline 997 - not released - for Edit-Tests','#teaser-headline');
    }
    
}
