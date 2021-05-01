<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class OrganisationFixture extends ActiveFixture
{
    public $modelClass = 'app\models\organisation\Organisation';
    public $depends = ['app\tests\fixtures\OrganisationtypeFixture'];
}