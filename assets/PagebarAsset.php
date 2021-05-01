<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Mark Worthmann
 * @since 2.0
 */
class PagebarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/pagebar.scss'
    ];
    public $js = [
        'js/pagebar.js'
    ];
    public $depends = [
        'app\assets\LayoutAsset',
    ];
}
