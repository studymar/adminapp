<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class SubnavigationFixture extends ActiveFixture
{
    public $modelClass = 'app\models\content\navigation\Subnavigation';
    public $depends = ['app\tests\fixtures\ReleaseFixture'];
}