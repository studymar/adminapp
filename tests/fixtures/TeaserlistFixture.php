<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class TeaserlistFixture extends ActiveFixture
{
    public $modelClass = 'app\models\content\Teaserlist';
    public $depends = ['app\tests\fixtures\PageHasTemplateFixture'];
}