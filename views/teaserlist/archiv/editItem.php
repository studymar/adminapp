<?php

/* 
 * Formular zum Editieren der Inhalte eines Teasers
 * @param Object $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\helpers\FormHelper;
use app\models\content\LinkItem;
use app\models\content\DocumentList;

use app\assets\FormAsset;
FormAsset::register($this);

?>
<?php if(!Yii::$app->user->isGuest){ ?>
    <h3>Teaser - Edit</h3>

    <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
        <?php if($topSuccess){ ?>
        <p class="alert alert-success"><?= $topSuccess ?></p>
        <?php } ?>
        <?php if($topError){ ?>
        <p class="alert alert-danger"><?= $topError ?></p>
        <?php } ?>
    
        <?= $form->field($model, 'headline') ?>
        <?= $form->field($model, 'text') ?>
        <?= $this->render('@app/views/partials/_link_item_form',
            [
            'model'=>($model->link_item_id) ? $model->linkItem : new LinkItem(), 
            'form'=>$form,
            'removeLink' => Url::toRoute(['teaserlist/remove-link','p'=>$model->id]),
            'saveLink' => Url::toRoute(['teaserlist/save-link','p'=>$model->id]),
            ]) ?>
        <?= $this->render('@app/views/partials/_document_item_form',
            [
            'documentList'=>$model->documentList, 
            'form'=>$form,
            'removeLink' => Url::toRoute(['teaserlist/remove-link','p'=>$model->id]),
            'saveLink' => Url::toRoute(['teaserlist/save-link','p'=>$model->id]),
            ]) ?>
        <?= $this->render('@app/views/partials/_releaseform',['model'=>$model, 'form'=>$form]) ?>
        
        
        <?= Html::submitButton('Speichern',['class'=>'btn btn-primary']) ?>
        <?= Html::a('Zurück',['page/edit','p'=>$model->teaserlist->pageHasTemplate->page->urlname],['class'=>'btn btn-outline-primary']) ?>
        
    <?php ActiveForm::end() ?>


<?php } ?>


