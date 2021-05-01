<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\content;

/**
 * Description of IsDeletablePageHasTemplate
 *
 * @author Mark Worthmann
 */
interface IsDeletablePageHasTemplateInterface {
    
    public static function findByPageHasTemplateId($p);
    public function deleteItem();
    
}
