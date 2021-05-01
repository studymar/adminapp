<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class RoleFixture extends ActiveFixture
{
    public $modelClass = 'app\models\role\Role';
    public $depends = ['app\tests\fixtures\RoleHasRightFixture'];
}