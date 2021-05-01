<?php
use app\tests\fixtures\ReleaseFixture;
use app\tests\fixtures\PageFixture;
use app\tests\fixtures\TemplateFixture;
use app\tests\fixtures\PageHasTemplateFixture;
use app\tests\fixtures\TeaserlistFixture;
use app\tests\fixtures\TeaserFixture;
use app\models\content\Teaserlist;
use app\models\content\Teaser;

class RoleCest
{
    public function _fixtures()
    {
        return [
        ];
    }
    
    
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        $I->amOnPage('rolemanager/index');
    }

    public function seeRolemanager(\FunctionalTester $I)
    {
        $I->see('Admin');
    }

    public function updateRole(\FunctionalTester $I)
    {
        $I->amOnRoute('rolemanager/update',['id'=>'2']);
        $I->submitForm('#form', [
            'Role[name]' => 'StandardA',
        ]);
        $I->see('Daten wurden gespeichert');
        
        //zurücksetzen
        $I->submitForm('#form', [
            'Role[name]' => 'Standard',
        ]);
        $I->see('Daten wurden gespeichert');
        
    }

    
    public function createRole(\FunctionalTester $I)
    {
        $I->amOnRoute('rolemanager/create');
        $I->see('Neue Rolle');
        
    }
    
    public function deleteRole(\FunctionalTester $I)
    {
        $I->see('DeleteTest');
        $I->amOnRoute('rolemanager/delete',['id'=>3]);
        $I->dontSee('DeleteTest');
        
    }    
    
}