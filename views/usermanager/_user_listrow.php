<?php

/* 
 * Zeile für den Usermanager
 */

use yii\helpers\Url;
?>

                    <tr>
                      <td><?= $model->lastname ?></td>
                      <td><?= $model->firstname ?></td>
                      <td><?= $model->email ?></td>
                      <td><?= $model->organisation->name ?></td>
                      <td><?= $model->role->name ?></td>
                      <td><?= $model->lastlogindate ?></td>
                      <td>
                            <div class="d-flex">
                                <div class="mr-2">
                                    <a class="remove" href="<?= Url::toRoute(['usermanager/delete','p'=>$model->id]) ?>" data-toggle="modal" data-target="#confirm-modal" data-name="<?= $model->lastname ?>" >
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                                <div class="mr-2">
                                    <a class="edit" href="<?= Url::toRoute(['usermanager/update','p'=>$model->id]) ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                            </div>
                      </td>
                    </tr>
<!--
                    <li class="item">
                        <div class="item-row">
                            <div class="item-col pull-left item-col-title">
                                <div class="item-heading">Name</div>
                                <div>
                                    <a href="<?= Url::toRoute(["usermanager/update","p"=>$model->id]) ?>" class="">
                                        <div class="item-title"> <?= $model->lastname ?> </div>
                                    </a>
                                </div>
                            </div>
                            <div class="item-col pull-left item-col-title">
                                <div class="item-heading">Verein</div>
                                <div>
                                    <a href="<?= Url::toRoute(["usermanager/update","p"=>$model->id]) ?>" class="">
                                        <div class="item-title"> <?= $model->organisation->name ?> </div>
                                    </a>
                                </div>
                            </div>
                            <div class="item-col pull-left item-col-title">
                                <div class="item-heading">Verein</div>
                                <div>
                                    <a href="<?= Url::toRoute(["usermanager/update","p"=>$model->id]) ?>" class="">
                                        <div class="item-title"> <?= $model->role->name ?> </div>
                                    </a>
                                </div>
                            </div>
                            <div class="item-col item-col-header item-col-stats">
                                <div class="item-heading">Letzter Login</div>
                                <div>
                                    <a href="<?= Url::toRoute(["usermanager/update","p"=>$model->id]) ?>" class="">
                                        <div class="item-title"> <?= $model->lastlogindate ?> </div>
                                    </a>
                                </div>
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
                                                <a class="remove" href="<?= Url::toRoute(['rolemanager/delete','p'=>$model->id]) ?>" data-toggle="modal" data-target="#confirm-modal" data-name="<?= $model->lastname ?>" >
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
-->

