<?php

namespace tests\models;

use app\tests\fixtures\UserFixture;
use app\tests\fixtures\OrganisationFixture;
use app\tests\fixtures\OrganisationtypeFixture;
use app\tests\fixtures\RoleFixture;
use app\tests\fixtures\RightFixture;
use app\tests\fixtures\RoleHasRightFixture;
use app\tests\fixtures\RightgroupFixture;
use app\models\user\User;
use app\models\role\Right;

class UserTest extends \Codeception\Test\Unit
{


    public function _fixtures()
    {
        return [
            'rightgroup' => [
                'class' => \app\tests\fixtures\RightgroupFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'rightgroup.php'
            ],
            'right' => [
                'class' => RightFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'right.php'
            ],
            'role' => [
                'class' => RoleFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'role.php'
            ],
            'role_has_right' => [
                'class' => RoleHasRightFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'role_has_right.php'
            ],
            'organisationtype' => [
                'class' => \app\tests\fixtures\OrganisationtypeFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'organisationtype.php'
            ],
            'organisation' => [
                'class' => \app\tests\fixtures\OrganisationFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'organisation.php'
            ],
            'user' => [
                'class' => \app\tests\fixtures\UserFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ];
    }

    
    protected $_userFromRegistrationForm ;
    
    protected $_usernameAdmin ;
    protected $_emailAdmin;
    protected $_passwordAdmin;

    protected $_usernameEditor ;
    
    public function _before()
    {
        //user 1 = grade registrierungsformular ausgefüllt
        $this->_userFromRegistrationForm = new User();
        $this->_userFromRegistrationForm->scenario     = "registration";
        $this->_userFromRegistrationForm->firstname    = 'test-admin';
        $this->_userFromRegistrationForm->lastname     = 'test-admin';
        $this->_userFromRegistrationForm->email        = 'test-admin@admin.de';
        $this->_userFromRegistrationForm->organisation_id = 100;
        $this->_userFromRegistrationForm->username     = 'testadmin';
        $this->_userFromRegistrationForm->password     = 'password1';
        $this->_userFromRegistrationForm->password_repeat = 'password1';
        
        //user 2 = Admin
        $this->_usernameAdmin   = 'admin';
        $this->_emailAdmin      = 'admin@admin.de';
        $this->_passwordAdmin   = 'admin';
    
        //user 3 = Editor
        $this->_usernameEditor = 'editor';
    }
    public function _after()
    {
    }
    
    public function testFindIdentity()
    {
        $item = $this->tester->grabFixture('user', 0);
        expect_that($user = User::findIdentity($item->id));
        expect($user->username)->equals($user->username);

        expect_not(User::findIdentity(999));
    }

    public function testFindUserByUsername()
    {
        $item = $this->tester->grabFixture('user', 0);
        $user = User::findByUsername($item->username);
        $this->assertTrue($item->username == $user->username);
        $this->assertNULL(User::findByUsername('not-admin999'));
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $item = $this->tester->grabFixture('user', 0);
        $this->assertTrue($item->validatePassword($this->_passwordAdmin));

        $this->assertFalse($item->validatePassword('not-admin'));
    }

    /**
     */
    public function testFindByEmail()
    {
        $user = User::findByEmail($this->_emailAdmin);
        //expect_that($user->validateAuthKey('test100key'));
        //expect_not($user->validateAuthKey('test102key'));

        $this->assertTrue( $user->username == $this->_usernameAdmin );
    }
    
    /**
     * @depends testFindUserByUsername
     */
    public function testCreateUser()
    {
        $unHashedPassword = $this->_userFromRegistrationForm->password;
        $this->_userFromRegistrationForm->createUser();

        $item = User::findByUsername($this->_userFromRegistrationForm->username);
        $this->assertTrue( $item->isvalidated == 0 );
        $this->assertTrue( $item->role_id == 2 );
        $this->assertTrue( $item->password != $unHashedPassword );//muss verschlüsselt sein
        
        //wieder löschen
        $this->assertTrue( $item->delete() == 1 );
        
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testUserHasRight()
    {
        $item = User::findByUsername($this->_usernameAdmin);
        $this->assertTrue( $item->hasRight( Right::SEITENDETAILS_BEARBEITEN) );
        
        $item = User::findByUsername($this->_usernameEditor);
        $this->assertFalse( $item->hasRight( Right::USERVERWALTUNG) );
    }

    /**
     * @depends testFindUserByUsername
     * @depends testUserHasRight
     */
    public function testLock()
    {
        $item = User::findByUsername($this->_usernameEditor);
        $this->assertTrue( $item->lock() );
        
        $this->assertFalse( $item->hasRight( Right::MEINE_DATEN) );
        //lock wird in unlock wieder rausgenommen
    }

    /**
     * @depends testFindUserByUsername
     * @depends testUserHasRight
     * @depends testLock
     */
    public function testUnlock()
    {
        $item = User::findByUsername($this->_usernameEditor);
        $this->assertTrue( $item->unlock() );
        
        $item = User::findByUsername($this->_usernameEditor);
        $this->assertTrue( $item->hasRight( Right::MEINE_DATEN) );
    }
    
}
