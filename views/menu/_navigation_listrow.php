<?php

/* 
 * Zeile für den Navigationmanager
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

                    <tr>
                      <td><?= $model->sort ?></td>
                      <td><?= ( !isset($model->navigation_id) && $model->path == null )? Html::a($model->name,['menu/subnavigationmanager','p'=>$model->id]) : $model->name ?></td>
                      <td><?= $model->path ?></td>
                      <td><?= ($model->path != null)?'extern':'intern' ?></td>
                      <td><?= ($model->release->isVisible())?'Ja':'Nein' /*visible*/?></td>
                      <td>
                            <div class="d-flex">
                                <div class="mr-2">
                                    <a class="edit" href="<?= Url::toRoute((isset($model->navigation_id))?['menu/update','p'=>$model->id,'p2'=>$model->navigation_id]:['menu/update','p'=>$model->id]) ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                                <div class="mr-2">
                                    <a class="remove" href="<?= Url::toRoute((!isset($model->navigation_id))?['menu/delete','p'=>$model->id]:['menu/delete','p'=>$model->id, 'p2'=>true]) ?>" data-toggle="modal" data-target="#confirm-modal" data-name="<?= $model->name ?>" >
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                            </div>
                      </td>
                    </tr>


