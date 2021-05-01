<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\helpers;

/**
 * Description of FormConfigArray
 *
 * @author mwort
 */
class FormHelper {
    
    /**
     * Gibt das formConfigArray für en View zurück mit allen Einstellungen
     * Zwingend in activeForm einzubinden, um Validierung korrekt zu ermöglichen
     * (setzt korrekte css-klassen und breiten)
     * @param string $formId [optional | default = 'form'] Id des <form>-tags ist einstellbar,
     * um mehrere unterschiedliche Ids auf einer Seite verwenden zu können
     * @param $isHorizontal [optional | default = true] true = Zweispaltig, false = label und input untereinander
     * @return array
     */
    public static function getConfigArray($formId = "form", $isHorizontal = true){
        return
        [
            'id' => $formId,
            'options'           => ['class' => ''],
            'layout'            => ($isHorizontal)?'horizontal':'default',
            'successCssClass'   => 'form-group was-validated is-valid',
            'errorCssClass'     => 'form-group was-validated is-invalid',
            'fieldConfig'  => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'options'  => ['class' => ($isHorizontal)?'row':''],
                'horizontalCssClasses' => [
                    'label'     => 'col-sm-3',
                    'wrapper'   => 'col-sm-9',
                    'error'     => '',
                    'hint'      => ''
                ],
            ]
        ];
    }
    
}
