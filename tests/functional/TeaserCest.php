<?php
use app\tests\fixtures\ReleaseFixture;
use app\tests\fixtures\PageFixture;
use app\tests\fixtures\TemplateFixture;
use app\tests\fixtures\PageHasTemplateFixture;
use app\tests\fixtures\TeaserlistFixture;
use app\tests\fixtures\TeaserFixture;
use app\models\content\Teaserlist;
use app\models\content\Teaser;
use yii\helpers\Url;

class TeaserCest
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

    public function seeReleasedTeaser(\FunctionalTester $I)
    {
        $I->see('Card Headline 999 - visible');
    }

    public function dontSeeUnreleasedTeaser(\FunctionalTester $I)
    {
        $I->dontSee('Card Headline 998 - not released');
    }

    public function sortUp(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        $I->amOnPage('page/edit');
        $I->see('SortUp');
        $I->amOnRoute('teaserlist/sort-up',['p'=>999]);
        //sehe immer noch das Item
        $I->see('Card Headline 999 - visible');
        //nicht mehr sortierbar, weil jetzt an Position 1
        $I->dontSeeLink('SortUp', Url::toRoute(['teaserlist/sort-up','p'=>999]));
        //dafür bisherige Position 1 sortierbar
        $I->seeLink('SortUp', Url::toRoute(['teaserlist/sort-up','p'=>997]));
        //zurueck sortieren
        $I->amOnRoute('teaserlist/sort-up',['p'=>997]);
        
    }

    public function delete(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        $I->amOnPage('page/edit');
        //item sichtbar
        $I->see('Delete');
        $I->see('Card Headline 998 - not released');
        //item löschen
        $I->amOnRoute('teaserlist/delete-item',['p'=>998]);
        //item nicht mehr sichtbar
        $I->dontSee('Card Headline 998 - not released');
        
    }
    
    public function openEditPage(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        $I->amOnPage('page/edit');
        $I->click('Edit','#edit997');
        $I->see('Teaser - Edit');
        //$I->canSeeInField('#teaser-text', 'Text 997');
        //$I->canSeeInField('Headline', 'Card Headline 997 - not released - for Edit-Tests');
    }
    
}