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
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'ext/tablesaw/tablesaw.css',
    ];
    public $js = [
        //'ext/tablesaw/tablesaw.js', //responsive table
        //'ext/tablesaw/tablesaw-init.js',
        'ext/bootstrap-table2cards/tableToCards.js'
    ];
    public $depends = [
    ];
}
