<?php

/*
 * Beispielaufruf:
 *                   <!-- ListView Filter -->
 *                   <li class="item item-list-header item-list-filter">
 *                       <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
 *                       <?= app\models\helpers\FormFilter::renderFilter([
 *                           new app\models\helpers\FormFilterTextColumn($model,'name'),
 *                           //new app\models\helpers\FormFilterNumColumn($model,'anz')
 *                           new app\models\helpers\FormFilterEmptyColumn()
 *                       ])
 *                       ?>
 *                       <?php ActiveForm::end() ?>
 *                   </li>
 * 
 * Ergibt z.b.
 *                       <div class="item-row">
 *                           <div class="item-col item-col-header item-col-title">
 *                               <div class="">
 *                                   <?= Html::activeInput('name',$model,'name', ['class'=>'form-control','placeholder'=>'Name']) ?>
 *                                   <?php if($model->hasErrors('name')){ ?><span class="has-error"><?= $model->getErrors('name')[0] ?></span><?php } ?>
 *                               </div>
 *                           </div>
 *                           <div class="item-col item-col-header item-col-stats">
 *                               <div class="">
 *                                   <?= Html::activeInput('Filter[name]',$model,'name', ['class'=>'form-control','placeholder'=>'Name']) ?>
 *                                   <?php if($model->hasErrors('name')){ ?><span class="has-error"><?= $model->getErrors('name')[0] ?></span><?php } ?>
 *                               </div>
 *                           </div>
 *                           <div class="item-col item-col-header fixed item-col-actions-dropdown">
 *                           </div>
 *                       </div>
 * 
 */

namespace app\models\helpers;

use Yii;
use yii\base\Model;
use yii\helpers\Html;


/**
 * Description of FormConfigArray
 *
 * @author mwort
 */
class FormFilter {
    
    /**
     * Gibt das formConfigArray für en View zurück mit allen Einstellungen
     * Zwingend in activeForm einzubinden, um Validierung korrekt zu ermöglichen
     * (setzt korrekte css-klassen und breiten)
     * @param string $formId [optional | default = 'form'] Id des <form>-tags ist einstellbar,
     * um mehrere unterschiedliche Ids auf einer Seite verwenden zu können
     * @param $isHorizontal [optional | default = true] true = Zweispaltig, false = label und input untereinander
     * @return array
     */
    public static function renderFilter(array $fieldsArray = []){
        $renderString = self::getFormBegin();
        foreach($fieldsArray as $field){
        $renderString.= $field->renderColumn();
        }
        $renderString.= self::getFormEnd();
        return $renderString;
    }


    public static function getFormBegin(){
        return '
        ';
        /*
        return '
                        <div class="item-row">
            
        ';
         */
    }

    public static function getFormEnd(){
        return '
        ';
        /*
        return '
                            <div class="item-col item-col-header fixed item-col-actions-dropdown">
                        </div>
            
        ';
         * 
         */
    }


    
}



class FormFilterColumn extends Model {
    protected $model;
    protected $colname;
    protected $placeholder;
    public function __construct($model, $colname, $placeholder = "") {
        $this->model = $model;
        $this->colname = $colname;
        $this->placeholder = $placeholder;
    }
}

class FormFilterTextColumn extends FormFilterColumn {
    public function renderColumn(){
        $colname = $this->colname;
        $input = Html::activeInput('text',$this->model,$this->colname, ['class'=>'form-control','placeholder'=>$this->placeholder, 'name'=>'Filter['.$this->colname.']', 'onchange'=>'this.form.submit()']);
        $error = $this->model->hasErrors($this->colname);
        $errortext = "";
        if($error){
            $errortext = '<div class="has-error">'.$error[0].'</div>';
        }
        return <<<EOT
                            <td data-filtercolumn>$input $errortext</td>
        EOT;
        /*
        return <<<EOT
                            <div class="item-col item-col-header item-col-title">
                                <div class="">
                                    $input
                                    $errortext
                                </div>
                            </div>
        EOT;
         */
    }
}
    

class FormFilterNumColumn extends FormFilterColumn {
    public function renderColumn(){
        $input = Html::activeInput('text',$this->model,$this->colname, ['class'=>'form-control','placeholder'=>$this->placeholder, 'name'=>'Filter['.$this->colname.']', 'onchange'=>'this.form.submit()']);
        $error = $this->model->hasErrors($this->colname);
        $errortext = "";
        if($error){
            $errortext = '<div class="has-error">'.$error[0].'</div>';
        }
        return <<<EOT
                            <td data-filtercolumn>$input $errortext</td>
        EOT;
        /*
        return <<<EOT
                            <div class="item-col item-col-header item-col-stats">
                                <div class="">
                                    $input
                                    $errortext
                                </div>
                            </div>
        EOT;
        */      
    }    
}

class FormFilterEmptyColumn extends FormFilterColumn {
    public function __construct() {
    }
    public function renderColumn(){
        return <<<EOT
                            <td data-filtercolumn-empty></td>
        EOT;
        /*
        return <<<EOT
                            <div class="item-col item-col-header item-col-stats">
                            </div>
        EOT;
        */
    }    
}

