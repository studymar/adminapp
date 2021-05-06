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
class VueAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        ['https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js','position' => \yii\web\View::POS_HEAD] //prod-mode
        //['https://cdn.jsdelivr.net/npm/vue/dist/vue.js','position' => \yii\web\View::POS_HEAD] //dev-mode
        //['https://cdn.jsdelivr.net/npm/vue/dist/vue.js','position' => \yii\web\View::POS_END] //dev-mode
        //['https://cdn.jsdelivr.net/npm/vue','position' => \yii\web\View::POS_HEAD],
        //['https://unpkg.com/axios/dist/axios.min.js','position' => \yii\web\View::POS_HEAD]
    ];
    public $depends = [
        'app\assets\LayoutAsset',
    ];
}
