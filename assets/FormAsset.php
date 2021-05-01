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
class FormAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/form.scss',
        'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
        //'ext/bootstrap-datepicker-1.6.4-dist/css/bootstrap-datepicker.min.css'
    ];
    public $js = [
        //['https://cdn.jsdelivr.net/npm/flatpickr','position' => \yii\web\View::POS_HEAD],
        ['https://unpkg.com/flatpickr@4.5.7/dist/flatpickr.js','position' => \yii\web\View::POS_HEAD],
        ['https://cdn.jsdelivr.net/npm/vue-flatpickr-component@8/dist/vue-flatpickr.js','position' => \yii\web\View::POS_HEAD],
        //['ext/bootstrap-datepicker-1.6.4-dist/js/bootstrap-datepicker.min.js','position' => \yii\web\View::POS_HEAD],
        //['https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js','position' => \yii\web\View::POS_HEAD],
        //['https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/locale/de.js','position' => \yii\web\View::POS_HEAD],
        //['ext/vee-validate/vee-validate.js','position' => \yii\web\View::POS_HEAD],
        //['ext/vee-validate/de.js','position' => \yii\web\View::POS_HEAD],
        ['ext/vee-validate/dist/vee-validate.js','position' => \yii\web\View::POS_HEAD],
        ['ext/vee-validate/dist/rules.umd.js','position' => \yii\web\View::POS_HEAD],
        ['js/form-validation.js','position' => \yii\web\View::POS_HEAD],
        ['js/form.js','position' => \yii\web\View::POS_END],
    ];
    public $depends = [
        'app\assets\LayoutAsset',
        'app\assets\VueAsset',
    ];
}
