<?php
use app\tests\fixtures\ReleaseFixture;
use app\tests\fixtures\PageFixture;

class AAFixtureCest
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
                'class' => \app\tests\fixtures\RightFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'right.php'
            ],
            'role' => [
                'class' => \app\tests\fixtures\RoleFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'role.php'
            ],
            'role_has_right' => [
                'class' => \app\tests\fixtures\RoleHasRightFixture::className(),
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
            'release' => [
                'class' => \app\tests\fixtures\ReleaseFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'release.php'
            ],
            'navigation' => [
                'class' => \app\tests\fixtures\NavigationFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'navigation.php'
            ],
            'subnavigation' => [
                'class' => \app\tests\fixtures\SubnavigationFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'subnavigation.php'
            ],
            'pagetype' => [
                'class' => \app\tests\fixtures\PagetypeFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'pagetype.php'
            ],
            'page' => [
                'class' => \app\tests\fixtures\PageFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'page.php'
            ],
            'template' => [
                'class' => \app\tests\fixtures\TemplateFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'template.php'
            ],
            'page_has_template' => [
                'class' => \app\tests\fixtures\PageHasTemplateFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'page_has_template.php'
            ],
            'teaserlist' => [
                'class' => \app\tests\fixtures\TeaserlistFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'teaserlist.php'
            ],
            'teaser' => [
                'class' => \app\tests\fixtures\TeaserFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'teaser.php'
            ],
            'link_item' => [
                'class' => \app\tests\fixtures\LinkItemFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'link_item.php'
            ],
            'image_list' => [
                'class' => \app\tests\fixtures\ImageListFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'image_list.php'
            ],
            'image_item' => [
                'class' => \app\tests\fixtures\ImageItemFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'image_item.php'
            ],
            'uploadedimage' => [
                'class' => \app\tests\fixtures\UploadedimageFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'uploadedimage.php'
            ],
            'document_list' => [
                'class' => \app\tests\fixtures\DocumentListFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'document_list.php'
            ],
            'document_item' => [
                'class' => \app\tests\fixtures\DocumentItemFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'document_item.php'
            ],
            'document' => [
                'class' => \app\tests\fixtures\DocumentFixture::className(),
                // fixture data located in tests/_data/user.php
                'dataFile' => codecept_data_dir() . 'document.php'
            ],
        ];
    }
    
    
    public function _before(\FunctionalTester $I)
    {
    }

    public function openFixtureLoaded(\FunctionalTester $I)
    {
        $I->amOnPage('/');

    }
}