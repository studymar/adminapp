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
class VueCKEditorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        ['ext/@ckeditor/ckeditor5-build-classic/build/ckeditor.js','position' => \yii\web\View::POS_HEAD],
        ['ext/@ckeditor/ckeditor5-vue2/dist/ckeditor.js','position' => \yii\web\View::POS_HEAD]
        
    ];
    public $depends = [
        'app\assets\VueAsset',
    ];
}
