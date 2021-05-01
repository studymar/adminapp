<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class PageHasTemplateFixture extends ActiveFixture
{
    public $modelClass = 'app\models\content\PageHasTemplate';
    public $depends = ['app\tests\fixtures\ReleaseFixture', 'app\tests\fixtures\PageFixture' , 'app\tests\fixtures\TemplateFixture'];
}