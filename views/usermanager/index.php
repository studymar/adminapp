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
        <h3 class="title"> Usermanager </h3>
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
                <table class="table" id="usertable">
                    <thead>
                      <tr>
                        <th data-card-title>Nachname</th>
                        <th>Vorname</th>
                        <th data-card-action-links>Email</th>
                        <th>Verein</th>
                        <th>Rolle</th>
                        <th>Lastlogin</th>
                        <th data-card-footer></th>
                      </tr>
                    </thead>
                    <tbody>
                        <!-- Filter -->
                        <tr>
                          <?= app\models\helpers\FormFilter::renderFilter([
                              new app\models\helpers\FormFilterTextColumn($model,'lastname'),
                              new app\models\helpers\FormFilterTextColumn($model,'firstname'),
                              new app\models\helpers\FormFilterTextColumn($model,'email'),
                              new app\models\helpers\FormFilterTextColumn($model,'organisationname'),
                              new app\models\helpers\FormFilterTextColumn($model,'rolename'),
                              new app\models\helpers\FormFilterEmptyColumn(),
                              new app\models\helpers\FormFilterEmptyColumn()
                          ])
                          ?>
                        </tr>
                        <?= ListView::widget([
                            'dataProvider' => $dataProvider,
                            'itemView' => '_user_listrow',
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

    <!--
    <div class="row sameheight-container items-list-page">
        
        <div class="col-md-12">

            <div class="card items">
                <ul class="item-list striped list-with-Filter">
                    <!-- ListView Header --
                    <li class="item item-list-header">
                        <div class="item-row">
                            <div class="item-col item-col-header item-col-title">
                                <div>
                                    <span>Name</span>
                                </div>
                            </div>
                            <div class="item-col item-col-header item-col-title">
                                <div>
                                    <span>Verein</span>
                                </div>
                            </div>
                            <div class="item-col item-col-header item-col-title">
                                <div>
                                    <span>Rolle</span>
                                </div>
                            </div>
                            <div class="item-col item-col-header item-col-stats">
                                <div class="no-overflow">
                                    <span>Lastlogin</span>
                                </div>
                            </div>
                            <div class="item-col item-col-header fixed item-col-actions-dropdown">
                            </div>
                        </div>
                    </li>
                    <!-- ListView Filter --
                    <li class="item item-list-header item-list-filter">
                        <?php $form = ActiveForm::begin(FormHelper::getConfigArray());?>
                        <?= app\models\helpers\FormFilter::renderFilter([
                            new app\models\helpers\FormFilterTextColumn($model,'lastname'),
                            new app\models\helpers\FormFilterTextColumn($model,'organisationname'),
                            new app\models\helpers\FormFilterTextColumn($model,'rolename'),
                            new app\models\helpers\FormFilterTextColumn($model,'lastlogindate'),
                            //new app\models\helpers\FormFilterNumColumn($model,'anz')
                            new app\models\helpers\FormFilterEmptyColumn()
                        ])
                        ?>
                        <?php ActiveForm::end() ?>
                    </li>

                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemView' => '_user_listrow',
                        'layout' => app\models\helpers\ListViewOptions::getLayoutString(),
                        'summary'=> app\models\helpers\ListViewOptions::getSummaryString(),
                        'pager' => app\models\helpers\ListViewOptions::getPagerOptions(),
                    ]); ?>
                    
                </ul>
            </div>
            <?= Html::a('Rolle hinzufügen',['rolemanager/create'],['class'=>'btn btn-primary','role'=>"button"]) ?>
        </div>
        
    </div>
    -->



    <!--
    <div class="title-block">
        <h3 class="title"> Usermanager </h3>
    </div>

    <section class="section">


        
        <div class="row sameheight-container">
            
            <div class="col-md-12">
                <div class="card card-block sameheight-item">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'itemView' => '_userrow',
                        'columns'   =>  [
                            //'id',
                            //'firstname',
                            //'lastname',
                            [
                                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                                'label' => 'Name', 
                                'value' => function ($data) {
                                   return $data->lastname.",".$data->firstname;
                                },
                                'attribute' => 'lastname',
                            ],
                            'username',
                            [
                                'attribute' => 'organisation_id',
                                'value'     => 'organisation.name',
                            ],
                            [
                                'attribute' => 'role_id',
                                'value'     => 'role.name',
                            ],
                            //'lastlogindate:datetime',
                            [
                                'attribute' => 'lastlogindate',
                                'format' => ['date', 'php:d.m.y H:i']
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template'  => '{view} {update} {delete}',
                                'buttons'   => [
                                    'view' => function ($url, $model, $key) {
                                        return false;
                                    },
                                    'update' => function ($url, $model, $key) {
                                        return $model->role_id !== 1 ? Html::a('<i class="material-icons">edit</i>', $url, ['id'=>'edit-'.$model->id]) : '';
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return $model->role_id !== 1 ? Html::a('<i class="material-icons">delete</i>', $url, [
                                            'id'=>'delete-'.$model->id,
                                            'data' => [
                                                'confirm' => 'Sind Sie sicher, dass Sie den User "'.$model->username.'" löschen möchten?'
                                            ],
                                        ]) : '';
                                    },
                                ],
                            ],
                        ]
                    ]);
                    ?>
                </div>
            </div>
            -->

        </div>
    </section>
            
            