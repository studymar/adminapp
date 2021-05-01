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
class VueSortableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        ['//cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js','position' => \yii\web\View::POS_HEAD],
        ['//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js','position' => \yii\web\View::POS_HEAD],
    ];
    public $depends = [
        'app\assets\LayoutAsset',
        'app\assets\VueAsset',
    ];
}
