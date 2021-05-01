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
class VueWYSIWYGEditorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        ['https://unpkg.com/@mycure/vue-wysiwyg/dist/mc-wysiwyg.js','position' => \yii\web\View::POS_HEAD]
        
    ];
    public $depends = [
        'app\assets\VueAsset',
    ];
}
