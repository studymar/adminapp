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
use app\assets\TableAsset;
TableAsset::register($this);

?>

    <div class="title-block">
        <h3 class="title"> <?= (isset($navigation))?'Submenu von "'.$navigation->name.'"':'Navigationmanager' ?> </h3>
    </div>

    <div class="row items-list-page">       
        <div class="col-md-12">
            <div class="card items">

                <?php /* TableAsset::register benötigt, für Mobile-Ansicht als Cards
                <!-- Attributes: 
                data-card-title wird als Überschrift der Card dargestellt, 
                data-card-action-links = wird ohne label in card dargestellt, 
                data-card-footer = wird in card abgesetzt als Footer dargestellt 
                data-card-merge-name="EinName" data-card-merge-index="1" für mobile zusammenführen von zwei Spalten
                 * -->
                 * 
                 */
                ?>
                <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                <table class="table" id="navigationtable">
                    <thead>
                      <tr>
                        <th>Nr.</th>
                        <th data-card-title>Name</th>
                        <th>Externe Url</th>
                        <th>Seite</th>
                        <th>Sichtbar?</th>
                        <th data-card-footer></th>
                      </tr>
                    </thead>
                    <tbody>
                        <!-- Filter -->
                        <tr>
                          <?= app\models\helpers\FormFilter::renderFilter([
                              new app\models\helpers\FormFilterEmptyColumn(),
                              new app\models\helpers\FormFilterTextColumn($model,'name'),
                              new app\models\helpers\FormFilterNumColumn($model,'path'),
                              new app\models\helpers\FormFilterEmptyColumn(),
                              new app\models\helpers\FormFilterEmptyColumn(),
                              new app\models\helpers\FormFilterEmptyColumn()
                          ])
                          ?>
                        </tr>
                        <?= ListView::widget([
                            'dataProvider' => $dataProvider,
                            'itemView' => '_navigation_listrow',
                            'layout' => app\models\helpers\ListViewOptions::getLayoutString(),
                            'summary'=> app\models\helpers\ListViewOptions::getSummaryString(),
                            'pager' => app\models\helpers\ListViewOptions::getPagerOptions(),
                        ]); ?>

                  </tbody>
                </table>
                <?php ActiveForm::end() ?>

            </div>
        </div>
    </div>

    <?php if(isset($navigation)){ /*submenupunkt hinzufügen */ ?>
    <?= Html::a('Seite hinzufügen',['menu/create-submenu-item','p'=>$navigation->id],['class'=>'btn btn-primary', 'id'=>'newSubmenu','role'=>"button"]) ?>
    <?= Html::a('Externen Link  hinzufügen',['menu/create-extern-submenu-item','p'=>$navigation->id],['class'=>'btn btn-primary', 'id'=>'newSubmenu','role'=>"button"]) ?>
    <?php } else { /*hauptmenüpunkt hinzufügen */ ?>
    <?= Html::a('Seite hinzufügen',['menu/create-menu-item'],['class'=>'btn btn-primary', 'id'=>'newMenu', 'role'=>"button"]) ?>
    <?= Html::a('Externen Link  hinzufügen',['menu/create-extern-menu-item'],['class'=>'btn btn-primary', 'id'=>'newMenu', 'role'=>"button"]) ?>
    <?php } ?>
    <?= (isset($navigation))?Html::a('Zurück',['menu/navigationmanager'],['class'=>'btn btn-outline-primary','role'=>"button"]):''?>
            