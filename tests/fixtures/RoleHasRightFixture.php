<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class RoleHasRightFixture extends ActiveFixture
{
    public $modelClass = 'app\models\role\RoleHasRight';
    public $depends = ['app\tests\fixtures\RightFixture'];
}