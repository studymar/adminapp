<?php

/* 
 * Usermanager
 * @param Object $model
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\helpers\FormHelper;
use yii\widgets\ListView;
use yii\grid\GridView;

use app\assets\FormAsset;
FormAsset::register($this);
//use app\assets\TableAsset;
//TableAsset::register($this);

?>
    <div class="title-block">
        <h3 class="title"> Rolemanager </h3>
    </div>

    <div class="row sameheight-container items-list-page">
        
        <div class="col-md-12">

            <div class="card items">
                <ul class="item-list striped list-with-Filter">
                    <!-- ListView Header -->
                    <li class="item item-list-header">
                        <div class="item-row">
                            <div class="item-col item-col-header item-col-title">
                                <div>
                                    <span>Name</span>
                                </div>
                            </div>
                            <div class="item-col item-col-header item-col-stats">
                                <div class="no-overflow">
                                    <span>Anzahl User</span>
                                </div>
                            </div>
                            <div class="item-col item-col-header fixed item-col-actions-dropdown">
                            </div>
                        </div>
                    </li>
                    <!-- ListView Filter -->
                    <li class="item item-list-header item-list-filter">
                        <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                        <?= app\models\helpers\FormFilter::renderFilter([
                            new app\models\helpers\FormFilterTextColumn($model,'name'),
                            //new app\models\helpers\FormFilterNumColumn($model,'anz')
                            new app\models\helpers\FormFilterEmptyColumn()
                        ])
                        ?>
                        <?php ActiveForm::end() ?>
                    </li>

                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_role_listrow',
                        'layout' => app\models\helpers\ListViewOptions::getLayoutString(),
                        'summary'=> app\models\helpers\ListViewOptions::getSummaryString(),
                        'pager' => app\models\helpers\ListViewOptions::getPagerOptions(),
                    ]); ?>
                    
                </ul>
            </div>
            <?= Html::a('Rolle hinzufügen',['rolemanager/create'],['class'=>'btn btn-primary','role'=>"button"]) ?>
        </div>
        
    </div>
                    
                    
                    
    
