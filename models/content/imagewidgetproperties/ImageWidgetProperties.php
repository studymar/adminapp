<?php

namespace app\models\content\imagewidgetproperties;

/**
 * Description of ImageWidgetProperties
 *
 * @author mwort
 */
abstract class ImageWidgetProperties {
    
    public $options = [
        'labeltext'         => 'Image',
        'preselectedImages' => null,
        'saveUrl'           => null,
        'removeUrl'         => null,
        'refreshUrl'        => null,
        'multiselect'       => false, 
        'imagemanagerUrl'   => null,
    ];

    public function __construct (){
        //prueft, ob alle properties gefuellt sind
        foreach($this->options as $key=>$value){
            if($value===null)
                throw new \ErrorException("ImagewidgetProperties Options not set correctly, $key is null");
        }
        
    }
    
    public function getLabeltext(){
        return $this->options['labeltext'];
    }
    public function getPreselectedImages(){
        return $this->options['preselectedImages'];
    }
    public function getSaveUrl(){
        return $this->options['saveUrl'];
    }
    public function getRemoveUrl(){
        return $this->options['removeUrl'];
    }
    public function getRefreshUrl(){
        return $this->options['refreshUrl'];
    }
    public function isMultiselect(){
        return $this->options['multiselect'];
    }
    public function getImagemanagerUrl(){
        return $this->options['imagemanagerUrl'];
    }
    
    public function setLabeltext($p){
        $this->options['labeltext'] = $p;
    }
    public function setPreselectedImages($p){
        $this->options['preselectedImages'] = $p;
    }
    public function setSaveUrl($p){
        $this->options['saveUrl'] = $p;
    }
    public function setRemoveUrl($p){
        $this->options['removeUrl'] = $p;
    }
    public function setRefreshUrl($p){
        $this->options['refreshUrl'] = $p;
    }
    public function setIsMultiselect($p = false){
        $this->options['multiselect'] = $p;
    }
    public function setImagemanagerUrl($p){
        $this->options['imagemanagerUrl'] = $p;
    }
    
}
