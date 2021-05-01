<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class PageFixture extends ActiveFixture
{
    public $modelClass = 'app\models\content\Page';
    public $depends = ['app\tests\fixtures\ReleaseFixture', 'app\tests\fixtures\PagetypeFixture'];
}