<?php

namespace tests\models;

use app\models\forms\LoginForm;

class LoginFormTest extends \Codeception\Test\Unit
{
    private $model;

    public function _fixtures()
    {
        return [
            /*
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
        */
        ];
    }    
    
    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    public function testLoginNoUser()
    {
        $this->model = new LoginForm();
        $this->model->username = 'not_existing_username';
        $this->model->password = 'not_existing_password';

        expect_not($this->model->login());
        expect_that(\Yii::$app->user->isGuest);
    }

    public function testLoginWrongPassword()
    {
        $this->model = new LoginForm([
            'username' => 'demo',
            'password' => 'wrong_password',
        ]);

        expect_not($this->model->login());
        expect_that(\Yii::$app->user->isGuest);
        expect($this->model->errors)->hasKey('password');
    }

    public function testLoginCorrect()
    {
        $this->model = new LoginForm();
        $this->model->username = 'admin';
        $this->model->password = 'admin';

        $this->assertTrue($this->model->login());
        $this->assertFalse(\Yii::$app->user->isGuest);
        expect($this->model->errors)->hasntKey('password');
    }

}
