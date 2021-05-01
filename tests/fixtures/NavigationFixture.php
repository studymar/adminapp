<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class NavigationFixture extends ActiveFixture
{
    public $modelClass = 'app\models\content\navigation\Navigation';
    public $depends = ['app\tests\fixtures\ReleaseFixture'];
}