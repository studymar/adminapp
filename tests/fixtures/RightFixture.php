<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class RightFixture extends ActiveFixture
{
    public $modelClass = 'app\models\role\Right';
    public $depends = ['app\tests\fixtures\RightgroupFixture'];
}