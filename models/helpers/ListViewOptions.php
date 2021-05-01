<?php


namespace app\models\helpers;

/**
 * Description of FormConfigArray
 *
 * @author mwort
 */
class ListViewOptions {
    
    /**
     * Gibt die Options für den Pager zurück
     * @return array
     */
    public static function getPagerOptions(){
        return
        [
            'firstPageLabel' => '<span class="material-icons">first_page</span>',
            'lastPageLabel' => '<span class="material-icons">last_page</span>',
            'prevPageLabel' => '<span class="material-icons">navigate_before</span>',
            'nextPageLabel' => '<span class="material-icons">navigate_next</span>',
            'maxButtonCount' => 3,

            // Customizing options for pager container tag
            'options' => [
                'tag' => 'ul',
                'class' => 'pagination',
            ],

            // Customizing CSS class for pager link
            'linkOptions' => ['class' => 'page-link'],
            'activePageCssClass' => 'active',
            'disabledPageCssClass' => 'd-none',

            // Customizing CSS class for navigating link
            'prevPageCssClass' => 'page-item',
            'pageCssClass' => 'page-item',
            'nextPageCssClass' => 'page-item',
            'firstPageCssClass' => 'page-item',
            'lastPageCssClass' => 'page-item',
        ];
    }
    
    public static function getSummaryString(){
        return "{begin}-{end} von {totalCount}";
    }
    
    public static function getLayoutString(){
        return "{items}\n<nav class=\"text-right\">{pager}</nav>\n<div class=\"summary text-right\">{summary}</div>\n";
    }
    
    
    
}
