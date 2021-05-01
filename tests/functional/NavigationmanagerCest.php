<?php

class NavigationmanagerCest
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


    public function seeNavigationmanager(\FunctionalTester $I)
    {
        $I->amOnRoute('menu/navigationmanager');
        $I->see('Sort');//Uberschrift
        $I->see('Downloads');
        
    }

    public function seeSortUp(\FunctionalTester $I)
    {
        $I->amOnRoute('menu/sort-up',['p'=>7]);
        $I->see('Sort');//Uberschrift
        $I->see('Downloads');

        //zurücksetzen
        $I->amOnRoute('menu/sort-up',['p'=>6]);
        $I->see('Downloads');
        
        //submenu sortieren
        $I->amOnRoute('menu/sort-up',['p'=>2,'p2'=>4]);//4=Ranglisten
        $I->see('Sort');//Uberschrift
        $I->see('Ranglisten');
        $I->see('KM / RL');

        //zurücksetzen
        $I->amOnRoute('menu/sort-up',['p'=>1,'p2'=>4]);
        $I->see('Ranglisten');
        $I->see('KM / RL');
    }

    public function openUpdatePage(\FunctionalTester $I)
    {
        $I->amOnRoute('menu/navigationmanager');
        $I->click('#editDownloads');
        $I->see('Navigationmanager - Update');
        $I->seeInField('#navigation-path','downloads');//path
    }

    
    public function update(\FunctionalTester $I)
    {
        $I->amOnRoute('menu/update',['p'=>'7']);
        $I->submitForm('#form', [
            'Navigation[name]' => 'DownloadsA',
        ]);
        $I->see('Daten wurden gespeichert');
        
        //zurücksetzen
        $I->submitForm('#form', [
            'Navigation[name]' => 'Downloads',
        ]);
        $I->see('Daten wurden gespeichert');
        
    }

    public function createItem(\FunctionalTester $I)
    {
        $I->amOnRoute('menu/create-item');
        $I->see('Neuer Menüpunkt');

        //neuer submenupunkt
        $I->amOnRoute('menu/create-item',['p'=>6]);//&=Termine
        $I->see('Neuer Menüpunkt');
        $I->see('Termine');
        
    }
    
    public function deleteItem(\FunctionalTester $I)
    {
        $I->amOnRoute('menu/delete-item',['p'=>7]);//7=Downloads
        $I->see('Links');
        $I->dontSee('Downloads');

        //submenu löschen
        $I->amOnRoute('menu/delete-item',['p'=>2,'p2'=>4]);//4=ranglisten
        $I->see('Kreismeisterschaften');
        $I->dontSee('Ranglisten');
        $I->see('KM / RL');
        
    }
    
}