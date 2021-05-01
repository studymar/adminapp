<?php
namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class TeaserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\content\Teaser';
    public $depends = [
        'app\tests\fixtures\TeaserlistFixture',
        'app\tests\fixtures\LinkItemFixture',
        'app\tests\fixtures\ImageListFixture',
        'app\tests\fixtures\DocumentListFixture'
        ];
}