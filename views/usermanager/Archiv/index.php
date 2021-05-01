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

    <section class="section">        
        <div class="row sameheight-container">
            
            <div class="col-md-12">
                <div class="card card-block sameheight-item">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'itemView' => '_userrow',
                        'tableOptions' => [
                            'class'   =>  'table table-striped tablesaw tablesaw-stack',
                            'data-tablesaw-mode'    => 'stack',
                        ],
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


        </div>
    </section>
            
            