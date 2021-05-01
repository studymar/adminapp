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
class Vue2DropzoneAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        ['ext/vue2Dropzone/vue2Dropzone.min.css','position' => \yii\web\View::POS_HEAD],
    ];
    public $js = [
        ['ext/vue2Dropzone/vue2Dropzone.js','position' => \yii\web\View::POS_HEAD],
    ];
    public $depends = [
        'app\assets\LayoutAsset',
        'app\assets\VueAsset',
    ];
}
