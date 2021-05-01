<?php

namespace tests\models;

use app\tests\fixtures\RoleFixture;
use app\tests\fixtures\RightFixture;
use app\tests\fixtures\RoleHasRightFixture;
use app\tests\fixtures\RightgroupFixture;
use app\models\role\Right;
use app\models\role\Role;

class RoleTest extends \Codeception\Test\Unit
{

    public function _before()
    {

    }
    public function _after()
    {
    }
    
    public function testHasRight()
    {
        $item = $this->tester->grabFixture('role', 0);
        $this->assertTrue($item->hasRight(1));
    }

    public function testCreate()
    {
        $item = Role::create();
        $this->assertTrue($item->id > 0);
        
        //wieder löschen
        $this->assertTrue($item->delete() == 1);
        
    }
    
    public function testSaveRights()
    {
        $item = $this->tester->grabFixture('role', 2);
        $this->assertTrue($item->saveRights([1,100]));
        $this->assertTrue($item->hasRight(1));
        $this->assertTrue($item->hasRight(100));
        
        //wieder entfernen
        $this->assertTrue($item->saveRights([]));
        $this->assertFalse($item->hasRight(1));
        $this->assertFalse($item->hasRight(100));
        
    }
    
    public function testSavePagetypes()
    {
        $item = $this->tester->grabFixture('role', 2);
        $this->assertTrue($item->savePagetypes([1,2]));
        $pagetypes = $item->pagetypes;
        $this->assertTrue(count($pagetypes)==2);
        
        //wieder entfernen
        $this->assertTrue($item->savePagetypes([]));
        
    }
    
   
}
