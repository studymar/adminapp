<?php

namespace app\models\content\imagewidgetproperties;

use app\models\content\imagewidgetproperties\ImageWidgetProperties;
use yii\helpers\Url;

/**
 * Description of ImageWidgetPropertiesText
 *
 * @author mwort
 */
class ImageWidgetPropertiesText extends ImageWidgetProperties{

    
    public function __construct($item){
        $this->setPreselectedImages($item->uploadedimages);
        $this->setSaveUrl(Url::toRoute(['text/save-images','p'=>$item->id]));
        $this->setRemoveUrl(Url::toRoute(['text/remove-image','p'=>$item->id]));
        $this->setRefreshUrl(Url::toRoute(['text/refresh-image-widget','p'=>$item->id]));
        $this->setImagemanagerUrl(Url::toRoute(['image/index']));
        parent::__construct();
    }
    
}
