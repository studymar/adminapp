<?php

class UsermanagerCest
{
    public function _fixtures()
    {
        return [
        ];
    }
    
    
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\user\User::findByUsername('admin'));
        $I->amOnPage('usermanager/index');
    }

    public function seeUsermanager(\FunctionalTester $I)
    {
        $I->see('Admin');
    }

    public function updateUser(\FunctionalTester $I)
    {
        $I->amOnRoute('usermanager/update',['id'=>'2']);
        $I->submitForm('#form', [
            'User[name]' => 'EditorA',
        ]);
        $I->see('Daten wurden gespeichert');
        
        //zurücksetzen
        $I->submitForm('#form', [
            'User[name]' => 'Editor',
        ]);
        $I->see('Daten wurden gespeichert');
        
    }
    
}