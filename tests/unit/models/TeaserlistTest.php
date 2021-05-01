<?php

namespace tests\models;


class TeaserlistTest extends \Codeception\Test\Unit
{

    public function _fixtures()
    {
        return [
        ];
    }
    
    public function _before()
    {
    }
    public function _after()
    {
    }
    
    public function testGetAllTeasers()
    {
        $item = $this->tester->grabFixture('teaserlist', 0);
        $teasers = $item->getAllTeasers()->all();
        $this->assertTrue(count($teasers)>1);
    }
    
    public function testGetVisibleTeasers()
    {
        $item = $this->tester->grabFixture('teaserlist', 0);
        $teasers = $item->getVisibleTeasers()->all();
        foreach($teasers as $teaser){
            $this->assertTrue( $teaser->release->is_released == 1 );
        }        
    }

    public function testSortUp()
    {
        $item = $this->tester->grabFixture('teaser', 1);
        $item->sortUp();
        $this->assertTrue($item->sort == 2);

        //anderes Item gegenprüfen, muss dafür runtersortiert seinn
        $item = $this->tester->grabFixture('teaser', 0);
        $this->assertTrue($item->sort == 1);
        
    }
    
    public function testGetMaxSort()
    {
        $item = $this->tester->grabFixture('teaserlist', 0);
        
        $this->assertTrue(\app\models\content\Teaser::getMaxSort($item->id) == 2);

    }    
    
}
