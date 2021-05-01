<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Mark Worthmann
 * @since 2.0
 */
class VueAdvancedCropperAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        ['ext/vue-advanced-cropper/index.umd.js','position' => \yii\web\View::POS_HEAD],
    ];
    public $depends = [
        'app\assets\LayoutAsset',
        'app\assets\VueAsset',
    ];
}
