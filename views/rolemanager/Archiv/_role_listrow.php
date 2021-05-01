<?php

/* 
 * Zeile für den Rolemanager
 */

use yii\helpers\Url;
?>


                    <li class="item">
                        <div class="item-row">
                            <div class="item-col pull-left item-col-title">
                                <div class="item-heading">Name</div>
                                <div>
                                    <a href="<?= Url::toRoute(["rolemanager/update","p"=>$model->id]) ?>" class="">
                                        <h4 class="item-title"> <?= $model->name ?> </h4>
                                    </a>
                                </div>
                            </div>
                            <div class="item-col item-col-stats no-overflow">
                                <div class="item-heading">Anzahl User</div>
                                <div class="no-overflow"> <?= $model->getCountUsers() ?> </div>
                            </div>
                            <div class="item-col fixed item-col-actions-dropdown">
                                <div class="item-actions-dropdown">
                                    <a class="item-actions-toggle-btn">
                                        <span class="inactive">
                                            <i class="fa fa-cog"></i>
                                        </span>
                                        <span class="active">
                                            <i class="fa fa-chevron-circle-right"></i>
                                        </span>
                                    </a>
                                    <div class="item-actions-block">
                                        <ul class="item-actions-list">
                                            <li>
                                                <a class="remove <?= ($model->getCountUsers() > 0)?'invisible':'' ?>" href="<?= Url::toRoute(['rolemanager/delete','p'=>$model->id]) ?>" data-toggle="modal" data-target="#confirm-modal" data-name="<?= $model->name ?>" >
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="edit" href="<?= Url::toRoute(['rolemanager/update','p'=>$model->id]) ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>


